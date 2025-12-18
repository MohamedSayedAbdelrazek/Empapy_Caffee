<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    /**
     * Display order tracking page
     */
    public function track(Request $request)
    {
        return view('orders.track');
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

        return view('orders.show', compact('order'));
    }
}
