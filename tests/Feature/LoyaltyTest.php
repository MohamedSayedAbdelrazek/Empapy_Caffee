<?php

namespace Tests\Feature;

use App\Models\LoyaltyReward;
use App\Models\LoyaltyTier;
use App\Models\Order;
use App\Models\PointRule;
use App\Models\User;
use App\Services\LoyaltyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Loyalty engine: point earning rules, reward redemption, and tier math.
 */
class LoyaltyTest extends TestCase
{
    use RefreshDatabase;

    private LoyaltyService $loyalty;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake(); // never touch FCM
        $this->loyalty = new LoyaltyService;
    }

    private function customerWithPoints(int $available = 0, int $tierPoints = 0): User
    {
        $user = User::factory()->create(['role' => 'customer']);
        $user->loyaltyPoints->update([
            'available_points' => $available,
            'total_earned' => $available,
            'tier_points' => $tierPoints,
        ]);

        return $user->fresh();
    }

    private function seedDefaultTiers(): void
    {
        foreach (LoyaltyTier::getDefaultTiers() as $tier) {
            LoyaltyTier::create($tier);
        }
    }

    // ---- Earning ---------------------------------------------------------

    #[Test]
    public function points_are_earned_per_currency_spent_on_an_order(): void
    {
        PointRule::factory()->perCurrency(1)->create(); // 1 point per 1 EGP
        $user = $this->customerWithPoints();
        $order = Order::factory()->delivered()->forUser($user)->create(['total' => 200]);

        $tx = $this->loyalty->processOrderPoints($order);

        $this->assertNotNull($tx);
        $this->assertSame(200, $tx->points);
        $this->assertDatabaseHas('loyalty_points', [
            'user_id' => $user->id,
            'available_points' => 200,
            'total_earned' => 200,
        ]);
    }

    #[Test]
    public function the_max_points_per_order_cap_is_respected(): void
    {
        PointRule::factory()->perCurrency(1)->maxPerOrder(50)->create();
        $user = $this->customerWithPoints();
        $order = Order::factory()->delivered()->forUser($user)->create(['total' => 200]);

        $tx = $this->loyalty->processOrderPoints($order);

        $this->assertSame(50, $tx->points); // capped from 200
    }

    #[Test]
    public function the_tier_multiplier_boosts_earned_points(): void
    {
        $this->seedDefaultTiers();
        PointRule::factory()->perCurrency(1)->create();
        // 1500 tier points → silver tier (×1.25 multiplier).
        $user = $this->customerWithPoints(tierPoints: 1500);
        $order = Order::factory()->delivered()->forUser($user)->create(['total' => 100]);

        $tx = $this->loyalty->processOrderPoints($order);

        $this->assertSame(125, $tx->points); // 100 × 1.25
    }

    #[Test]
    public function a_first_order_only_rule_does_not_apply_after_the_first_order(): void
    {
        PointRule::factory()->fixed(100)->firstOrderOnly()->create();
        $user = $this->customerWithPoints();

        // Two delivered orders exist → rule should be skipped.
        Order::factory()->delivered()->forUser($user)->create(['total' => 100]);
        $second = Order::factory()->delivered()->forUser($user)->create(['total' => 100]);

        $tx = $this->loyalty->processOrderPoints($second);

        $this->assertNull($tx); // no points awarded
        $this->assertDatabaseHas('loyalty_points', [
            'user_id' => $user->id,
            'available_points' => 0,
        ]);
    }

    // ---- Redemption ------------------------------------------------------

    #[Test]
    public function redeeming_a_reward_deducts_points_and_creates_a_redemption(): void
    {
        $user = $this->customerWithPoints(available: 200, tierPoints: 200);
        $reward = LoyaltyReward::factory()->discountFixed(10, points: 100)->create();

        $result = $this->loyalty->redeemReward($user, $reward);

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('loyalty_points', [
            'user_id' => $user->id,
            'available_points' => 100, // 200 − 100
            'total_redeemed' => 100,
        ]);
        $this->assertDatabaseHas('reward_redemptions', [
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'points_spent' => 100,
            'status' => 'pending',
        ]);
        $this->assertSame(1, $reward->fresh()->times_redeemed);
    }

    #[Test]
    public function redeeming_decrements_finite_reward_stock(): void
    {
        $user = $this->customerWithPoints(available: 500, tierPoints: 500);
        $reward = LoyaltyReward::factory()->discountFixed(10, points: 100)->withStock(3)->create();

        $this->loyalty->redeemReward($user, $reward);

        $this->assertSame(2, $reward->fresh()->stock);
    }

    #[Test]
    public function redemption_fails_with_insufficient_points(): void
    {
        $user = $this->customerWithPoints(available: 50, tierPoints: 50);
        $reward = LoyaltyReward::factory()->discountFixed(10, points: 100)->create();

        $result = $this->loyalty->redeemReward($user, $reward);

        $this->assertFalse($result['success']);
        $this->assertDatabaseHas('loyalty_points', [
            'user_id' => $user->id,
            'available_points' => 50, // untouched
        ]);
        $this->assertDatabaseCount('reward_redemptions', 0);
    }

    // ---- Tiers -----------------------------------------------------------

    #[Test]
    public function reaching_a_threshold_upgrades_the_tier(): void
    {
        $this->seedDefaultTiers();
        $user = $this->customerWithPoints(tierPoints: 1200); // ≥ 1000 → silver

        $result = $this->loyalty->checkTierUpgrade($user);

        $this->assertTrue($result['upgraded']);
        $this->assertSame('silver', $result['new_tier']);
        $this->assertDatabaseHas('loyalty_points', [
            'user_id' => $user->id,
            'current_tier' => 'silver',
        ]);
    }

    #[Test]
    public function a_higher_balance_reaches_a_higher_tier(): void
    {
        $this->seedDefaultTiers();
        $user = $this->customerWithPoints(tierPoints: 6000); // ≥ 5000 → gold

        $this->loyalty->checkTierUpgrade($user);

        $this->assertDatabaseHas('loyalty_points', [
            'user_id' => $user->id,
            'current_tier' => 'gold',
        ]);
    }

    #[Test]
    public function staying_below_the_threshold_does_not_upgrade(): void
    {
        $this->seedDefaultTiers();
        $user = $this->customerWithPoints(tierPoints: 500); // below silver (1000)

        $result = $this->loyalty->checkTierUpgrade($user);

        $this->assertFalse($result['upgraded']);
        $this->assertDatabaseHas('loyalty_points', [
            'user_id' => $user->id,
            'current_tier' => 'bronze',
        ]);
    }
}
