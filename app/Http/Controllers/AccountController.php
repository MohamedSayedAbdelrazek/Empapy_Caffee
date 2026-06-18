<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\UpdateAvatarRequest;
use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Http\Requests\Account\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{

    /**
     * Display account dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get user stats
        $stats = [
            'orders_count' => $user->orders()->count(),
            'pending_orders' => $user->orders()->pending()->count(),
            'completed_orders' => $user->orders()->completed()->count(),
            'points' => $user->points,
            'tier' => $user->tier,
        ];

        // Recent orders
        $recentOrders = $user->orders()->latest()->take(5)->get();

        return view('account.index', compact('user', 'stats', 'recentOrders'));
    }

    /**
     * Display profile edit page
     */
    public function profile()
    {
        $user = Auth::user();
        $shippingZones = \App\Models\ShippingZone::active()->ordered()->get();
        return view('account.profile', compact('user', 'shippingZones'));
    }

    /**
     * Update profile information
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        Auth::user()->update($request->validated());

        return back()->with('success', 'تم تحديث بياناتك بنجاح! ✨');
    }

    /**
     * Update password
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        Auth::user()->update([
            'password' => Hash::make($request->validated()['password']),
        ]);

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح! 🔐');
    }

    /**
     * Update avatar
     */
    public function updateAvatar(UpdateAvatarRequest $request)
    {
        try {
            $user = Auth::user();

            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');

            if (!$path) {
                throw new \Exception('فشل في حفظ الملف');
            }

            $user->update(['avatar' => $path]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تغيير الصورة بنجاح! 📸',
                    'avatar_url' => Storage::url($path),
                ]);
            }

            return back()->with('success', 'تم تغيير الصورة بنجاح! 📸');
        } catch (\Exception $e) {
            \Log::error('User avatar upload error: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Remove avatar
     */
    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return back()->with('success', 'تم حذف الصورة!');
    }
}
