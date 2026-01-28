<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

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
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'string', 'min:8', 'confirmed', Password::defaults()],
            'referral_code' => 'nullable|string|max:20'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'customer'
        ]);

        // Create loyalty points record for new user
        $loyaltyPoint = $user->loyaltyPoints()->create([
            'referral_code' => \App\Models\LoyaltyPoint::generateReferralCode($user),
            'current_tier' => 'bronze',
        ]);

        // Process signup bonus
        $loyaltyService = app(\App\Services\LoyaltyService::class);
        $loyaltyService->processSignupBonus($user);

        // Process referral if code is present (from form or session)
        $referralCode = $validated['referral_code'] ?? session('referral_code');
        if ($referralCode) {
            $referral = $loyaltyService->processReferralSignup($user, $referralCode);

            // Notify referrer about new signup
            if ($referral && $referral->referrer) {
                $referrer = $referral->referrer;

                // Send notification to referrer
                $referrer->notifications()->create([
                    'title' => '🎉 إحالة جديدة!',
                    'body' => "{$user->name} سجّل باستخدام رابط الإحالة الخاص بك! سيتم منحك النقاط عند أول طلب.",
                    'type' => 'referral',
                    'data' => json_encode([
                        'referred_name' => $user->name,
                        'referral_id' => $referral->id,
                    ]),
                ]);
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
}
