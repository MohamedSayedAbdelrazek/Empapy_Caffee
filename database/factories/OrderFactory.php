<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 *
 * order_number is intentionally left unset so the Order model's `creating`
 * hook generates a random, non-sequential token (see SEC-01 fix).
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 50, 500);

        return [
            'user_id' => null, // guest order by default
            'subtotal' => $subtotal,
            'shipping' => 0,
            'discount' => 0,
            'coupon_code' => null,
            'total' => $subtotal,
            'status' => Order::STATUS_PENDING,
            'payment_status' => 'pending',
            'payment_method' => 'cash_on_delivery',
            'currency' => 'EGP',
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->unique()->safeEmail(),
            'customer_phone' => '010'.$this->faker->numerify('########'),
            'shipping_address' => $this->faker->address(),
            'city' => 'القاهرة',
            'governorate' => 'القاهرة',
            'notes' => null,
        ];
    }

    /** Attach the order to a registered user. */
    public function forUser(User $user): static
    {
        return $this->state(fn () => ['user_id' => $user->id]);
    }

    public function status(string $status): static
    {
        return $this->state(fn () => ['status' => $status]);
    }

    public function delivered(): static
    {
        return $this->state(fn () => ['status' => Order::STATUS_DELIVERED]);
    }
}
