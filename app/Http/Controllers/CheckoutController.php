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

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $options = $item['options'] ?? [];
                $optionValueIds = array_values($options);
                $unitPrice = $product->calculatePriceWithOptions($optionValueIds);

                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $unitPrice * $item['quantity'],
                    'options' => $options
                ];
                $subtotal += $unitPrice * $item['quantity'];
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

            foreach ($cart as $key => $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $options = $item['options'] ?? [];
                    // Calculate price with options
                    $optionValueIds = array_values($options);
                    $unitPrice = $product->calculatePriceWithOptions($optionValueIds);

                    $itemTotal = $unitPrice * $item['quantity'];
                    $subtotal += $itemTotal;

                    $orderItems[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $unitPrice,
                        'quantity' => $item['quantity'],
                        'total' => $itemTotal,
                        'options' => $options // temporarily hold options to save later
                    ];
                }
            }

            $shipping = $subtotal >= 500 ? 0 : 50;
            $discount = 0;
            $couponCode = null;
            $redemption = null;

            // Apply coupon or redemption code if provided
            if ($request->filled('coupon_code')) {
                $code = strtoupper(trim($request->coupon_code));

                // Check if it's a redemption code (starts with RWD-)
                if (str_starts_with($code, 'RWD-')) {
                    $redemption = \App\Models\RewardRedemption::with('reward')
                        ->where('redemption_code', $code)
                        ->where('status', 'pending')
                        ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                        ->where(function ($q) {
                            $q->whereNull('expires_at')
                                ->orWhere('expires_at', '>', now());
                        })
                        ->first();

                    if ($redemption && $redemption->reward) {
                        $reward = $redemption->reward;

                        // Calculate discount based on reward type
                        switch ($reward->reward_type) {
                            case 'discount_fixed':
                                $discount = min($reward->reward_value, $subtotal);
                                break;
                            case 'discount_percent':
                                $discount = ($subtotal * $reward->reward_value) / 100;
                                // Apply max discount cap if set
                                if ($reward->max_discount && $discount > $reward->max_discount) {
                                    $discount = $reward->max_discount;
                                }
                                break;
                            case 'free_shipping':
                                $shipping = 0;
                                break;
                        }

                        $couponCode = $code; // Store the redemption code
                    }
                } else {
                    // Regular coupon code
                    $coupon = \App\Models\Coupon::where('code', $code)->first();

                    if ($coupon && $coupon->isValid()) {
                        $discount = $coupon->calculateDiscount($subtotal);
                        $couponCode = $coupon->code;

                        // Increment usage count
                        $coupon->incrementUsage();
                    }
                }
            }

            $total = $subtotal + $shipping - $discount;

            // Create order
            $order = Order::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
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
                $optionsToSave = $item['options'] ?? [];
                // Remove options from item array before creating OrderItem
                unset($item['options']);

                $orderItem = $order->items()->create($item);

                // Save selected options
                if (!empty($optionsToSave)) {
                    foreach ($optionsToSave as $type => $valueId) {
                        $optionValue = \App\Models\ProductOptionValue::with('option')->find($valueId);
                        if ($optionValue) {
                            \App\Models\OrderItemOption::create([
                                'order_item_id' => $orderItem->id,
                                'product_option_value_id' => $optionValue->id,
                                'option_type' => $optionValue->option->type,
                                'option_name' => $optionValue->option->name ?? $optionValue->option->type_name,
                                'value_name' => $optionValue->value,
                                'price_modifier' => $optionValue->price_modifier
                            ]);
                        }
                    }
                }
            }

            // Clear cart
            session()->forget('cart');

            // Fire OrderCreated event
            event(new \App\Events\OrderCreated($order));

            // Mark reward redemption as applied
            if ($redemption) {
                $redemption->applyToOrder($order);
            }

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
