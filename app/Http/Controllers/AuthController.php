<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Create fancy time-based welcome message
            $user = Auth::user();
            $hour = now()->format('H');

            if ($hour >= 5 && $hour < 12) {
                $greeting = 'صباح الخير';
                $emoji = '☀️';
            } elseif ($hour >= 12 && $hour < 17) {
                $greeting = 'مساء النور';
                $emoji = '🌤️';
            } elseif ($hour >= 17 && $hour < 21) {
                $greeting = 'مساء الخير';
                $emoji = '🌅';
            } else {
                $greeting = 'أهلاً بك';
                $emoji = '🌙';
            }

            $welcomeMessage = "{$emoji} {$greeting}، {$user->name}! مرحباً بعودتك";

            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('welcome', $welcomeMessage);
            }

            if ($user->isCashier()) {
                return redirect()->intended(route('admin.orders.index'))
                    ->with('welcome', $welcomeMessage);
            }

            return redirect()->intended(route('home'))
                ->with('welcome', $welcomeMessage);
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة'
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegister(Request $request)
    {
        // Capture referral code from URL if present
        if ($request->has('ref')) {
            session(['referral_code' => $request->get('ref')]);
        }

        return view('auth.register', [
            'referralCode' => session('referral_code')
        ]);
    }

    /**
     * Handle registration request
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = new User([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);
        // Set the role explicitly (not mass-assignable — SEC-07). Done before
        // save() so the "created" observer sees the correct role.
        $user->role = 'customer';
        $user->save();

        // Create loyalty points record for new user (or get existing if somehow already exists)
        $loyaltyPoint = $user->loyaltyPoints()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'referral_code' => \App\Models\LoyaltyPoint::generateReferralCode($user),
                'current_tier' => 'bronze',
            ]
        );

        // Process signup bonus
        $loyaltyService = app(\App\Services\LoyaltyService::class);
        $loyaltyService->processSignupBonus($user);

        // Process referral if code is present (from form or session)
        $referralCode = $validated['referral_code'] ?? session('referral_code');
        if ($referralCode) {
            $referral = $loyaltyService->processReferralSignup($user, $referralCode);

            // Notify referrer about new signup (via Firebase push notification)
            if ($referral && $referral->referrer) {
                try {
                    $firebaseService = app(\App\Services\FirebaseNotificationService::class);
                    $firebaseService->sendToUsers(
                        [$referral->referrer->id],
                        '🎉 إحالة جديدة!',
                        "{$user->name} سجّل باستخدام رابط الإحالة الخاص بك! سيتم منحك النقاط عند أول طلب.",
                        [
                            'type' => 'referral_signup',
                            'referred_name' => $user->name,
                            'url' => route('loyalty.referral'),
                        ]
                    );
                } catch (\Exception $e) {
                    // Notification failure should not break registration
                    \Illuminate\Support\Facades\Log::warning('Failed to send referral notification: ' . $e->getMessage());
                }
            }

            // Clear session
            session()->forget('referral_code');
        }

        Auth::login($user);

        $successMessage = 'تم إنشاء حسابك بنجاح!';
        if ($referralCode && isset($referral)) {
            $rule = \App\Models\PointRule::active()->forTrigger('referral_signup')->first();
            if ($rule) {
                $successMessage .= " 🎁 حصلت على {$rule->value} نقطة مكافأة للتسجيل بإحالة!";
            }
        }

        return redirect()->route('home')->with('success', $successMessage);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Show the "forgot password" form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Email a password reset link.
     */
    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $status = PasswordBroker::sendResetLink($request->only('email'));

        if ($status === PasswordBroker::RESET_LINK_SENT) {
            return back()->with('success', 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'تعذّر إرسال رابط إعادة التعيين. تأكد من البريد الإلكتروني']);
    }

    /**
     * Show the reset-password form for a given token.
     */
    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Reset the password using a valid token.
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = PasswordBroker::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])
                    ->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === PasswordBroker::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'تم تغيير كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'فشل إعادة تعيين كلمة المرور. الرابط غير صالح أو منتهي الصلاحية']);
    }
}
