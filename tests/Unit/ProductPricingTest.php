<?php

namespace Tests\Unit;

use App\Models\AdditiveWeightPrice;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Product price accessors and the option-aware price engine
 * (Product::calculatePriceWithOptions and the weight/roast/additive rules).
 */
class ProductPricingTest extends TestCase
{
    use RefreshDatabase;

    private function product(array $attributes = []): Product
    {
        return Product::factory()->create(array_merge([
            'category_id' => Category::factory(),
            'price' => 100,
            'sale_price' => null,
        ], $attributes));
    }

    private function addOption(Product $product, string $type, array $values): array
    {
        $option = ProductOption::create([
            'product_id' => $product->id,
            'type' => $type,
            'name' => $type,
            'sort_order' => 0,
        ]);

        $ids = [];
        foreach ($values as $label => $value) {
            $row = ProductOptionValue::create([
                'product_option_id' => $option->id,
                'value' => (string) $label,
                'price_modifier' => $value['price'],
                'is_default' => $value['default'] ?? false,
                'sort_order' => 0,
            ]);
            $ids[$label] = $row->id;
        }

        return $ids;
    }

    #[Test]
    public function current_price_falls_back_to_regular_price_without_a_sale(): void
    {
        $product = $this->product(['price' => 120, 'sale_price' => null]);

        $this->assertEqualsWithDelta(120, $product->current_price, 0.001);
        $this->assertFalse($product->is_on_sale);
        $this->assertSame(0, $product->discount_percentage);
    }

    #[Test]
    public function current_price_uses_the_sale_price_when_on_sale(): void
    {
        $product = $this->product(['price' => 100, 'sale_price' => 80]);

        $this->assertEqualsWithDelta(80, $product->current_price, 0.001);
        $this->assertTrue($product->is_on_sale);
        $this->assertSame(20, $product->discount_percentage);
    }

    #[Test]
    public function price_with_no_options_is_the_current_price(): void
    {
        $product = $this->product(['price' => 100, 'sale_price' => 80]);

        $this->assertEqualsWithDelta(80, $product->calculatePriceWithOptions([]), 0.001);
    }

    #[Test]
    public function a_weight_value_sets_the_base_price(): void
    {
        $product = $this->product(['has_weight_options' => true]);
        $weights = $this->addOption($product, 'weight', [
            '250g' => ['price' => 100, 'default' => true],
            '500g' => ['price' => 180],
        ]);

        $this->assertEqualsWithDelta(180, $product->calculatePriceWithOptions([$weights['500g']]), 0.001);
        $this->assertEqualsWithDelta(100, $product->calculatePriceWithOptions([$weights['250g']]), 0.001);
    }

    #[Test]
    public function roast_modifier_is_added_on_top_of_the_weight_price(): void
    {
        $product = $this->product(['has_weight_options' => true, 'has_roast_options' => true]);
        $weights = $this->addOption($product, 'weight', ['500g' => ['price' => 180, 'default' => true]]);
        $roasts = $this->addOption($product, 'roast', [
            'medium' => ['price' => 0, 'default' => true],
            'dark' => ['price' => 10],
        ]);

        $this->assertEqualsWithDelta(
            190,
            $product->calculatePriceWithOptions([$weights['500g'], $roasts['dark']]),
            0.001
        );
    }

    #[Test]
    public function additive_modifier_is_added_when_there_is_no_matrix_entry(): void
    {
        $product = $this->product(['has_weight_options' => true, 'has_additive_options' => true]);
        $weights = $this->addOption($product, 'weight', ['500g' => ['price' => 180, 'default' => true]]);
        $additives = $this->addOption($product, 'additive', [
            'none' => ['price' => 0, 'default' => true],
            'milk' => ['price' => 15],
        ]);

        $this->assertEqualsWithDelta(
            195,
            $product->calculatePriceWithOptions([$weights['500g'], $additives['milk']]),
            0.001
        );
    }

    #[Test]
    public function the_additive_weight_matrix_overrides_the_default_additive_modifier(): void
    {
        $product = $this->product(['has_weight_options' => true, 'has_additive_options' => true]);
        $weights = $this->addOption($product, 'weight', ['500g' => ['price' => 180, 'default' => true]]);
        $additives = $this->addOption($product, 'additive', ['milk' => ['price' => 15]]);

        // For 500g + milk the matrix says +25 (not the generic +15).
        AdditiveWeightPrice::create([
            'additive_option_value_id' => $additives['milk'],
            'weight_option_value_id' => $weights['500g'],
            'price_modifier' => 25,
        ]);

        $this->assertEqualsWithDelta(
            205,
            $product->calculatePriceWithOptions([$weights['500g'], $additives['milk']]),
            0.001
        );
    }

    #[Test]
    public function a_flavor_value_sets_the_base_price_like_weight(): void
    {
        $product = $this->product(['has_flavor_options' => true]);
        $flavors = $this->addOption($product, 'flavor', [
            'vanilla' => ['price' => 150, 'default' => true],
            'caramel' => ['price' => 170],
        ]);

        $this->assertEqualsWithDelta(170, $product->calculatePriceWithOptions([$flavors['caramel']]), 0.001);
    }

    #[Test]
    public function starting_min_and_max_prices_reflect_the_weight_range(): void
    {
        $product = $this->product(['has_weight_options' => true]);
        $this->addOption($product, 'weight', [
            '250g' => ['price' => 100, 'default' => true],
            '500g' => ['price' => 180],
            '1kg' => ['price' => 320],
        ]);
        $product->refresh();

        $this->assertEqualsWithDelta(100, $product->starting_price, 0.001); // default weight
        $this->assertEqualsWithDelta(100, $product->min_price, 0.001);
        $this->assertEqualsWithDelta(320, $product->max_price, 0.001);
    }
}
