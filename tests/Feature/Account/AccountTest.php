<?php

namespace Tests\Feature\Account;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Safety net for the QUAL-02 refactor of AccountController (which had no tests):
 * the profile/password validation must behave exactly as before.
 */
class AccountTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_update_their_profile(): void
    {
        $user = User::factory()->create(['role' => 'customer', 'name' => 'الاسم القديم']);

        $this->actingAs($user)
            ->put(route('account.profile.update'), [
                'name' => 'الاسم الجديد',
                'email' => $user->email,
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'الاسم الجديد']);
    }

    #[Test]
    public function updating_the_profile_requires_a_name(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->put(route('account.profile.update'), ['email' => $user->email])
            ->assertSessionHasErrors('name');
    }

    #[Test]
    public function a_user_can_change_their_password_with_the_correct_current_password(): void
    {
        $user = User::factory()->create(['role' => 'customer']); // factory password = 'password'

        $this->actingAs($user)
            ->put(route('account.password'), [
                'current_password' => 'password',
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    #[Test]
    public function changing_password_with_a_wrong_current_password_fails(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->put(route('account.password'), [
                'current_password' => 'wrong-password',
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('password', $user->fresh()->password)); // unchanged
    }
}
