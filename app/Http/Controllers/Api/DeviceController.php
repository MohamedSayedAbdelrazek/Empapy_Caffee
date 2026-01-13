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

        // Check if token already exists
        $device = UserDevice::where('fcm_token', $request->token)->first();

        if ($device) {
            // Update existing device
            $device->update([
                'user_id' => $user->id,
                'is_active' => true,
                'last_used_at' => now(),
            ]);
        } else {
            // Create new device
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

        UserDevice::where('fcm_token', $request->token)->update(['is_active' => false]);

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
