<?php

namespace Tests\Feature\Security;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * SEC-01 regression guard: customer order PII must not be reachable by an
 * unauthenticated/forged request.
 *
 *  - the success page needs a valid signed link OR order ownership;
 *  - /track only reveals an order after a matching second factor;
 *  - order numbers are random, not sequential/enumerable.
 */
class OrderPiiTest extends TestCase
{
    use RefreshDatabase;

    private function order(array $attrs = []): Order
    {
        return Order::factory()->create(array_merge([
            'customer_email' => 'owner@example.com',
            'customer_phone' => '01099887766',
        ], $attrs));
    }

    // ---- checkout.success ------------------------------------------------

    #[Test]
    public function a_guest_cannot_open_the_success_page_without_a_signed_link(): void
    {
        $order = $this->order();

        $this->get(route('checkout.success', $order->order_number))
            ->assertForbidden();
    }

    #[Test]
    public function a_different_user_cannot_open_someone_elses_success_page(): void
    {
        $owner = User::factory()->create(['role' => 'customer']);
        $attacker = User::factory()->create(['role' => 'customer']);
        $order = $this->order(['user_id' => $owner->id]);

        $this->actingAs($attacker)
            ->get(route('checkout.success', $order->order_number))
            ->assertForbidden();
    }

    #[Test]
    public function the_owner_can_open_their_own_success_page(): void
    {
        $owner = User::factory()->create(['role' => 'customer']);
        $order = $this->order(['user_id' => $owner->id]);

        $this->actingAs($owner)
            ->get(route('checkout.success', $order->order_number))
            ->assertOk()
            ->assertViewIs('checkout.success');
    }

    #[Test]
    public function a_valid_signed_link_grants_a_guest_access(): void
    {
        $order = $this->order();

        $signedUrl = URL::temporarySignedRoute(
            'checkout.success',
            now()->addHour(),
            ['order' => $order->order_number]
        );

        $this->get($signedUrl)->assertOk()->assertViewIs('checkout.success');
    }

    #[Test]
    public function a_tampered_signature_is_rejected(): void
    {
        $order = $this->order();

        $signedUrl = URL::temporarySignedRoute(
            'checkout.success',
            now()->addHour(),
            ['order' => $order->order_number]
        );

        $this->get($signedUrl.'tampered')->assertForbidden();
    }

    // ---- /track ----------------------------------------------------------

    #[Test]
    public function the_track_page_reveals_nothing_from_the_query_string_alone(): void
    {
        $order = $this->order();

        $this->get(route('orders.track', ['order_number' => $order->order_number]))
            ->assertOk()
            ->assertViewIs('orders.track')
            ->assertViewMissing('order')
            ->assertDontSee($order->customer_email)
            ->assertDontSee($order->customer_phone);
    }

    #[Test]
    public function tracking_with_a_wrong_second_factor_reveals_nothing(): void
    {
        $order = $this->order();

        $this->from(route('orders.track'))
            ->post(route('orders.search'), [
                'order_number' => $order->order_number,
                'verification' => 'wrong@example.com',
            ])
            ->assertRedirect(route('orders.track'))
            ->assertSessionHas('error');
    }

    #[Test]
    public function tracking_with_a_matching_email_reveals_the_order(): void
    {
        $order = $this->order();

        $this->post(route('orders.search'), [
            'order_number' => $order->order_number,
            'verification' => 'owner@example.com',
        ])
            ->assertOk()
            ->assertViewIs('orders.track')
            ->assertViewHas('order');
    }

    #[Test]
    public function tracking_with_a_matching_phone_reveals_the_order(): void
    {
        $order = $this->order();

        // Different formatting (spaces) must still match: comparison is digits-only.
        $this->post(route('orders.search'), [
            'order_number' => $order->order_number,
            'verification' => '0109 988 7766',
        ])
            ->assertOk()
            ->assertViewHas('order');
    }

    #[Test]
    public function tracking_a_non_existent_order_reveals_nothing(): void
    {
        $this->from(route('orders.track'))
            ->post(route('orders.search'), [
                'order_number' => 'EMP-DOESNOTEXIST',
                'verification' => 'owner@example.com',
            ])
            ->assertRedirect(route('orders.track'))
            ->assertSessionHas('error');
    }

    // ---- order numbers ---------------------------------------------------

    #[Test]
    public function order_numbers_are_random_and_not_sequential(): void
    {
        $orders = Order::factory()->count(3)->create();

        foreach ($orders as $order) {
            $this->assertMatchesRegularExpression('/^EMP-[A-Z0-9]{10}$/', $order->order_number);
            // Must not embed the (sequential) primary key.
            $this->assertNotSame('EMP-'.$order->id, $order->order_number);
        }

        $numbers = $orders->pluck('order_number')->all();
        $this->assertSame($numbers, array_unique($numbers), 'order numbers must be unique');
    }
}
