<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    protected $serverKey;
    protected $projectId;

    public function __construct()
    {
        $this->projectId = config('firebase.web.project_id');
    }

    /**
     * Send notification to specific users
     */
    public function sendToUsers(array $userIds, string $title, string $body, array $data = [], ?string $icon = null): array
    {
        $tokens = UserDevice::whereIn('user_id', $userIds)
            ->active()
            ->pluck('fcm_token')
            ->toArray();

        if (empty($tokens)) {
            Log::info('[FCM] No active devices found for users: ' . implode(',', $userIds));
            return ['success' => false, 'message' => 'No devices found'];
        }

        return $this->sendToTokens($tokens, $title, $body, $data, $icon);
    }

    /**
     * Send notification to all admins
     */
    public function sendToAdmins(string $title, string $body, array $data = [], ?string $icon = null): array
    {
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
        return $this->sendToUsers($adminIds, $title, $body, $data, $icon);
    }

    /**
     * Send notification to all staff (admins + cashiers)
     */
    public function sendToStaff(string $title, string $body, array $data = [], ?string $icon = null): array
    {
        $staffIds = User::whereIn('role', ['admin', 'cashier'])->pluck('id')->toArray();
        return $this->sendToUsers($staffIds, $title, $body, $data, $icon);
    }

    /**
     * Send notification to specific FCM tokens
     */
    public function sendToTokens(array $tokens, string $title, string $body, array $data = [], ?string $icon = null): array
    {
        $results = [];
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            Log::error('[FCM] Failed to get access token');
            return ['success' => false, 'message' => 'Failed to get access token'];
        }

        foreach ($tokens as $token) {
            try {
                // For web, we send pure data messages so onMessage can handle foreground
                // The Service Worker (firebase-messaging-sw.js) handles background display
                $message = [
                    'message' => [
                        'token' => $token,
                        'webpush' => [
                            'fcm_options' => [
                                'link' => $data['url'] ?? '/',
                            ],
                        ],
                        // Data payload - received by onMessage(foreground) & onBackgroundMessage(background)
                        'data' => array_merge($data, [
                            'title' => $title,
                            'body' => $body,
                            'icon' => $icon ?? '/icons/android/android-launchericon-192-192.png',
                            'click_action' => $data['url'] ?? '/',
                            'sound' => 'notification',
                        ]),
                    ],
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ])->post(
                    "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send",
                    $message
                );

                if ($response->successful()) {
                    $results[] = ['token' => substr($token, 0, 20) . '...', 'success' => true];
                    Log::info('[FCM] Notification sent successfully');
                } else {
                    $results[] = ['token' => substr($token, 0, 20) . '...', 'success' => false, 'error' => $response->body()];
                    Log::error('[FCM] Failed to send: ' . $response->body());

                    // If token is invalid, deactivate it
                    if (str_contains($response->body(), 'UNREGISTERED') || str_contains($response->body(), 'INVALID_ARGUMENT')) {
                        UserDevice::where('fcm_token', $token)->update(['is_active' => false]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('[FCM] Exception: ' . $e->getMessage());
                $results[] = ['token' => substr($token, 0, 20) . '...', 'success' => false, 'error' => $e->getMessage()];
            }
        }

        return [
            'success' => collect($results)->where('success', true)->count() > 0,
            'results' => $results,
        ];
    }

    /**
     * Get OAuth2 access token for FCM v1 API
     */
    protected function getAccessToken(): ?string
    {
        try {
            $credentialsPath = config('firebase.credentials.file');

            if (!file_exists($credentialsPath)) {
                Log::error('[FCM] Service account file not found: ' . $credentialsPath);
                return null;
            }

            $credentials = json_decode(file_get_contents($credentialsPath), true);

            // Create JWT
            $now = time();
            $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $payload = base64_encode(json_encode([
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ]));

            $privateKey = openssl_pkey_get_private($credentials['private_key']);
            openssl_sign("$header.$payload", $signature, $privateKey, OPENSSL_ALGO_SHA256);
            $jwt = "$header.$payload." . base64_encode($signature);

            // Exchange JWT for access token
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('[FCM] Token exchange failed: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('[FCM] Access token error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Notification templates
     */
    public function notifyNewOrder($order): array
    {
        return $this->sendToStaff(
            '🛒 طلب جديد #' . $order->order_number,
            "طلب جديد من {$order->customer_name} - " . number_format($order->total, 2) . ' ج.م',
            [
                'type' => 'new_order',
                'order_number' => $order->order_number,
                'url' => route('admin.orders.show', $order->id),
            ]
        );
    }

    public function notifyOrderStatusChange($order, $oldStatus, $newStatus): array
    {
        // Match actual system statuses
        $statusLabels = [
            'pending' => 'قيد الانتظار',
            'processing' => 'قيد التحضير',
            'shipped' => 'في الطريق',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي',
        ];

        $newStatusLabel = $statusLabels[$newStatus] ?? $newStatus;

        Log::info('[FCM] Sending order status change notification', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);

        return $this->sendToUsers(
            [$order->user_id],
            '📦 تحديث طلبك #' . $order->order_number,
            "حالة طلبك أصبحت: {$newStatusLabel}",
            [
                'type' => 'order_status_change',
                'order_number' => $order->order_number,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'url' => url('/my-orders/' . $order->id),
            ]
        );
    }

    public function notifyNewContactMessage($contact): array
    {
        return $this->sendToAdmins(
            '✉️ رسالة جديدة',
            "رسالة من: {$contact->name} - {$contact->subject}",
            [
                'type' => 'new_contact',
                'contact_id' => (string) $contact->id,
                'url' => route('admin.contacts.show', $contact->id),
            ]
        );
    }

    public function notifyOrderCancelled($order): array
    {
        return $this->sendToStaff(
            '❌ طلب ملغي #' . $order->order_number,
            "تم إلغاء الطلب من {$order->customer_name}",
            [
                'type' => 'order_cancelled',
                'order_number' => $order->order_number,
                'url' => route('admin.orders.show', $order->id),
            ]
        );
    }

    public function notifyNewUser($user): array
    {
        return $this->sendToAdmins(
            '👤 عميل جديد',
            "تسجيل جديد: {$user->name}",
            [
                'type' => 'new_user',
                'user_id' => (string) $user->id,
                'url' => route('admin.users.show', $user->id),
            ]
        );
    }
}
