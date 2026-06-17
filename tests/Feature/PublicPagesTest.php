<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Smoke tests for the public storefront pages. These render the full layout,
 * so they also guard against regressions like BUG-01 (env() used in a Blade
 * view, which returns null under config:cache) breaking the shared layout.
 */
class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_home_page_loads(): void
    {
        $this->get(route('home'))->assertOk();
    }

    #[Test]
    public function the_shop_page_loads_and_lists_active_products(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $this->get(route('shop.index'))
            ->assertOk()
            ->assertSee($product->name);
    }

    #[Test]
    public function a_product_detail_page_loads(): void
    {
        $product = Product::factory()->create([
            'category_id' => Category::factory(),
            'is_active' => true,
        ]);

        $this->get(route('shop.show', $product->slug))->assertOk();
    }

    #[Test]
    public function the_login_and_register_pages_load_for_guests(): void
    {
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
    }

    #[Test]
    public function the_order_tracking_page_loads(): void
    {
        $this->get(route('orders.track'))->assertOk()->assertViewIs('orders.track');
    }
}
