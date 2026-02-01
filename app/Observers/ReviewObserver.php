<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\Review;
use App\Services\LoyaltyService;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\Log;

class ReviewObserver
{
    protected LoyaltyService $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        // Load the user and product relationships
        $review->load('user', 'product');

        if ($review->user && $review->product) {
            // Admin notification
            AdminNotification::createReviewNotification($review);

            // Award loyalty points for review
            $transaction = $this->loyaltyService->processReviewPoints($review->user, $review);

            // Notify user about earned points
            if ($transaction) {
                $this->sendReviewPointsNotification($review->user, $transaction->points);
            }
        }
    }

    /**
     * Send notification to user about review points
     */
    protected function sendReviewPointsNotification($user, int $points): void
    {
        try {
            $firebaseService = app(FirebaseNotificationService::class);
            $firebaseService->sendToUsers(
                [$user->id],
                '⭐ شكراً على تقييمك!',
                "حصلت على {$points} نقطة لكتابة تقييم",
                [
                    'type' => 'review_points',
                    'points' => (string) $points,
                    'click_action' => '/loyalty',
                ]
            );

            Log::info("Review points notification sent to user {$user->id}: {$points} points");
        } catch (\Exception $e) {
            Log::warning('Failed to send review points notification: ' . $e->getMessage());
        }
    }
}
