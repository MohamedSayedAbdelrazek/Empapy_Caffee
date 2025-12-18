<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        // Log the order creation
        Log::info('New order created', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'total' => $order->total,
        ]);

        // You can add email notification here
        // Mail::to($order->customer_email)->send(new OrderConfirmation($order));

        // You can add admin notification here
        // Notification::send($admins, new NewOrderNotification($order));
    }

    /**
     * Handle a job failure.
     */
    public function failed(OrderCreated $event, \Throwable $exception): void
    {
        Log::error('Failed to send order notification', [
            'order_id' => $event->order->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
