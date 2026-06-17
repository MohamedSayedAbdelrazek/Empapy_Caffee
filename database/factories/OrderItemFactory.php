<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 50, 300);
        $quantity = $this->faker->numberBetween(1, 3);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_name' => $this->faker->randomElement(['قهوة إثيوبية', 'قهوة كولومبية', 'إسبريسو']),
            'price' => $price,
            'quantity' => $quantity,
            'total' => $price * $quantity,
            'is_reward_item' => false,
            'reward_note' => null,
        ];
    }

    public function rewardItem(): static
    {
        return $this->state(fn () => [
            'is_reward_item' => true,
            'price' => 0,
            'total' => 0,
            'reward_note' => 'منتج مجاني - مكافأة الولاء',
        ]);
    }
}
