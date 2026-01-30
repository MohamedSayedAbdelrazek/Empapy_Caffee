<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zones = ShippingZone::ordered()->get();
        return view('admin.shipping_zones.index', compact('zones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingZone $shippingZone)
    {
        $request->validate([
            'fee' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        $shippingZone->update([
            'fee' => $request->fee,
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'تم تحديث منطقة الشحن بنجاح');
    }
}
