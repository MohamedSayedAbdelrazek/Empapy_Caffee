<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        // The account routes are already behind the 'auth' middleware.
        return true;
    }

    public function rules(): array
    {
        return [
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.required' => 'يرجى اختيار صورة',
            'avatar.image' => 'الملف يجب أن يكون صورة',
            'avatar.mimes' => 'صيغة الصورة غير مدعومة',
            'avatar.max' => 'حجم الصورة يجب أن لا يتجاوز 5MB',
        ];
    }
}
