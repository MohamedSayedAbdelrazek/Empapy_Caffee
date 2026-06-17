<?php

namespace Tests\Feature\Security;

use App\Http\Middleware\AdminMiddleware;
use App\Models\Permission;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * SEC-02 regression guard: only admins may create admins or assign
 * roles/permissions, the actor's own role is protected, and the system can
 * never be left without an admin.
 *
 * Some tests deliberately disable AdminMiddleware to prove the *server-side*
 * checks inside StaffController hold even if the route gate were ever loosened
 * (defense in depth), since the routes are admin-gated today.
 */
class PrivilegeEscalationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
    }

    private function permissionId(string $name): int
    {
        return Permission::where('name', $name)->value('id');
    }

    // ---- route gating (primary control) ----------------------------------

    #[Test]
    public function a_cashier_cannot_reach_the_staff_create_form(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $cashier->givePermissionTo('create-users');

        // AdminMiddleware sends non-admin staff back to their orders area.
        $this->actingAs($cashier)
            ->get(route('admin.staff.create'))
            ->assertRedirect(route('admin.orders.index'));
    }

    #[Test]
    public function a_cashier_cannot_create_a_staff_member(): void
    {
        User::factory()->create(['role' => 'admin']); // existing admin for context
        $cashier = User::factory()->create(['role' => 'cashier']);
        $cashier->givePermissionTo('create-users');

        $this->actingAs($cashier)->post(route('admin.staff.store'), [
            'name' => 'مدير مزيف',
            'email' => 'forged-admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ])->assertRedirect(route('admin.orders.index'));

        $this->assertDatabaseMissing('users', ['email' => 'forged-admin@example.com']);
    }

    #[Test]
    public function an_admin_can_create_another_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.staff.store'), [
            'name' => 'مدير جديد',
            'email' => 'new-admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ])->assertRedirect(route('admin.staff.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'new-admin@example.com',
            'role' => 'admin',
        ]);
    }

    // ---- server-side validation (defense in depth) -----------------------

    #[Test]
    public function a_forged_role_admin_from_a_non_admin_is_rejected_server_side(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $cashier->givePermissionTo('create-users');

        // Simulate the route gate being loosened; the controller must still
        // reject role=admin coming from a non-admin actor.
        $this->actingAs($cashier)
            ->withoutMiddleware(AdminMiddleware::class)
            ->post(route('admin.staff.store'), [
                'name' => 'مدير مزيف',
                'email' => 'forged@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
            ])
            ->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'forged@example.com']);
    }

    #[Test]
    public function a_non_admin_cannot_grant_a_permission_they_do_not_hold(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $cashier->givePermissionTo(['edit-users', 'view-products']); // NOT delete-products

        $target = User::factory()->create(['role' => 'cashier']);

        $this->actingAs($cashier)
            ->withoutMiddleware(AdminMiddleware::class)
            ->put(route('admin.staff.update', $target), [
                'name' => $target->name,
                'email' => $target->email,
                'phone' => $target->phone ?? '',
                'role' => 'cashier',
                'permissions' => [
                    $this->permissionId('view-products'),
                    $this->permissionId('delete-products'),
                ],
            ]);

        $target->refresh();
        $this->assertTrue($target->hasPermission('view-products'), 'actor may grant a permission they hold');
        $this->assertFalse(
            $target->hasPermission('delete-products'),
            'actor must not grant a permission they do not hold'
        );
    }

    // ---- protecting the last admin ---------------------------------------

    #[Test]
    public function an_admin_cannot_demote_their_own_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->put(route('admin.staff.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'phone' => $admin->phone ?? '',
            'role' => 'cashier', // attempt self-demotion
        ]);

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }

    #[Test]
    public function an_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->delete(route('admin.staff.destroy', $admin))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    #[Test]
    public function the_last_admin_cannot_be_demoted_even_if_the_gate_is_bypassed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']); // the only admin
        $cashier = User::factory()->create(['role' => 'cashier']);
        $cashier->givePermissionTo('edit-users');

        $this->actingAs($cashier)
            ->withoutMiddleware(AdminMiddleware::class)
            ->put(route('admin.staff.update', $admin), [
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => $admin->phone ?? '',
                'role' => 'cashier',
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }

    #[Test]
    public function the_last_admin_cannot_be_deleted_even_if_the_gate_is_bypassed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']); // the only admin
        $cashier = User::factory()->create(['role' => 'cashier']);
        $cashier->givePermissionTo('delete-users');

        $this->actingAs($cashier)
            ->withoutMiddleware(AdminMiddleware::class)
            ->delete(route('admin.staff.destroy', $admin))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    #[Test]
    public function a_non_last_admin_can_be_removed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $other = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->delete(route('admin.staff.destroy', $other))
            ->assertRedirect(route('admin.staff.index'));

        $this->assertDatabaseMissing('users', ['id' => $other->id]);
    }
}
