<?php

namespace Tests\Feature\Security;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * SEC-06 regression guard: FCM device-token endpoints must be scoped to the
 * authenticated owner — a user can only register/unregister their own tokens
 * and can neither hijack nor disable another account's token.
 */
class DeviceTokenTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_register_their_own_token(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->postJson(route('api.device.register'), ['token' => 'MY-TOKEN'])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('user_devices', [
            'user_id' => $user->id,
            'fcm_token' => 'MY-TOKEN',
            'is_active' => true,
        ]);
    }

    #[Test]
    public function registering_an_existing_own_token_reactivates_it(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $device = UserDevice::create([
            'user_id' => $user->id,
            'fcm_token' => 'MY-TOKEN',
            'is_active' => false,
        ]);

        $this->actingAs($user)
            ->postJson(route('api.device.register'), ['token' => 'MY-TOKEN'])
            ->assertOk();

        $this->assertTrue($device->fresh()->is_active);
        $this->assertSame(1, UserDevice::where('fcm_token', 'MY-TOKEN')->count());
    }

    #[Test]
    public function a_user_cannot_hijack_another_users_token_via_register(): void
    {
        $owner = User::factory()->create(['role' => 'customer']);
        $attacker = User::factory()->create(['role' => 'customer']);
        $device = UserDevice::create([
            'user_id' => $owner->id,
            'fcm_token' => 'VICTIM-TOKEN',
            'is_active' => true,
        ]);

        $this->actingAs($attacker)
            ->postJson(route('api.device.register'), ['token' => 'VICTIM-TOKEN'])
            ->assertStatus(409);

        // Still owned by the original user and untouched.
        $device->refresh();
        $this->assertSame($owner->id, $device->user_id);
        $this->assertTrue($device->is_active);
    }

    #[Test]
    public function a_user_cannot_unregister_another_users_token(): void
    {
        $owner = User::factory()->create(['role' => 'customer']);
        $attacker = User::factory()->create(['role' => 'customer']);
        $device = UserDevice::create([
            'user_id' => $owner->id,
            'fcm_token' => 'VICTIM-TOKEN',
            'is_active' => true,
        ]);

        $this->actingAs($attacker)
            ->postJson(route('api.device.unregister'), ['token' => 'VICTIM-TOKEN'])
            ->assertOk();

        // The victim's token must remain active.
        $this->assertTrue($device->fresh()->is_active);
    }

    #[Test]
    public function a_user_can_unregister_their_own_token(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $device = UserDevice::create([
            'user_id' => $user->id,
            'fcm_token' => 'MY-TOKEN',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->postJson(route('api.device.unregister'), ['token' => 'MY-TOKEN'])
            ->assertOk();

        $this->assertFalse($device->fresh()->is_active);
    }

    #[Test]
    public function the_endpoints_require_authentication(): void
    {
        $this->postJson(route('api.device.register'), ['token' => 'X'])->assertUnauthorized();
        $this->postJson(route('api.device.unregister'), ['token' => 'X'])->assertUnauthorized();
    }
}
