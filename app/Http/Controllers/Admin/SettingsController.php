<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = Setting::getAllGrouped();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.*' => 'nullable',
        ]);

        foreach ($request->input('settings', []) as $group => $items) {
            foreach ($items as $key => $value) {
                $setting = Setting::where('key', $key)->first();

                if ($setting) {
                    Setting::set($key, $value, $setting->type, $setting->group);
                }
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
