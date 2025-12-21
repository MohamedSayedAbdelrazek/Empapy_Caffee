<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\Review;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        // Load the user and product relationships
        $review->load('user', 'product');

        if ($review->user && $review->product) {
            AdminNotification::createReviewNotification($review);
        }
    }
}
