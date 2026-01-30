<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    /**
     * Display order tracking page
     * Auto-search if order_number is provided in query string
     */
    public function track(Request $request)
    {
        $order = null;

        // Auto-search if order_number is in URL
        if ($request->has('order_number')) {
            $order = Order::where('order_number', $request->order_number)
                ->with('items.product')
                ->first();
        }

        return view('orders.track', compact('order'));
    }

    /**
     * Search for order by order number
     */
    public function search(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string'
        ]);

        $order = Order::where('order_number', $request->order_number)
            ->with('items.product')
            ->first();

        if (!$order) {
            return back()->with('error', 'لم يتم العثور على طلب بهذا الرقم');
        }

        return view('orders.track', compact('order'));
    }

    /**
     * Display order history for authenticated user
     */
    public function myOrders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.my-orders', compact('orders'));
    }

    /**
     * Display order details for authenticated user
     */
    public function show(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        // Load reward redemption if coupon code starts with RWD-
        $rewardRedemption = null;
        if ($order->coupon_code && str_starts_with($order->coupon_code, 'RWD-')) {
            $rewardRedemption = \App\Models\RewardRedemption::with('reward')
                ->where('redemption_code', $order->coupon_code)
                ->first();
        }

        return view('orders.show', compact('order', 'rewardRedemption'));
    }
}
