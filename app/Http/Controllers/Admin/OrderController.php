<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        // If delivered, mark as paid
        if ($request->status === 'delivered' && $order->payment_method === 'cash_on_delivery') {
            $order->update(['payment_status' => 'paid']);
        }

        return back()->with('success', 'تم تحديث حالة الطلب');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $order->update([
            'payment_status' => $request->payment_status
        ]);

        return back()->with('success', 'تم تحديث حالة الدفع');
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        if ($order->status === 'cancelled') {
            return back()->with('error', 'هذا الطلب ملغي بالفعل');
        }

        $order->update([
            'status' => 'cancelled'
        ]);

        return back()->with('success', 'تم إلغاء الطلب');
    }

    /**
     * Display Kanban Board view
     */
    public function kanban()
    {
        // Get orders grouped by status
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        $ordersByStatus = [];
        foreach ($statuses as $status) {
            $ordersByStatus[$status] = Order::with('user')
                ->where('status', $status)
                ->latest()
                ->take(50)
                ->get();
        }

        // Status labels and colors
        $statusConfig = [
            'pending' => [
                'label' => 'طلبات جديدة',
                'icon' => 'bi-hourglass-split',
                'color' => 'warning',
                'gradient' => 'linear-gradient(135deg, #f59e0b, #d97706)',
            ],
            'processing' => [
                'label' => 'قيد التحضير',
                'icon' => 'bi-gear-fill',
                'color' => 'info',
                'gradient' => 'linear-gradient(135deg, #3b82f6, #2563eb)',
            ],
            'shipped' => [
                'label' => 'في الطريق',
                'icon' => 'bi-truck',
                'color' => 'purple',
                'gradient' => 'linear-gradient(135deg, #8b5cf6, #7c3aed)',
            ],
            'delivered' => [
                'label' => 'تم التوصيل',
                'icon' => 'bi-check-circle-fill',
                'color' => 'success',
                'gradient' => 'linear-gradient(135deg, #10b981, #059669)',
            ],
            'cancelled' => [
                'label' => 'ملغي',
                'icon' => 'bi-x-circle-fill',
                'color' => 'danger',
                'gradient' => 'linear-gradient(135deg, #ef4444, #dc2626)',
            ],
        ];

        return view('admin.orders.kanban', compact('ordersByStatus', 'statusConfig'));
    }

    /**
     * Update order status via AJAX (for Kanban drag & drop)
     */
    public function updateStatusAjax(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus
        ]);

        // If delivered, mark as paid (for cash on delivery)
        if ($newStatus === 'delivered' && $order->payment_method === 'cash_on_delivery') {
            $order->update(['payment_status' => 'paid']);
        }

        // If moved FROM delivered to another status, revert payment status (for cash on delivery)
        if ($oldStatus === 'delivered' && $newStatus !== 'delivered' && $order->payment_method === 'cash_on_delivery') {
            $order->update(['payment_status' => 'pending']);
        }

        // Status labels for response
        $statusLabels = [
            'pending' => 'طلب جديد',
            'processing' => 'قيد التحضير',
            'shipped' => 'في الطريق',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغي',
        ];

        return response()->json([
            'success' => true,
            'message' => "تم نقل الطلب #{$order->order_number} إلى {$statusLabels[$newStatus]}",
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);
    }

    /**
     * Get order details via AJAX (for Kanban quick-view modal)
     */
    public function getOrderDetails(Order $order)
    {
        $order->load('items.product', 'items.selectedOptions');

        $statusLabels = [
            'pending' => 'طلب جديد',
            'processing' => 'قيد التحضير',
            'shipped' => 'في الطريق',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغي',
        ];

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'status_ar' => $statusLabels[$order->status] ?? $order->status,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'customer_email' => $order->customer_email,
                'shipping_address' => $order->shipping_address,
                'city' => $order->city,
                'governorate' => $order->governorate,
                'notes' => $order->notes,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method === 'cash_on_delivery' ? 'الدفع عند الاستلام' : $order->payment_method,
                'subtotal' => number_format($order->subtotal),
                'shipping' => $order->shipping == 0 ? 'مجاني' : number_format($order->shipping) . ' ج.م',
                'discount' => $order->discount > 0 ? number_format($order->discount) : null,
                'coupon_code' => $order->coupon_code,
                'total' => number_format($order->total),
                'created_at' => $order->created_at->format('Y/m/d H:i'),
                'created_at_human' => $order->created_at->diffForHumans(),
                'items' => $order->items->map(fn($item) => [
                    'name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'price' => number_format($item->price),
                    'total' => number_format($item->total),
                    'image' => $item->product?->image,
                    'options' => $item->selectedOptions->map(fn($opt) => [
                        'label' => $opt->option_name ?? $opt->option_type,
                        'value' => $opt->value_name ?? '',
                        'price' => $opt->price_modifier > 0 ? '+' . number_format($opt->price_modifier) : null,
                    ]),
                    'options_text' => $item->options_display_text,
                ])
            ]
        ]);
    }
}
