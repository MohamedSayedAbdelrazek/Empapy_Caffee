<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\RewardRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /**
     * Validate a coupon or redemption code (AJAX)
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'order_total' => 'required|numeric|min:0'
        ]);

        $code = strtoupper(trim($request->code));

        // Check if it's a redemption code (starts with RWD-)
        if (str_starts_with($code, 'RWD-')) {
            return $this->validateRedemptionCode($code, $request->order_total);
        }

        // Regular coupon validation
        return $this->validateCouponCode($code, $request->order_total);
    }

    /**
     * Validate a redemption code
     */
    private function validateRedemptionCode(string $code, float $orderTotal)
    {
        if (!Auth::check()) {
            return response()->json(['valid' => false, 'message' => 'يجب تسجيل الدخول لاستخدام كود المكافأة']);
        }

        $redemption = RewardRedemption::with('reward')
            ->where('redemption_code', $code)
            ->where('status', 'pending')
            ->where('user_id', Auth::id())
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$redemption) {
            return response()->json(['valid' => false, 'message' => 'كود المكافأة غير صحيح أو منتهي الصلاحية']);
        }

        if (!$redemption->reward) {
            return response()->json(['valid' => false, 'message' => 'المكافأة غير متوفرة']);
        }

        $reward = $redemption->reward;
        $discount = 0;
        $message = '';

        switch ($reward->reward_type) {
            case 'discount_fixed':
                $discount = min($reward->reward_value, $orderTotal);
                $message = 'خصم ' . number_format($discount) . ' ج.م';
                break;
            case 'discount_percent':
                $discount = ($orderTotal * $reward->reward_value) / 100;
                if ($reward->max_discount && $discount > $reward->max_discount) {
                    $discount = $reward->max_discount;
                }
                $message = 'خصم ' . $reward->reward_value . '% = ' . number_format($discount) . ' ج.م';
                break;
            case 'free_shipping':
                $discount = 0; // Shipping will be handled separately
                $message = '🚚 شحن مجاني!';
                break;
            default:
                $message = 'مكافأة: ' . $reward->name;
        }

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'reward_type' => $reward->reward_type,
            'message' => 'تم تطبيق المكافأة: ' . $message
        ]);
    }

    /**
     * Validate a regular coupon code
     */
    private function validateCouponCode(string $code, float $orderTotal)
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'كود الخصم غير صحيح']);
        }

        if (!$coupon->isValid()) {
            return response()->json(['valid' => false, 'message' => 'كود الخصم منتهي أو غير نشط']);
        }

        if ($coupon->min_order_amount && $orderTotal < $coupon->min_order_amount) {
            return response()->json([
                'valid' => false,
                'message' => 'الحد الأدنى للطلب ' . number_format($coupon->min_order_amount) . ' ج.م'
            ]);
        }

        $discount = $coupon->calculateDiscount($orderTotal);

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'message' => 'تم تطبيق الخصم: ' . number_format($discount) . ' ج.م'
        ]);
    }
}
