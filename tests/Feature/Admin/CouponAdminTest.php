<?php

namespace Tests\Feature\Admin;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * NEW-01 regression guard: routes/web.php used to register admin.coupons.show
 * pointing at a CouponController::show() that never existed, so any direct
 * GET /admin/coupons/{id} threw a 500. The dead route was removed (the admin
 * panel only uses index/create/edit); these tests pin the removal and prove
 * every remaining coupon admin route works for an authorized admin.
 */
class CouponAdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function the_dead_coupon_show_route_is_gone(): void
    {
        $this->assertFalse(
            Route::has('admin.coupons.show'),
            'admin.coupons.show points at a nonexistent controller method; it must stay removed.'
        );

        // A direct GET on the old URL is now a 405 (the URI only accepts
        // PUT/DELETE for update/destroy), not a 500.
        $coupon = Coupon::factory()->create();

        $this->actingAs($this->admin())
            ->get("/admin/coupons/{$coupon->id}")
            ->assertStatus(405);
    }

    #[Test]
    public function an_admin_can_view_the_coupons_index(): void
    {
        Coupon::factory()->count(2)->create();

        $this->actingAs($this->admin())
            ->get(route('admin.coupons.index'))
            ->assertOk();
    }

    #[Test]
    public function an_admin_can_view_the_create_form(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.coupons.create'))
            ->assertOk();
    }

    #[Test]
    public function an_admin_can_create_a_coupon(): void
    {
        $this->actingAs($this->admin())->post(route('admin.coupons.store'), [
            'code' => 'summer26',
            'name_ar' => 'خصم الصيف',
            'type' => 'percentage',
            'value' => 15,
        ])->assertRedirect(route('admin.coupons.index'));

        $this->assertDatabaseHas('coupons', [
            'code' => 'SUMMER26', // stored uppercased
            'name_ar' => 'خصم الصيف',
            'type' => 'percentage',
        ]);
    }

    #[Test]
    public function an_admin_can_view_the_edit_form(): void
    {
        $coupon = Coupon::factory()->create();

        $this->actingAs($this->admin())
            ->get(route('admin.coupons.edit', $coupon))
            ->assertOk();
    }

    #[Test]
    public function an_admin_can_update_a_coupon(): void
    {
        $coupon = Coupon::factory()->percentage(10)->create();

        $this->actingAs($this->admin())->put(route('admin.coupons.update', $coupon), [
            'code' => $coupon->code,
            'name_ar' => 'كوبون معدل',
            'type' => 'fixed',
            'value' => 25,
        ])->assertRedirect(route('admin.coupons.index'));

        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'name_ar' => 'كوبون معدل',
            'type' => 'fixed',
        ]);
    }

    #[Test]
    public function an_admin_can_delete_a_coupon(): void
    {
        $coupon = Coupon::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('admin.coupons.destroy', $coupon))
            ->assertRedirect(route('admin.coupons.index'));

        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    }
}
