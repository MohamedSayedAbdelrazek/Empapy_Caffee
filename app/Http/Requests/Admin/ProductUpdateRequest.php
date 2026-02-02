<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'weight' => 'nullable|string|max:50',
            'roast_level' => 'nullable|in:light,medium,dark',
            'is_featured' => 'nullable',
            'is_active' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Only validate sale_price < price if sale_price has a value
            if ($this->filled('sale_price') && $this->filled('price')) {
                if ((float) $this->sale_price >= (float) $this->price) {
                    $validator->errors()->add('sale_price', 'سعر التخفيض يجب أن يكون أقل من السعر الأصلي');
                }
            }
        });
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'اسم المنتج',
            'category_id' => 'الصنف',
            'description' => 'الوصف',
            'price' => 'السعر',
            'sale_price' => 'سعر التخفيض',
            'weight' => 'الوزن',
            'roast_level' => 'درجة التحميص',
            'image' => 'صورة المنتج',
            'gallery' => 'الصور الإضافية',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم المنتج مطلوب',
            'category_id.required' => 'يجب اختيار صنف المنتج',
            'category_id.exists' => 'الصنف المختار غير موجود',
            'price.required' => 'سعر المنتج مطلوب',
            'price.min' => 'السعر يجب أن يكون أكبر من صفر',
            'sale_price.lt' => 'سعر التخفيض يجب أن يكون أقل من السعر الأصلي',
            'image.image' => 'الملف يجب أن يكون صورة',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
        ];
    }
}
