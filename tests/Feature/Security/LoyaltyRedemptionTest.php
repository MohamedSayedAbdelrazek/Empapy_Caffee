<?php

namespace Tests\Feature\Security;

use App\Models\LoyaltyReward;
use App\Models\User;
use App\Services\LoyaltyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * SEC-03 regression guard: loyalty redemption must not let a user spend more
 * points than they hold, oversell reward stock, or exceed the per-user cap.
 *
 * True parallel races are prevented in the code by lockForUpdate + revalidation
 * inside the transaction; those cannot be reproduced single-threaded. These
 * tests lock in the deterministic invariants that revalidation enforces — most
 * importantly that a second (duplicate) redemption is rejected once the balance
 * or stock is exhausted, so there is no double-spend.
 */
class LoyaltyRedemptionTest extends TestCase
{
    use RefreshDatabase;

    private LoyaltyService $loyalty;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
        $this->loyalty = new LoyaltyService;
    }

    private function customer(int $points): User
    {
        $user = User::factory()->create(['role' => 'customer']);
        $user->loyaltyPoints->update([
            'available_points' => $points,
            'total_earned' => $points,
            'tier_points' => $points,
        ]);

        return $user->fresh();
    }

    #[Test]
    public function points_cannot_be_driven_negative(): void
    {
        $user = $this->customer(100);
        $reward = LoyaltyReward::factory()->discountFixed(10, points: 150)->create();

        $result = $this->loyalty->redeemReward($user, $reward);

        $this->assertFalse($result['success']);
        $this->assertSame(100, $user->loyaltyPoints()->first()->available_points);
        $this->assertGreaterThanOrEqual(0, $user->loyaltyPoints()->first()->available_points);
        $this->assertDatabaseCount('reward_redemptions', 0);
    }

    #[Test]
    public function a_duplicate_redemption_cannot_double_spend_points(): void
    {
        // Exactly enough for ONE redemption.
        $user = $this->customer(100);
        $reward = LoyaltyReward::factory()->discountFixed(10, points: 100)->create();

        $first = $this->loyalty->redeemReward($user, $reward);
        $second = $this->loyalty->redeemReward($user->fresh(), $reward->fresh());

        $this->assertTrue($first['success']);
        $this->assertFalse($second['success'], 'second redemption must be rejected');

        $loyalty = $user->loyaltyPoints()->first();
        $this->assertSame(0, $loyalty->available_points);
        $this->assertSame(100, $loyalty->total_redeemed); // not 200
        $this->assertDatabaseCount('reward_redemptions', 1);
    }

    #[Test]
    public function reward_stock_cannot_be_oversold(): void
    {
        $user = $this->customer(1000);
        $reward = LoyaltyReward::factory()->discountFixed(10, points: 100)->withStock(1)->create();

        $first = $this->loyalty->redeemReward($user, $reward);
        $second = $this->loyalty->redeemReward($user->fresh(), $reward->fresh());

        $this->assertTrue($first['success']);
        $this->assertFalse($second['success']);
        $this->assertSame(0, $reward->fresh()->stock); // floored at 0, never negative
        $this->assertDatabaseCount('reward_redemptions', 1);
    }

    #[Test]
    public function the_per_user_redemption_cap_is_enforced(): void
    {
        $user = $this->customer(1000);
        $reward = LoyaltyReward::factory()->discountFixed(10, points: 100)->maxPerUser(1)->create();

        $first = $this->loyalty->redeemReward($user, $reward);
        $second = $this->loyalty->redeemReward($user->fresh(), $reward->fresh());

        $this->assertTrue($first['success']);
        $this->assertFalse($second['success']);
        $this->assertDatabaseCount('reward_redemptions', 1);
    }
}
