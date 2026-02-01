<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\Product;
use App\Services\LoyaltyService;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    protected LoyaltyService $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Create notification for new order
        AdminNotification::createOrderNotification($order);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Check if status changed to 'delivered'
        if ($order->isDirty('status') && $order->status === 'delivered') {
            // Award loyalty points for completed order
            $transaction = $this->loyaltyService->processOrderPoints($order);

            // Send push notification about earned points
            if ($transaction && $order->user_id) {
                $this->sendPointsEarnedNotification($order, $transaction->points);
            }
        }
    }

    /**
     * Send notification to user about earned points
     */
    protected function sendPointsEarnedNotification(Order $order, int $points): void
    {
        try {
            $firebaseService = app(FirebaseNotificationService::class);
            $firebaseService->sendToUsers(
                [$order->user_id],
                '🎉 حصلت على نقاط!',
                "مبروك! حصلت على {$points} نقطة من طلبك #{$order->order_number}",
                [
                    'type' => 'points_earned',
                    'points' => (string) $points,
                    'order_number' => $order->order_number,
                    'click_action' => '/loyalty',
                ]
            );

            Log::info("Points notification sent to user {$order->user_id}: {$points} points");
        } catch (\Exception $e) {
            Log::warning('Failed to send points earned notification: ' . $e->getMessage());
        }
    }
}
