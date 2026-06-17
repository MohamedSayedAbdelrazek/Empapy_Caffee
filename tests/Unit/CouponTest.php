<?php

namespace Tests\Unit;

use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Pure coupon domain logic: discount math + validity rules.
 * (Concurrency / over-redemption is covered in the security suite.)
 */
class CouponTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function percentage_discount_is_calculated_from_the_order_total(): void
    {
        $coupon = Coupon::factory()->percentage(10)->create();

        $this->assertSame(20.0, $coupon->calculateDiscount(200));
    }

    #[Test]
    public function fixed_discount_returns_a_flat_amount(): void
    {
        $coupon = Coupon::factory()->fixed(30)->create();

        $this->assertSame(30.0, $coupon->calculateDiscount(200));
    }

    #[Test]
    public function discount_never_exceeds_the_order_total(): void
    {
        $coupon = Coupon::factory()->fixed(300)->create();

        $this->assertSame(200.0, $coupon->calculateDiscount(200));
    }

    #[Test]
    public function max_discount_caps_a_percentage_coupon(): void
    {
        $coupon = Coupon::factory()->percentage(50)->create(['max_discount' => 40]);

        // 50% of 200 = 100, capped at 40.
        $this->assertSame(40.0, $coupon->calculateDiscount(200));
    }

    #[Test]
    public function discount_is_zero_below_the_minimum_order_amount(): void
    {
        $coupon = Coupon::factory()->fixed(30)->create(['min_order_amount' => 100]);

        $this->assertSame(0.0, $coupon->calculateDiscount(50));
        $this->assertSame(30.0, $coupon->calculateDiscount(150));
    }

    #[Test]
    public function an_inactive_coupon_is_invalid(): void
    {
        $coupon = Coupon::factory()->inactive()->create();

        $this->assertFalse($coupon->isValid());
    }

    #[Test]
    public function an_expired_coupon_is_invalid(): void
    {
        $coupon = Coupon::factory()->expired()->create();

        $this->assertFalse($coupon->isValid());
    }

    #[Test]
    public function a_coupon_that_has_not_started_is_invalid(): void
    {
        $coupon = Coupon::factory()->notYetStarted()->create();

        $this->assertFalse($coupon->isValid());
    }

    #[Test]
    public function a_coupon_at_its_global_usage_limit_is_invalid(): void
    {
        $coupon = Coupon::factory()->create(['usage_limit' => 2, 'usage_count' => 2]);

        $this->assertFalse($coupon->isValid());

        $coupon->usage_count = 1;
        $this->assertTrue($coupon->isValid());
    }

    #[Test]
    public function the_per_user_limit_is_enforced_for_the_given_user(): void
    {
        $coupon = Coupon::factory()->create(['per_user_limit' => 1]);
        $user = User::factory()->create();
        $other = User::factory()->create();

        // User has already used it once → at their personal limit.
        CouponUser::create([
            'coupon_id' => $coupon->id,
            'user_id' => $user->id,
            'usage_count' => 1,
        ]);

        $this->assertSame(1, $coupon->usageCountForUser($user->id));
        $this->assertFalse($coupon->isValid($user->id), 'user at per-user limit should be invalid');
        $this->assertTrue($coupon->isValid($other->id), 'a different user is still within their limit');
        // With no user context the per-user limit cannot apply.
        $this->assertTrue($coupon->isValid());
    }
}
