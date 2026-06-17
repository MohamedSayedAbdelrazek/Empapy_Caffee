<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Setting;
use App\Models\ShippingZone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Checkout: order + items creation, server-side shipping per governorate,
 * coupon discount math, and the signed success-URL redirect.
 */
class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Guard against any outbound notification HTTP during checkout.
        Http::fake();

        $this->product = Product::factory()->create([
            'category_id' => Category::factory(),
            'price' => 100,
            'sale_price' => null,
            'is_active' => true,
        ]);
    }

    private function cartSession(int $quantity = 2): array
    {
        $key = md5($this->product->id.serialize([]));

        return ['cart' => [$key => [
            'product_id' => $this->product->id,
            'quantity' => $quantity,
            'options' => [],
        ]]];
    }

    private function checkoutPayload(array $overrides = []): array
    {
        return array_merge([
            'customer_name' => 'أحمد محمد',
            'customer_email' => 'ahmed@example.com',
            'customer_phone' => '01012345678',
            'shipping_address' => '12 شارع التحرير',
            'city' => 'القاهرة',
            'governorate' => 'القاهرة',
            'payment_method' => 'cash_on_delivery',
        ], $overrides);
    }

    #[Test]
    public function it_creates_an_order_and_its_items_with_correct_totals(): void
    {
        ShippingZone::factory()->create(['name' => 'القاهرة', 'fee' => 40]);

        $response = $this->withSession($this->cartSession(2))
            ->post(route('checkout.store'), $this->checkoutPayload());

        $response->assertSessionHas('success');

        // subtotal 2×100 = 200, shipping 40 (< free threshold 500), total 240.
        $this->assertDatabaseHas('orders', [
            'customer_email' => 'ahmed@example.com',
            'subtotal' => 200.00,
            'shipping' => 40.00,
            'discount' => 0.00,
            'total' => 240.00,
            'status' => 'pending',
            'payment_method' => 'cash_on_delivery',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->product->id,
            'price' => 100.00,
            'quantity' => 2,
            'total' => 200.00,
        ]);
    }

    #[Test]
    public function shipping_is_computed_server_side_from_the_governorate_zone(): void
    {
        ShippingZone::factory()->create(['name' => 'الإسكندرية', 'fee' => 60]);

        // Client tries to force shipping=0; the server must ignore it.
        $this->withSession($this->cartSession(2))
            ->post(route('checkout.store'), $this->checkoutPayload([
                'governorate' => 'الإسكندرية',
                'shipping' => 0,
                'total' => 1,
            ]))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'governorate' => 'الإسكندرية',
            'shipping' => 60.00,
            'total' => 260.00, // 200 + 60
        ]);
    }

    #[Test]
    public function free_shipping_applies_above_the_threshold(): void
    {
        Setting::set('shipping_free_threshold', 150, 'number');
        ShippingZone::factory()->create(['name' => 'القاهرة', 'fee' => 40]);

        // subtotal 200 ≥ 150 → shipping should be free.
        $this->withSession($this->cartSession(2))
            ->post(route('checkout.store'), $this->checkoutPayload())
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'subtotal' => 200.00,
            'shipping' => 0.00,
            'total' => 200.00,
        ]);
    }

    #[Test]
    public function a_valid_coupon_reduces_the_total(): void
    {
        ShippingZone::factory()->create(['name' => 'القاهرة', 'fee' => 40]);
        $coupon = Coupon::factory()->percentage(10)->create(['code' => 'SAVE10']);

        $this->withSession($this->cartSession(2))
            ->post(route('checkout.store'), $this->checkoutPayload(['coupon_code' => 'save10']))
            ->assertSessionHas('success');

        // subtotal 200, discount 10% = 20, shipping 40 → total 220.
        $this->assertDatabaseHas('orders', [
            'subtotal' => 200.00,
            'discount' => 20.00,
            'shipping' => 40.00,
            'total' => 220.00,
            'coupon_code' => 'SAVE10',
        ]);

        // The coupon's global usage was recorded exactly once.
        $this->assertSame(1, $coupon->fresh()->usage_count);
    }

    #[Test]
    public function checkout_redirects_to_a_signed_success_url(): void
    {
        ShippingZone::factory()->create(['name' => 'القاهرة', 'fee' => 40]);

        $response = $this->withSession($this->cartSession(1))
            ->post(route('checkout.store'), $this->checkoutPayload());

        $response->assertRedirect();
        // SEC-01: the confirmation link is a one-time signed URL, not /…/{id}.
        $this->assertStringContainsString('/checkout/success/', $response->headers->get('Location'));
        $this->assertStringContainsString('signature=', $response->headers->get('Location'));
    }

    #[Test]
    public function checkout_clears_the_cart_session(): void
    {
        ShippingZone::factory()->create(['name' => 'القاهرة', 'fee' => 40]);

        $this->withSession($this->cartSession(1))
            ->post(route('checkout.store'), $this->checkoutPayload())
            ->assertSessionMissing('cart');
    }

    #[Test]
    public function checkout_with_an_empty_cart_is_rejected(): void
    {
        $this->withSession(['cart' => []])
            ->post(route('checkout.store'), $this->checkoutPayload())
            ->assertSessionHas('error');

        $this->assertDatabaseCount('orders', 0);
    }

    #[Test]
    public function checkout_validates_required_customer_fields(): void
    {
        $this->withSession($this->cartSession(1))
            ->post(route('checkout.store'), ['payment_method' => 'cash_on_delivery'])
            ->assertSessionHasErrors(['customer_name', 'customer_email', 'customer_phone', 'shipping_address', 'city']);

        $this->assertDatabaseCount('orders', 0);
    }

    #[Test]
    public function an_authenticated_order_is_linked_to_the_user(): void
    {
        ShippingZone::factory()->create(['name' => 'القاهرة', 'fee' => 40]);
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->withSession($this->cartSession(1))
            ->post(route('checkout.store'), $this->checkoutPayload())
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'customer_email' => 'ahmed@example.com',
        ]);
    }
}
