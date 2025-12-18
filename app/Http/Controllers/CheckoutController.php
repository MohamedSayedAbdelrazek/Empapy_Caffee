<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'سلة التسوق فارغة');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->current_price * $item['quantity']
                ];
                $subtotal += $product->current_price * $item['quantity'];
            }
        }

        $shipping = $subtotal >= 500 ? 0 : 50; // Free shipping over 500 EGP
        $total = $subtotal + $shipping;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    /**
     * Process checkout
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'governorate' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash_on_delivery',
            'coupon_code' => 'nullable|string|max:50'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'سلة التسوق فارغة');
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $orderItems = [];

            foreach ($cart as $id => $item) {
                $product = Product::find($id);
                if ($product) {
                    $itemTotal = $product->current_price * $item['quantity'];
                    $subtotal += $itemTotal;

                    $orderItems[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_name_ar' => $product->name_ar,
                        'price' => $product->current_price,
                        'quantity' => $item['quantity'],
                        'total' => $itemTotal
                    ];

                    // Reduce stock
                    $product->decrement('stock', $item['quantity']);
                }
            }

            $shipping = $subtotal >= 500 ? 0 : 50;
            $discount = 0;
            $couponCode = null;

            // Apply coupon if provided
            if ($request->filled('coupon_code')) {
                $coupon = \App\Models\Coupon::where('code', strtoupper($request->coupon_code))->first();

                if ($coupon && $coupon->isValid()) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $couponCode = $coupon->code;

                    // Increment usage count
                    $coupon->incrementUsage();
                }
            }

            $total = $subtotal + $shipping - $discount;

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'discount' => $discount,
                'coupon_code' => $couponCode,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'cash_on_delivery',
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'city' => $request->city,
                'governorate' => $request->governorate,
                'notes' => $request->notes
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // Clear cart
            session()->forget('cart');

            // Fire OrderCreated event
            event(new \App\Events\OrderCreated($order));

            DB::commit();

            return redirect()->route('checkout.success', $order)
                ->with('success', 'تم إنشاء طلبك بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء معالجة الطلب. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Display order success page
     */
    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }
}
