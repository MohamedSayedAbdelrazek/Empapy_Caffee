<?php

namespace Database\Factories;

use App\Models\LoyaltyReward;
use App\Models\RewardRedemption;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RewardRedemption>
 */
class RewardRedemptionFactory extends Factory
{
    protected $model = RewardRedemption::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reward_id' => LoyaltyReward::factory(),
            'points_spent' => 100,
            'status' => 'pending',
            'redemption_code' => RewardRedemption::generateCode(),
            'order_id' => null,
            'expires_at' => now()->addDays(30),
            'applied_at' => null,
        ];
    }

    public function applied(): static
    {
        return $this->state(fn () => ['status' => 'applied', 'applied_at' => now()]);
    }

    public function expired(): static
    {
        return $this->state(fn () => ['expires_at' => now()->subDay()]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => 'cancelled']);
    }
}
