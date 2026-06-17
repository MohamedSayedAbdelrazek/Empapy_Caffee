<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class CheckoutController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    /**
     * Display checkout page
     */
    public function index()
    {
        $cartData = $this->cartService->getCartWithProducts();

        if ($cartData['count'] === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'سلة التسوق فارغة');
        }

        $cartItems = $cartData['items'];
        $subtotal = $cartData['total'];

        // Create view data
        $shippingZones = \App\Models\ShippingZone::active()->ordered()->get();
        // Initial shipping calc (will be updated by JS based on selection)
        $freeShippingThreshold = \App\Models\Setting::get('shipping_free_threshold', 500);

        // Try to get shipping fee from user's saved governorate
        $shippingFee = 0;
        $userGov = auth()->user()?->governorate;
        if ($userGov) {
            $zone = $shippingZones->firstWhere('name', $userGov);
            if ($zone) {
                $shippingFee = $zone->fee;
            }
        }

        $shipping = $subtotal >= $freeShippingThreshold ? 0 : $shippingFee;
        $total = $subtotal + $shipping;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shippingZones', 'freeShippingThreshold', 'shipping', 'total'));
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
            'payment_method' => 'required|in:cash_on_delivery', // Online payment coming soon
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

            $freeShippingThreshold = \App\Models\Setting::get('shipping_free_threshold', 500);

            // Calculate shipping based on zone (governorate)
            $shippingFee = 0;
            if ($request->governorate) {
                $zone = \App\Models\ShippingZone::where('name', $request->governorate)->first();
                if ($zone) {
                    $shippingFee = $zone->fee;
                } else {
                    // Fallback to default if zone not found active
                    $shippingFee = \App\Models\Setting::get('shipping_fee', 0);
                }
            } else {
                $shippingFee = \App\Models\Setting::get('shipping_fee', 0);
            }

            // Apply free shipping rule logic
            $shipping = $subtotal >= $freeShippingThreshold ? 0 : $shippingFee;
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
                            case 'free_product':
                                // Add free product to order items
                                if ($reward->product_id) {
                                    $freeProduct = Product::find($reward->product_id);
                                    if ($freeProduct) {
                                        $orderItems[] = [
                                            'product_id' => $freeProduct->id,
                                            'product_name' => $freeProduct->name,
                                            'price' => 0,
                                            'quantity' => 1,
                                            'total' => 0,
                                            'is_reward_item' => true,
                                            'reward_note' => 'منتج مجاني - مكافأة الولاء',
                                            'options' => []
                                        ];
                                    }
                                }
                                break;
                        }

                        $couponCode = $code; // Store the redemption code
                    }
                } else {
                    // Regular coupon code. Lock the row for the duration of the
                    // transaction so the usage limit can't be raced by concurrent
                    // checkouts (validation + increment stay consistent).
                    $coupon = \App\Models\Coupon::where('code', $code)->lockForUpdate()->first();
                    $couponUserId = \Illuminate\Support\Facades\Auth::id();

                    if ($coupon && $coupon->isValid($couponUserId)) {
                        $discount = $coupon->calculateDiscount($subtotal);
                        $couponCode = $coupon->code;
                        // Usage is recorded atomically after the order is created.
                    } else {
                        // Don't record usage for a coupon that wasn't applied.
                        $coupon = null;
                    }
                }
            }

            // Ensure total is never negative
            $total = max(0, $subtotal + $shipping - $discount);

            // Create order
            $order = Order::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'discount' => $discount,
                'coupon_code' => $couponCode,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => $request->payment_method === 'card' ? 'pending' : 'pending',
                'payment_method' => $request->payment_method,
                'currency' => 'EGP',
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

            // Send push notification to staff (async, non-blocking)
            try {
                $firebaseService = new FirebaseNotificationService();
                $firebaseService->notifyNewOrder($order);
            } catch (\Exception $e) {
                \Log::error('[FCM] Failed to send new order notification: ' . $e->getMessage());
            }

            // Mark reward redemption as applied
            if ($redemption) {
                $redemption->applyToOrder($order);
            }

            // Record coupon usage AFTER the order is created (inside the transaction).
            if (isset($coupon) && $coupon) {
                // Global usage limit: conditional increment; abort if it would
                // push usage past the cap (backstop to the row lock above).
                $incremented = \App\Models\Coupon::whereKey($coupon->id)
                    ->where(function ($q) {
                        $q->whereNull('usage_limit')
                            ->orWhereColumn('usage_count', '<', 'usage_limit');
                    })
                    ->increment('usage_count');

                if ($incremented === 0) {
                    throw new \Exception('Coupon usage limit reached');
                }

                // Per-user usage tracking / limit (only for authenticated users).
                $couponUserId = \Illuminate\Support\Facades\Auth::id();
                if ($couponUserId) {
                    $pivot = \App\Models\CouponUser::firstOrNew([
                        'coupon_id' => $coupon->id,
                        'user_id' => $couponUserId,
                    ]);

                    if ($coupon->per_user_limit !== null && $pivot->usage_count >= $coupon->per_user_limit) {
                        throw new \Exception('Coupon per-user limit reached');
                    }

                    $pivot->usage_count = ($pivot->usage_count ?? 0) + 1;
                    $pivot->save();
                }
            }

            // Save user info for next time if checkbox was checked
            if ($request->boolean('save_info') && auth()->check()) {
                $user = auth()->user();
                $user->update([
                    'phone' => $request->customer_phone,
                    'address' => $request->shipping_address,
                    'city' => $request->city,
                    'governorate' => $request->governorate,
                ]);
            }

            DB::commit();

            // Redirect to a one-time signed URL so guests (no user_id) can view
            // their own confirmation without the page being publicly enumerable.
            $successUrl = URL::temporarySignedRoute(
                'checkout.success',
                now()->addHours(24),
                ['order' => $order->order_number]
            );

            return redirect($successUrl)
                ->with('success', 'تم إنشاء طلبك بنجاح!')
                ->with('celebrate', true);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء معالجة الطلب. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Display order success page
     */
    /**
     * Display order success page
     */
    public function success(Request $request, Order $order)
    {
        // Only the order's owner, or someone holding a valid signed link
        // (the link issued at checkout), may view the confirmation page.
        $isOwner = auth()->check() && $order->user_id === auth()->id();

        if (!$request->hasValidSignature() && !$isOwner) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    /**
     * Calculate shipping fee via AJAX
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'governorate' => 'required|string',
            'subtotal' => 'nullable|numeric'
        ]);

        $zone = \App\Models\ShippingZone::where('name', $request->governorate)->active()->first();
        $fee = $zone ? $zone->fee : \App\Models\Setting::get('shipping_fee', 0);
        $freeThreshold = \App\Models\Setting::get('shipping_free_threshold', 500);

        // Required subtotal from cart session if not passed
        $subtotal = $request->subtotal;
        if (!$subtotal) {
            $subtotal = $this->cartService->getCartTotal();
        }

        $shipping = $subtotal >= $freeThreshold ? 0 : $fee;
        $total = $subtotal + $shipping;

        return response()->json([
            'success' => true,
            'fee' => $fee,
            'shipping' => $shipping,
            'total' => $total,
            'is_free' => $shipping == 0,
            'message' => $shipping == 0 ? 'شحن مجاني' : number_format($shipping) . ' ج.م'
        ]);
    }
}
