<?php

namespace Database\Factories;

use App\Models\LoyaltyTier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoyaltyTier>
 */
class LoyaltyTierFactory extends Factory
{
    protected $model = LoyaltyTier::class;

    public function definition(): array
    {
        return [
            'slug' => 'tier-'.$this->faker->unique()->numberBetween(1, 99999),
            'name' => 'مستوى',
            'description' => null,
            'min_points' => 0,
            'max_points' => null,
            'discount_percent' => 0,
            'free_shipping' => false,
            'free_shipping_threshold' => null,
            'points_multiplier' => 1.00,
            'icon' => '🥉',
            'color' => '#CD7F32',
            'perks' => null,
            'sort_order' => 0,
            'is_active' => true,
        ];
    }

    public function multiplier(float $multiplier): static
    {
        return $this->state(fn () => ['points_multiplier' => $multiplier]);
    }
}
