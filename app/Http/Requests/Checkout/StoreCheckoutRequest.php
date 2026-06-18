<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Checkout is a public route (guest checkout is allowed).
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'governorate' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash_on_delivery', // Online payment coming soon
            'coupon_code' => 'nullable|string|max:50',
        ];
    }
}
