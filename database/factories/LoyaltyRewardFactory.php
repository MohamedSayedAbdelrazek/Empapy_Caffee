<?php

namespace Database\Factories;

use App\Models\LoyaltyReward;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoyaltyReward>
 */
class LoyaltyRewardFactory extends Factory
{
    protected $model = LoyaltyReward::class;

    public function definition(): array
    {
        return [
            'name' => 'مكافأة '.$this->faker->unique()->numberBetween(1, 9999),
            'description' => 'وصف المكافأة',
            'points_required' => 100,
            'reward_type' => 'discount_fixed',
            'reward_value' => 10,
            'product_id' => null,
            'icon' => '🎁',
            'stock' => null,        // null = unlimited
            'times_redeemed' => 0,
            'max_per_user' => null, // null = no per-user cap
            'tier_required' => null,
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 0,
            'available_from' => null,
            'available_until' => null,
        ];
    }

    public function discountFixed(float $value, int $points = 100): static
    {
        return $this->state(fn () => [
            'reward_type' => 'discount_fixed',
            'reward_value' => $value,
            'points_required' => $points,
        ]);
    }

    public function discountPercent(float $value, int $points = 100): static
    {
        return $this->state(fn () => [
            'reward_type' => 'discount_percent',
            'reward_value' => $value,
            'points_required' => $points,
        ]);
    }

    public function freeShipping(int $points = 100): static
    {
        return $this->state(fn () => [
            'reward_type' => 'free_shipping',
            'reward_value' => null,
            'points_required' => $points,
        ]);
    }

    public function freeProduct(Product $product, int $points = 100): static
    {
        return $this->state(fn () => [
            'reward_type' => 'free_product',
            'product_id' => $product->id,
            'reward_value' => null,
            'points_required' => $points,
        ]);
    }

    public function withStock(int $stock): static
    {
        return $this->state(fn () => ['stock' => $stock]);
    }

    public function maxPerUser(int $max): static
    {
        return $this->state(fn () => ['max_per_user' => $max]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
