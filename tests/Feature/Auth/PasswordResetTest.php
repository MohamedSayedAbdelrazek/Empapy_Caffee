<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * SEC-08(b): the standard forgot/reset-password flow exists and works.
 */
class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_forgot_password_page_loads(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertViewIs('auth.forgot-password');
    }

    #[Test]
    public function a_reset_link_notification_is_sent_for_a_known_email(): void
    {
        Notification::fake();
        $user = User::factory()->create(['role' => 'customer', 'email' => 'known@example.com']);

        $this->post(route('password.email'), ['email' => 'known@example.com'])
            ->assertSessionHas('success');

        Notification::assertSentTo($user, ResetPassword::class);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => 'known@example.com']);
    }

    #[Test]
    public function an_unknown_email_does_not_error_out_but_reports_failure(): void
    {
        Notification::fake();

        $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => 'nobody@example.com'])
            ->assertSessionHasErrors('email');

        Notification::assertNothingSent();
    }

    #[Test]
    public function the_reset_password_page_loads_with_the_token(): void
    {
        $this->get(route('password.reset', ['token' => 'sample-token']))
            ->assertOk()
            ->assertViewIs('auth.reset-password')
            ->assertViewHas('token', 'sample-token');
    }

    #[Test]
    public function a_password_can_be_reset_with_a_valid_token(): void
    {
        $user = User::factory()->create(['role' => 'customer', 'email' => 'reset@example.com']);
        $token = Password::createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'reset@example.com',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    #[Test]
    public function an_invalid_token_does_not_reset_the_password(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'reset@example.com',
            'password' => Hash::make('original-password'),
        ]);

        $this->post(route('password.update'), [
            'token' => 'totally-invalid-token',
            'email' => 'reset@example.com',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('original-password', $user->fresh()->password));
    }
}
