<?php

namespace App\Services;

use App\Events\OrderCreated;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Order;
use App\Models\OrderItemOption;
use App\Models\Product;
use App\Models\ProductOptionValue;
use App\Models\RewardRedemption;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(protected ShippingService $shippingService) {}

    /**
     * Place an order from the given cart. Encapsulates pricing, shipping,
     * coupon/redemption application (including the SEC-04 atomic usage limits),
     * order + item + option creation, the new-order notification, and the
     * optional profile save — all inside a single transaction.
     *
     * The HTTP concern (the SEC-01 signed success URL) stays in the controller.
     *
     * @param  array<string, mixed>  $data  Validated checkout data (+ 'save_info')
     * @param  array<string, mixed>  $cart  The session cart
     */
    public function place(array $data, array $cart, ?User $user): Order
    {
        return DB::transaction(function () use ($data, $cart, $user) {
            $userId = $user?->id;

            // 1. Recompute totals + build order items from the cart (server-side).
            $subtotal = 0;
            $orderItems = [];

            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                if (! $product) {
                    continue;
                }

                $options = $item['options'] ?? [];
                $unitPrice = $product->calculatePriceWithOptions(array_values($options));
                $itemTotal = $unitPrice * $item['quantity'];
                $subtotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $unitPrice,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                    'options' => $options, // held temporarily to persist after the item is created
                ];
            }

            // 2. Shipping computed server-side from the governorate (single source of truth).
            $shipping = $this->shippingService->resolve($data['governorate'] ?? null, $subtotal)['shipping'];

            // 3. Apply a coupon OR a reward-redemption code, if provided.
            $discount = 0;
            $couponCode = null;
            $redemption = null;
            $coupon = null;

            if (! empty($data['coupon_code'])) {
                $code = strtoupper(trim($data['coupon_code']));

                if (str_starts_with($code, 'RWD-')) {
                    $redemption = RewardRedemption::with('reward')
                        ->where('redemption_code', $code)
                        ->where('status', 'pending')
                        ->where('user_id', $userId)
                        ->where(function ($q) {
                            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                        })
                        ->first();

                    if ($redemption && $redemption->reward) {
                        $reward = $redemption->reward;

                        switch ($reward->reward_type) {
                            case 'discount_fixed':
                                $discount = min($reward->reward_value, $subtotal);
                                break;
                            case 'discount_percent':
                                $discount = ($subtotal * $reward->reward_value) / 100;
                                if ($reward->max_discount && $discount > $reward->max_discount) {
                                    $discount = $reward->max_discount;
                                }
                                break;
                            case 'free_shipping':
                                $shipping = 0;
                                break;
                            case 'free_product':
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
                                            'options' => [],
                                        ];
                                    }
                                }
                                break;
                        }

                        $couponCode = $code; // store the redemption code
                    }
                } else {
                    // Regular coupon. Lock the row for the duration of the transaction
                    // so the usage limit can't be raced by concurrent checkouts (SEC-04).
                    $coupon = Coupon::where('code', $code)->lockForUpdate()->first();

                    if ($coupon && $coupon->isValid($userId)) {
                        $discount = $coupon->calculateDiscount($subtotal);
                        $couponCode = $coupon->code;
                        // Usage is recorded atomically after the order is created.
                    } else {
                        // Don't record usage for a coupon that wasn't applied.
                        $coupon = null;
                    }
                }
            }

            // 4. Total is never negative.
            $total = max(0, $subtotal + $shipping - $discount);

            // 5. Create the order.
            $order = Order::create([
                'user_id' => $userId,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'discount' => $discount,
                'coupon_code' => $couponCode,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $data['payment_method'],
                'currency' => 'EGP',
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => $data['shipping_address'],
                'city' => $data['city'],
                'governorate' => $data['governorate'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // 6. Create order items + their selected options.
            foreach ($orderItems as $item) {
                $optionsToSave = $item['options'] ?? [];
                unset($item['options']);

                $orderItem = $order->items()->create($item);

                foreach ($optionsToSave as $valueId) {
                    $optionValue = ProductOptionValue::with('option')->find($valueId);
                    if ($optionValue) {
                        OrderItemOption::create([
                            'order_item_id' => $orderItem->id,
                            'product_option_value_id' => $optionValue->id,
                            'option_type' => $optionValue->option->type,
                            'option_name' => $optionValue->option->name ?? $optionValue->option->type_name,
                            'value_name' => $optionValue->value,
                            'price_modifier' => $optionValue->price_modifier,
                        ]);
                    }
                }
            }

            // 7. Clear the cart.
            session()->forget('cart');

            // 8. Fire the OrderCreated event.
            event(new OrderCreated($order));

            // 9. Notify staff (non-blocking).
            try {
                (new FirebaseNotificationService)->notifyNewOrder($order);
            } catch (\Exception $e) {
                Log::error('[FCM] Failed to send new order notification: '.$e->getMessage());
            }

            // 10. Mark the reward redemption as applied.
            if ($redemption) {
                $redemption->applyToOrder($order);
            }

            // 11. Record coupon usage atomically inside the transaction (SEC-04).
            if ($coupon) {
                // Global usage limit: conditional increment; abort if it would push
                // usage past the cap (backstop to the row lock above).
                $incremented = Coupon::whereKey($coupon->id)
                    ->where(function ($q) {
                        $q->whereNull('usage_limit')->orWhereColumn('usage_count', '<', 'usage_limit');
                    })
                    ->increment('usage_count');

                if ($incremented === 0) {
                    throw new \Exception('Coupon usage limit reached');
                }

                // Per-user usage tracking / limit (only for authenticated users).
                if ($userId) {
                    $pivot = CouponUser::firstOrNew([
                        'coupon_id' => $coupon->id,
                        'user_id' => $userId,
                    ]);

                    if ($coupon->per_user_limit !== null && $pivot->usage_count >= $coupon->per_user_limit) {
                        throw new \Exception('Coupon per-user limit reached');
                    }

                    $pivot->usage_count = ($pivot->usage_count ?? 0) + 1;
                    $pivot->save();
                }
            }

            // 12. Save the customer's info for next time if requested.
            if (! empty($data['save_info']) && $user) {
                $user->update([
                    'phone' => $data['customer_phone'],
                    'address' => $data['shipping_address'],
                    'city' => $data['city'],
                    'governorate' => $data['governorate'] ?? null,
                ]);
            }

            return $order;
        });
    }
}
