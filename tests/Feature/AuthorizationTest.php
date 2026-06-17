<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Each admin route is gated by a specific permission: a staff member without it
 * gets 403, and the matching permission (and only it) opens the gate.
 */
class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** GET admin index routes → the permission that should gate them. */
    private const GATED_ROUTES = [
        'admin.dashboard' => 'view-analytics',
        'admin.products.index' => 'view-products',
        'admin.categories.index' => 'view-categories',
        'admin.coupons.index' => 'view-coupons',
        'admin.users.index' => 'view-users',
        'admin.announcements.index' => 'view-announcements',
        'admin.settings.index' => 'edit-settings',
        'admin.shipping-zones.index' => 'manage-site',
        'admin.contacts.index' => 'view-contacts',
        'admin.loyalty.dashboard' => 'view-loyalty',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
    }

    #[Test]
    public function a_cashier_without_any_permission_is_forbidden_from_every_gated_route(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);

        foreach (self::GATED_ROUTES as $routeName => $permission) {
            $this->actingAs($cashier)
                ->get(route($routeName))
                ->assertForbidden();
        }
    }

    #[Test]
    public function the_matching_permission_opens_the_gate(): void
    {
        // Light index pages that render cleanly with no seeded data.
        $cases = [
            'admin.products.index' => 'view-products',
            'admin.categories.index' => 'view-categories',
            'admin.coupons.index' => 'view-coupons',
        ];

        foreach ($cases as $routeName => $permission) {
            $cashier = User::factory()->create(['role' => 'cashier']);
            $cashier->givePermissionTo($permission);

            $this->actingAs($cashier)
                ->get(route($routeName))
                ->assertOk();
        }
    }

    #[Test]
    public function holding_a_different_permission_does_not_open_an_unrelated_gate(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $cashier->givePermissionTo('view-categories'); // not view-products

        $this->actingAs($cashier)
            ->get(route('admin.products.index'))
            ->assertForbidden();
    }

    #[Test]
    public function an_admin_bypasses_the_permission_gates(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        foreach (['admin.products.index', 'admin.categories.index', 'admin.coupons.index'] as $routeName) {
            $this->actingAs($admin)->get(route($routeName))->assertOk();
        }
    }
}
