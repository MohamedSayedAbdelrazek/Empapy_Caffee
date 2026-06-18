<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\ShippingZone;

class ShippingService
{
    /**
     * Single source of truth for shipping-fee resolution (QUAL-01). Used by the
     * checkout page, the AJAX shipping calculator, and order placement so all
     * three agree on the zone lookup, the default-fee fallback, and the
     * free-shipping threshold.
     *
     * @return array{fee: float, shipping: float, free_threshold: float, is_free: bool}
     */
    public function resolve(?string $governorate, float $subtotal): array
    {
        $freeThreshold = (float) Setting::get('shipping_free_threshold', 500);
        $defaultFee = (float) Setting::get('shipping_fee', 0);

        $fee = $defaultFee;
        if ($governorate) {
            $zone = ShippingZone::where('name', $governorate)->active()->first();
            $fee = $zone ? (float) $zone->fee : $defaultFee;
        }

        $shipping = $subtotal >= $freeThreshold ? 0.0 : $fee;

        return [
            'fee' => $fee,
            'shipping' => $shipping,
            'free_threshold' => $freeThreshold,
            'is_free' => $shipping == 0.0,
        ];
    }
}
