<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /**
     * Cancel order by customer
     * 
     * Security measures:
     * - Validates order ownership (customer can only cancel their own orders)
     * - Checks order status before allowing cancellation
     * - Uses database transaction for data integrity
     * - Logs all cancellation attempts for audit trail
     * - Prevents race conditions with locking
     * 
     * HTTP Status Codes:
     * - 200: Success
     * - 403: Unauthorized (not owner)
     * - 409: Conflict (already cancelled or status not cancellable)
     */
    public function cancel(Order $order)
    {
        // Security: Validate ownership - customer can only cancel their own orders
        if (!$order->belongsToUser(auth()->id())) {
            Log::warning('[Order Cancellation] Unauthorized attempt', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'attempted_by' => auth()->id(),
                'actual_owner' => $order->user_id,
                'ip' => request()->ip(),
            ]);
            abort(403, 'غير مصرح لك بإلغاء هذا الطلب');
        }

        // Check if already cancelled (409 Conflict)
        if ($order->isCancelled()) {
            return redirect()->route('orders.my-orders')->with('error', 'هذا الطلب ملغي بالفعل');
        }

        // Check if cancellation is allowed based on status (409 Conflict)
        if (!$order->canBeCancelled()) {
            Log::info('[Order Cancellation] Rejected - status not cancellable', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'current_status' => $order->status,
                'user_id' => auth()->id(),
            ]);
            return redirect()->route('orders.my-orders')->with('error', 'لا يمكن إلغاء الطلب بعد بدء التحضير. يرجى التواصل مع خدمة العملاء.');
        }

        try {
            // Use database transaction for data integrity and prevent race conditions
            DB::transaction(function () use ($order) {
                // Lock the order row to prevent concurrent modifications
                $order = Order::lockForUpdate()->find($order->id);

                // Double-check status inside transaction (race condition prevention)
                if (!$order->canBeCancelled()) {
                    throw new \Exception('Status changed during cancellation');
                }

                // Refund coupon usage if applicable (regular coupon, not reward)
                if ($order->coupon_code && !str_starts_with($order->coupon_code, 'RWD-')) {
                    $coupon = \App\Models\Coupon::where('code', $order->coupon_code)->first();
                    if ($coupon && $coupon->usage_count > 0) {
                        $coupon->decrement('usage_count');
                        Log::info('[Order Cancellation] Coupon usage refunded', [
                            'order_id' => $order->id,
                            'coupon_code' => $order->coupon_code,
                        ]);
                    }
                }

                // Restore reward redemption points if used
                if ($order->coupon_code && str_starts_with($order->coupon_code, 'RWD-')) {
                    $redemption = \App\Models\RewardRedemption::where('order_id', $order->id)
                        ->where('status', 'applied')
                        ->first();

                    if ($redemption) {
                        $redemption->cancel(); // This restores points to user
                        Log::info('[Order Cancellation] Reward points restored', [
                            'order_id' => $order->id,
                            'redemption_id' => $redemption->id,
                        ]);
                    }
                }

                // Update order status to cancelled
                $order->update(['status' => Order::STATUS_CANCELLED]);
            });

            // Log successful cancellation
            Log::info('[Order Cancellation] Success', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);

            // Redirect to my-orders page (not back() to avoid issues from checkout success page)
            return redirect()->route('orders.my-orders')->with('success', 'تم إلغاء طلبك بنجاح');

        } catch (\Exception $e) {
            Log::error('[Order Cancellation] Failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('orders.my-orders')->with('error', 'حدث خطأ أثناء إلغاء الطلب. يرجى المحاولة مرة أخرى.');
        }
    }
}
