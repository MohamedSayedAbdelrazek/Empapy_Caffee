<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Validate a coupon code (AJAX)
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'order_total' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'كود الخصم غير صحيح']);
        }

        if (!$coupon->isValid()) {
            return response()->json(['valid' => false, 'message' => 'كود الخصم منتهي أو غير نشط']);
        }

        if ($coupon->min_order_amount && $request->order_total < $coupon->min_order_amount) {
            return response()->json([
                'valid' => false,
                'message' => 'الحد الأدنى للطلب ' . number_format($coupon->min_order_amount) . ' ج.م'
            ]);
        }

        $discount = $coupon->calculateDiscount($request->order_total);

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'message' => 'تم تطبيق الخصم: ' . number_format($discount) . ' ج.م'
        ]);
    }
}
