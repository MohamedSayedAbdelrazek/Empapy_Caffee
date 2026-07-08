<?php

namespace Tests\Feature\Admin;

use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Order;
use App\Models\RewardRedemption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * NEW-02 regression guard: the admin cancel and refund flows used to run the
 * coupon-usage refund, reward-points restore, and order update as separate
 * unprotected writes. Both flows now run inside a DB transaction with the
 * order/coupon/redemption/loyalty rows locked (mirroring the customer cancel
 * path in OrderTrackingController), so a mid-sequence failure can never leave
 * the counters inconsistent.
 */
class OrderCancelAdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function admin_cancel_refunds_coupon_usage_globally_and_per_user(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $coupon = Coupon::factory()->create(['usage_count' => 1]);
        CouponUser::create([
            'coupon_id' => $coupon->id,
            'user_id' => $customer->id,
            'usage_count' => 1,
        ]);
        $order = Order::factory()->forUser($customer)->create(['coupon_code' => $coupon->code]);

        $this->actingAs($this->admin())
            ->post(route('admin.orders.cancel', $order))
            ->assertSessionHas('success');

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(0, $coupon->fresh()->usage_count);
        $this->assertSame(
            0,
            CouponUser::where('coupon_id', $coupon->id)->where('user_id', $customer->id)->value('usage_count'),
            'Per-user coupon usage must be refunded too, like the customer cancel path does.'
        );
    }

    #[Test]
    public function admin_cancel_restores_reward_redemption_points(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $pointsBefore = $customer->loyaltyPoints->available_points;

        $redemption = RewardRedemption::factory()->applied()->create([
            'user_id' => $customer->id,
            'points_spent' => 100,
        ]);
        $order = Order::factory()->forUser($customer)->create([
            'coupon_code' => $redemption->redemption_code,
        ]);
        $redemption->update(['order_id' => $order->id]);

        $this->actingAs($this->admin())
            ->post(route('admin.orders.cancel', $order))
            ->assertSessionHas('success');

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame('cancelled', $redemption->fresh()->status);
        $this->assertSame($pointsBefore + 100, $customer->loyaltyPoints->fresh()->available_points);
    }

    #[Test]
    public function a_failure_mid_cancel_rolls_back_every_write(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $coupon = Coupon::factory()->create(['usage_count' => 1]);
        CouponUser::create([
            'coupon_id' => $coupon->id,
            'user_id' => $customer->id,
            'usage_count' => 1,
        ]);
        $order = Order::factory()->forUser($customer)->create(['coupon_code' => $coupon->code]);

        // Make the final write of the sequence fail, AFTER the coupon
        // counters have already been decremented inside the transaction.
        Order::updating(function () {
            throw new \RuntimeException('Simulated mid-cancel failure');
        });

        $this->actingAs($this->admin())
            ->post(route('admin.orders.cancel', $order))
            ->assertSessionHas('error');

        // Atomicity: nothing moved.
        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);
        $this->assertSame(1, $coupon->fresh()->usage_count);
        $this->assertSame(
            1,
            CouponUser::where('coupon_id', $coupon->id)->where('user_id', $customer->id)->value('usage_count')
        );
    }

    #[Test]
    public function admin_refund_cancels_the_order_and_restores_credits(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $pointsBefore = $customer->loyaltyPoints->available_points;

        $redemption = RewardRedemption::factory()->applied()->create([
            'user_id' => $customer->id,
            'points_spent' => 100,
        ]);
        $order = Order::factory()->forUser($customer)->delivered()->create([
            'coupon_code' => $redemption->redemption_code,
            'payment_status' => 'paid',
        ]);
        $redemption->update(['order_id' => $order->id]);

        $this->actingAs($this->admin())
            ->patch(route('admin.orders.payment-status', $order), ['payment_status' => 'refunded'])
            ->assertSessionHas('success');

        $order = $order->fresh();
        $this->assertSame('cancelled', $order->status);
        $this->assertSame('refunded', $order->payment_status);
        $this->assertSame('cancelled', $redemption->fresh()->status);
        $this->assertSame($pointsBefore + 100, $customer->loyaltyPoints->fresh()->available_points);
    }

    #[Test]
    public function a_failure_mid_refund_rolls_back_every_write(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $pointsBefore = $customer->loyaltyPoints->available_points;

        $redemption = RewardRedemption::factory()->applied()->create([
            'user_id' => $customer->id,
            'points_spent' => 100,
        ]);
        $order = Order::factory()->forUser($customer)->delivered()->create([
            'coupon_code' => $redemption->redemption_code,
            'payment_status' => 'paid',
        ]);
        $redemption->update(['order_id' => $order->id]);

        Order::updating(function () {
            throw new \RuntimeException('Simulated mid-refund failure');
        });

        $this->actingAs($this->admin())
            ->patch(route('admin.orders.payment-status', $order), ['payment_status' => 'refunded'])
            ->assertSessionHas('error');

        $order = $order->fresh();
        $this->assertSame(Order::STATUS_DELIVERED, $order->status);
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame('applied', $redemption->fresh()->status);
        $this->assertSame($pointsBefore, $customer->loyaltyPoints->fresh()->available_points);
    }
}
