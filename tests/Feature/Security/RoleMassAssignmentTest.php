<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * SEC-07 regression guard: `role` must not be mass-assignable, so a forged
 * `role` in request input can never escalate a user to admin/cashier.
 */
class RoleMassAssignmentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function role_is_not_in_the_fillable_list(): void
    {
        $this->assertNotContains('role', (new User)->getFillable());
    }

    #[Test]
    public function fill_does_not_set_the_role_attribute(): void
    {
        $user = new User;
        $user->fill([
            'name' => 'x',
            'email' => 'x@example.com',
            'role' => 'admin', // should be ignored by mass assignment
        ]);

        $this->assertNull($user->role);
    }

    #[Test]
    public function registration_ignores_a_forged_role_and_creates_a_customer(): void
    {
        Http::fake();

        $this->post('/register', [
            'name' => 'عميل جديد',
            'email' => 'newcust@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin', // forged — must be ignored
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newcust@example.com',
            'role' => 'customer',
        ]);
        $this->assertDatabaseMissing('users', [
            'email' => 'newcust@example.com',
            'role' => 'admin',
        ]);
    }
}
