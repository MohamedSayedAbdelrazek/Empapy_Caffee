<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\Product;
use App\Services\LoyaltyService;

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
            $this->loyaltyService->processOrderPoints($order);
        }
    }
}
