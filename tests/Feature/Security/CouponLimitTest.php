<?php

namespace Tests\Feature\Security;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Product;
use App\Models\ShippingZone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * SEC-04 regression guard: a coupon's global usage_limit and per_user_limit
 * must be enforced — usage can never exceed the cap and a one-per-customer
 * coupon cannot be reused by the same customer.
 *
 * Exercised end-to-end through checkout (where the atomic increment lives).
 */
class CouponLimitTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake();
        // Pin sale_price to null: the factory otherwise sets a random sale_price
        // (derived from its own random base price) ~30% of the time, which would
        // override the price=100 here and make the subtotal non-deterministic.
        $this->product = Product::factory()->create([
            'category_id' => Category::factory(),
            'price' => 100,
            'sale_price' => null,
            'is_active' => true,
        ]);
        ShippingZone::factory()->create(['name' => 'القاهرة', 'fee' => 40]);
    }

    private function placeOrderWithCoupon(string $code, ?User $actAs = null): \Illuminate\Testing\TestResponse
    {
        $key = md5($this->product->id.serialize([]));
        $session = ['cart' => [$key => [
            'product_id' => $this->product->id,
            'quantity' => 1,
            'options' => [],
        ]]];

        $payload = [
            'customer_name' => 'عميل',
            'customer_email' => 'c@example.com',
            'customer_phone' => '01000000000',
            'shipping_address' => 'عنوان',
            'city' => 'القاهرة',
            'governorate' => 'القاهرة',
            'payment_method' => 'cash_on_delivery',
            'coupon_code' => $code,
        ];

        $request = $actAs ? $this->actingAs($actAs) : $this;

        return $request->withSession($session)->post(route('checkout.store'), $payload);
    }

    #[Test]
    public function global_usage_cannot_exceed_the_usage_limit(): void
    {
        $coupon = Coupon::factory()->percentage(10)->create([
            'code' => 'LIMIT1',
            'usage_limit' => 1,
            'usage_count' => 0,
        ]);

        $this->placeOrderWithCoupon('LIMIT1')->assertSessionHas('success');
        $this->assertSame(1, $coupon->fresh()->usage_count);

        // Second order: coupon is now exhausted → no discount, count stays at 1.
        $this->placeOrderWithCoupon('LIMIT1')->assertSessionHas('success');

        $this->assertSame(1, $coupon->fresh()->usage_count, 'usage must never exceed the limit');

        $secondOrder = \App\Models\Order::latest('id')->first();
        $this->assertEquals(0, $secondOrder->discount, 'an exhausted coupon grants no discount');
        $this->assertNull($secondOrder->coupon_code);
    }

    #[Test]
    public function the_per_user_limit_blocks_a_repeat_use_by_the_same_customer(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $coupon = Coupon::factory()->percentage(10)->create([
            'code' => 'ONCEPER',
            'per_user_limit' => 1,
        ]);

        // First use by the customer — recorded.
        $this->placeOrderWithCoupon('ONCEPER', $user)->assertSessionHas('success');
        $this->assertSame(1, CouponUser::where('coupon_id', $coupon->id)->where('user_id', $user->id)->value('usage_count'));

        // Second use by the SAME customer — blocked, no extra discount/usage.
        $this->placeOrderWithCoupon('ONCEPER', $user)->assertSessionHas('success');

        $this->assertSame(
            1,
            CouponUser::where('coupon_id', $coupon->id)->where('user_id', $user->id)->value('usage_count'),
            'per-user usage must not exceed the per-user limit'
        );

        $secondOrder = \App\Models\Order::where('user_id', $user->id)->latest('id')->first();
        $this->assertEquals(0, $secondOrder->discount);
    }

    #[Test]
    public function a_different_customer_can_still_use_a_per_user_limited_coupon(): void
    {
        $first = User::factory()->create(['role' => 'customer']);
        $second = User::factory()->create(['role' => 'customer']);
        Coupon::factory()->percentage(10)->create(['code' => 'SHARED', 'per_user_limit' => 1]);

        $this->placeOrderWithCoupon('SHARED', $first)->assertSessionHas('success');
        $this->placeOrderWithCoupon('SHARED', $second)->assertSessionHas('success');

        // 10% of a 100 subtotal (1 × 100) = 10.
        $secondOrder = \App\Models\Order::where('user_id', $second->id)->latest('id')->first();
        $this->assertEquals(10, $secondOrder->discount, 'a different customer still gets the discount');
    }
}
