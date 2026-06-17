<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    /**
     * Register FCM token for current user
     */
    public function registerToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'device_type' => 'nullable|string|in:web,android,ios',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // SEC-06: scope to the current user. Look the token up globally only to
        // detect when it belongs to a *different* account — in that case we must
        // not reassign it (token takeover) nor hit the unique constraint.
        $device = UserDevice::where('fcm_token', $request->token)->first();

        if ($device && $device->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'This device token is registered to another account',
            ], 409);
        }

        if ($device) {
            // Token already belongs to this user — just reactivate / refresh it.
            $device->update([
                'is_active' => true,
                'last_used_at' => now(),
            ]);
        } else {
            // Create new device owned by the current user.
            $device = UserDevice::create([
                'user_id' => $user->id,
                'fcm_token' => $request->token,
                'device_type' => $request->device_type ?? 'web',
                'device_name' => $request->device_name,
                'is_active' => true,
                'last_used_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Device registered successfully',
            'device_id' => $device->id,
        ]);
    }

    /**
     * Unregister FCM token
     */
    public function unregisterToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // SEC-06: only the owner may deactivate their own token. Scoping by
        // user_id stops one user from disabling another user's push delivery.
        UserDevice::where('user_id', $user->id)
            ->where('fcm_token', $request->token)
            ->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Device unregistered successfully',
        ]);
    }

    /**
     * Get VAPID public key for web push
     */
    public function getVapidKey()
    {
        return response()->json([
            'vapid_key' => config('firebase.fcm.vapid_key'),
        ]);
    }
}
