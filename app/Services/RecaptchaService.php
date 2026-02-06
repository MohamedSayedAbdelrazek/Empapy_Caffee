<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    /**
     * Verify reCAPTCHA token
     *
     * @param string $token The reCAPTCHA token from the client
     * @param string $action The expected action name (optional)
     * @return array ['success' => bool, 'score' => float, 'error' => string|null]
     */
    public function verify(string $token, string $action = 'contact'): array
    {
        // Skip verification if disabled
        if (!config('recaptcha.enabled')) {
            return ['success' => true, 'score' => 1.0, 'error' => null];
        }

        // Check if secret key is configured
        $secretKey = config('recaptcha.secret_key');
        if (empty($secretKey)) {
            Log::warning('[reCAPTCHA] Secret key not configured');
            return ['success' => true, 'score' => 1.0, 'error' => null]; // Pass if not configured
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            // Log for debugging
            Log::debug('[reCAPTCHA] Verification result', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? 0,
                'action' => $result['action'] ?? null,
                'hostname' => $result['hostname'] ?? null,
            ]);

            if (!($result['success'] ?? false)) {
                return [
                    'success' => false,
                    'score' => 0,
                    'error' => 'فشل التحقق من reCAPTCHA. يرجى المحاولة مرة أخرى.',
                ];
            }

            // Check action matches (if provided)
            if ($action && isset($result['action']) && $result['action'] !== $action) {
                return [
                    'success' => false,
                    'score' => $result['score'] ?? 0,
                    'error' => 'إجراء غير صالح',
                ];
            }

            // Check score
            $minScore = config('recaptcha.min_score', 0.5);
            if (($result['score'] ?? 0) < $minScore) {
                Log::warning('[reCAPTCHA] Low score detected', [
                    'score' => $result['score'] ?? 0,
                    'min_score' => $minScore,
                    'ip' => request()->ip(),
                ]);

                return [
                    'success' => false,
                    'score' => $result['score'] ?? 0,
                    'error' => 'تم اكتشاف نشاط مشبوه. يرجى المحاولة مرة أخرى.',
                ];
            }

            return [
                'success' => true,
                'score' => $result['score'] ?? 1.0,
                'error' => null,
            ];

        } catch (\Exception $e) {
            Log::error('[reCAPTCHA] Verification error: ' . $e->getMessage());
            
            // Don't block users on network errors
            return ['success' => true, 'score' => 1.0, 'error' => null];
        }
    }
}
