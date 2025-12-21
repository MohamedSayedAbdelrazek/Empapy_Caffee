<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\Product;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Create notification for new order
        AdminNotification::createOrderNotification($order);

        // Check for low stock on ordered products
        $order->load('items.product');

        foreach ($order->items as $item) {
            if ($item->product && $item->product->stock <= 5) {
                // Check if we already sent a low stock notification for this product today
                $existingNotification = AdminNotification::where('type', 'low_stock')
                    ->where('data->product_id', $item->product->id)
                    ->where('created_at', '>=', now()->startOfDay())
                    ->exists();

                if (!$existingNotification) {
                    AdminNotification::createLowStockNotification($item->product);
                }
            }
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // You can add notifications for status changes here if needed
    }
}
