<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cart behaviour through the AJAX endpoints and the CartService.
 * Matches the current schema (products no longer have a `stock` column).
 */
class CartTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create([
            'category_id' => Category::factory(),
            'price' => 100,
            'sale_price' => null,
            'is_active' => true,
        ]);
    }

    private function cartKeyFor(int $productId, array $options = []): string
    {
        ksort($options);

        return md5($productId.serialize($options));
    }

    // ---- HTTP endpoints --------------------------------------------------

    #[Test]
    public function a_product_can_be_added_to_the_cart(): void
    {
        $response = $this->postJson(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertOk()->assertJson([
            'success' => true,
            'message' => 'تمت الإضافة إلى السلة',
            'cartCount' => 2,
        ]);
    }

    #[Test]
    public function adding_the_same_product_again_increases_the_quantity(): void
    {
        $this->postJson(route('cart.add'), ['product_id' => $this->product->id, 'quantity' => 1]);
        $response = $this->postJson(route('cart.add'), ['product_id' => $this->product->id, 'quantity' => 2]);

        $response->assertOk()->assertJson(['cartCount' => 3]);
    }

    #[Test]
    public function an_inactive_product_cannot_be_added(): void
    {
        $inactive = Product::factory()->create([
            'category_id' => $this->product->category_id,
            'is_active' => false,
        ]);

        $response = $this->postJson(route('cart.add'), [
            'product_id' => $inactive->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(400)->assertJson([
            'success' => false,
            'message' => 'هذا المنتج غير متوفر حالياً',
        ]);
    }

    #[Test]
    public function adding_requires_a_valid_existing_product(): void
    {
        $this->postJson(route('cart.add'), ['product_id' => 999999, 'quantity' => 1])
            ->assertStatus(422);
    }

    #[Test]
    public function cart_quantity_can_be_updated(): void
    {
        $this->postJson(route('cart.add'), ['product_id' => $this->product->id, 'quantity' => 1]);
        $key = $this->cartKeyFor($this->product->id);

        $response = $this->postJson(route('cart.update'), ['key' => $key, 'quantity' => 4]);

        $response->assertOk()->assertJson([
            'success' => true,
            'cart' => ['count' => 4, 'total' => 400],
        ]);
    }

    #[Test]
    public function updating_quantity_to_zero_removes_the_item(): void
    {
        $this->postJson(route('cart.add'), ['product_id' => $this->product->id, 'quantity' => 2]);
        $key = $this->cartKeyFor($this->product->id);

        $this->postJson(route('cart.update'), ['key' => $key, 'quantity' => 0])
            ->assertOk()
            ->assertJson(['cart' => ['count' => 0]]);
    }

    #[Test]
    public function an_item_can_be_removed_from_the_cart(): void
    {
        $this->postJson(route('cart.add'), ['product_id' => $this->product->id, 'quantity' => 1]);
        $key = $this->cartKeyFor($this->product->id);

        $this->postJson(route('cart.remove'), ['key' => $key])
            ->assertOk()
            ->assertJson(['success' => true, 'message' => 'تم الحذف من السلة', 'cartCount' => 0]);
    }

    #[Test]
    public function the_cart_can_be_cleared(): void
    {
        $this->postJson(route('cart.add'), ['product_id' => $this->product->id, 'quantity' => 2]);

        $this->postJson(route('cart.clear'))
            ->assertOk()
            ->assertJson(['success' => true, 'message' => 'تم تفريغ السلة', 'cartCount' => 0, 'cartTotal' => 0]);
    }

    #[Test]
    public function cart_data_endpoint_returns_the_expected_shape(): void
    {
        $this->postJson(route('cart.add'), ['product_id' => $this->product->id, 'quantity' => 2]);

        $this->getJson(route('cart.data'))
            ->assertOk()
            ->assertJsonStructure(['cartCount', 'cartTotal', 'items', 'freeShippingThreshold'])
            ->assertJson(['cartCount' => 2, 'cartTotal' => 200]);
    }

    #[Test]
    public function the_cart_page_loads(): void
    {
        $this->get(route('cart.index'))->assertOk()->assertViewIs('cart.index');
    }

    // ---- CartService unit-style ------------------------------------------

    #[Test]
    public function the_cart_service_totals_quantity_times_unit_price(): void
    {
        $cart = new CartService;
        $cart->addToCart($this->product, 3);

        $this->assertSame(3, $cart->getCartCount());
        $this->assertEqualsWithDelta(300, $cart->getCartTotal(), 0.001);
    }

    #[Test]
    public function the_cart_service_applies_option_price_modifiers_to_the_total(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->product->category_id,
            'price' => 100,
            'has_weight_options' => true,
        ]);

        $option = ProductOption::create(['product_id' => $product->id, 'type' => 'weight', 'name' => 'weight']);
        $value = ProductOptionValue::create([
            'product_option_id' => $option->id,
            'value' => '500g',
            'price_modifier' => 180, // weight price_modifier IS the full price
            'is_default' => true,
        ]);

        $cart = new CartService;
        $cart->addToCart($product, 2, ['weight' => $value->id]);

        // 2 × 180 (the selected weight price), not 2 × 100 (base price).
        $this->assertEqualsWithDelta(360, $cart->getCartTotal(), 0.001);
    }

    #[Test]
    public function the_cart_service_drops_inactive_products_during_validation(): void
    {
        $cart = new CartService;
        $cart->addToCart($this->product, 1);

        $this->product->update(['is_active' => false]);

        $result = $cart->validateCart();

        $this->assertFalse($result['valid']);
        $this->assertNotEmpty($result['errors']);
    }
}
