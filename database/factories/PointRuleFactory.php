<?php

namespace Database\Factories;

use App\Models\PointRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PointRule>
 */
class PointRuleFactory extends Factory
{
    protected $model = PointRule::class;

    public function definition(): array
    {
        return [
            'slug' => 'rule-'.$this->faker->unique()->numberBetween(1, 99999),
            'name' => 'قاعدة نقاط',
            'description' => null,
            'type' => 'fixed',
            'value' => 50,
            'trigger' => 'order_complete',
            'is_first_order_only' => false,
            'min_order_amount' => null,
            'max_points_per_order' => null,
            'is_active' => true,
            'starts_at' => null,
            'ends_at' => null,
            'priority' => 0,
        ];
    }

    /** Earn a fixed number of points for the given trigger. */
    public function fixed(int $value, string $trigger = 'order_complete'): static
    {
        return $this->state(fn () => ['type' => 'fixed', 'value' => $value, 'trigger' => $trigger]);
    }

    /** Earn `value` points per 1 currency unit spent. */
    public function perCurrency(float $value, string $trigger = 'order_complete'): static
    {
        return $this->state(fn () => ['type' => 'per_currency', 'value' => $value, 'trigger' => $trigger]);
    }

    /** Earn a percentage of the order total as points. */
    public function percentage(float $value, string $trigger = 'order_complete'): static
    {
        return $this->state(fn () => ['type' => 'percentage', 'value' => $value, 'trigger' => $trigger]);
    }

    public function trigger(string $trigger): static
    {
        return $this->state(fn () => ['trigger' => $trigger]);
    }

    public function firstOrderOnly(): static
    {
        return $this->state(fn () => ['is_first_order_only' => true]);
    }

    public function maxPerOrder(int $max): static
    {
        return $this->state(fn () => ['max_points_per_order' => $max]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
