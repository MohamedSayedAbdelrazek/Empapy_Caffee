<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected Product $product;
    protected CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::factory()->create();

        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 99.99,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->cartService = new CartService();
    }

    /** @test */
    public function can_add_product_to_cart()
    {
        $response = $this->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'تمت الإضافة إلى السلة',
        ]);
    }

    /** @test */
    public function can_update_cart_quantity()
    {
        // First add to cart
        $this->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        // Then update quantity
        $response = $this->post(route('cart.update'), [
            'product_id' => $this->product->id,
            'quantity' => 3,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    /** @test */
    public function can_remove_product_from_cart()
    {
        // First add to cart
        $this->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        // Then remove
        $response = $this->post(route('cart.remove'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'تم الحذف من السلة',
        ]);
    }

    /** @test */
    public function can_clear_cart()
    {
        // Add products to cart
        $this->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        // Clear cart
        $response = $this->post(route('cart.clear'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'تم تفريغ السلة',
            'cartCount' => 0,
        ]);
    }

    /** @test */
    public function cannot_add_inactive_product_to_cart()
    {
        $inactiveProduct = Product::factory()->create([
            'category_id' => $this->product->category_id,
            'is_active' => false,
        ]);

        $response = $this->post(route('cart.add'), [
            'product_id' => $inactiveProduct->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'هذا المنتج غير متوفر حالياً',
        ]);
    }

    /** @test */
    public function cannot_exceed_stock_quantity()
    {
        $response = $this->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 100, // More than stock (10)
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /** @test */
    public function can_get_cart_data()
    {
        // Add product to cart
        $this->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response = $this->get(route('cart.data'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'cartCount',
            'cartTotal',
            'items',
        ]);
    }

    /** @test */
    public function cart_service_adds_product_correctly()
    {
        $result = $this->cartService->addToCart($this->product, 2);

        $this->assertTrue($result['success']);
        $this->assertEquals(2, $result['cartCount']);
    }

    /** @test */
    public function cart_service_calculates_total_correctly()
    {
        $this->cartService->addToCart($this->product, 2);

        $total = $this->cartService->getCartTotal();

        $this->assertEquals(99.99 * 2, $total);
    }

    /** @test */
    public function cart_service_validates_cart_items()
    {
        $this->cartService->addToCart($this->product, 2);

        $validation = $this->cartService->validateCart();

        $this->assertTrue($validation['valid']);
        $this->assertEmpty($validation['errors']);
    }

    /** @test */
    public function cart_page_loads_successfully()
    {
        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cart.index');
    }

    /** @test */
    public function adding_same_product_increases_quantity()
    {
        $this->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertJson([
            'cartCount' => 3,
        ]);
    }
}
