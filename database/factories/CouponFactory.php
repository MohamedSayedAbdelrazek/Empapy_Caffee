<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(Str::random(8)).$this->faker->unique()->numberBetween(100, 999),
            'name_ar' => 'كوبون خصم',
            'description_ar' => null,
            'type' => 'percentage',
            'value' => 10,
            'min_order_amount' => null,
            'max_discount' => null,
            'usage_limit' => null,
            'per_user_limit' => null,
            'usage_count' => 0,
            'starts_at' => null,
            'expires_at' => null,
            'is_active' => true,
        ];
    }

    /** A percentage discount coupon. */
    public function percentage(float $value): static
    {
        return $this->state(fn () => ['type' => 'percentage', 'value' => $value]);
    }

    /** A fixed-amount discount coupon. */
    public function fixed(float $value): static
    {
        return $this->state(fn () => ['type' => 'fixed', 'value' => $value]);
    }

    /** Coupon whose validity window has already passed. */
    public function expired(): static
    {
        return $this->state(fn () => [
            'starts_at' => now()->subDays(10),
            'expires_at' => now()->subDay(),
        ]);
    }

    /** Coupon whose validity window has not started yet. */
    public function notYetStarted(): static
    {
        return $this->state(fn () => [
            'starts_at' => now()->addDay(),
            'expires_at' => now()->addDays(10),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
