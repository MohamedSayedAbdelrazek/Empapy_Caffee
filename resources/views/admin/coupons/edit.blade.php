@extends('admin.layouts.app')

@section('title', 'تعديل الكوبون')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header-admin">
            <h1 class="page-title-admin">تعديل الكوبون</h1>
            <p class="page-subtitle-admin">{{ $coupon->name }}</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-right me-2"></i>العودة
        </a>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Code -->
                <div class="col-md-6">
                    <label class="form-label">كود الكوبون *</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                        value="{{ old('code', $coupon->code) }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Name -->
                <div class="col-md-6">
                    <label class="form-label">اسم الكوبون *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $coupon->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $coupon->description) }}</textarea>
                </div>

                <!-- Type -->
                <div class="col-md-6">
                    <label class="form-label">نوع الخصم *</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>نسبة
                            مئوية (%)</option>
                        <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>مبلغ ثابت
                            (ج.م)</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Value -->
                <div class="col-md-6">
                    <label class="form-label">قيمة الخصم *</label>
                    <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                        value="{{ old('value', $coupon->value) }}" step="0.01" min="0" required>
                    @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Min Order Amount -->
                <div class="col-md-6">
                    <label class="form-label">الحد الأدنى للطلب (ج.م)</label>
                    <input type="number" name="min_order_amount" class="form-control"
                        value="{{ old('min_order_amount', $coupon->min_order_amount) }}" step="0.01" min="0">
                </div>

                <!-- Max Discount -->
                <div class="col-md-6">
                    <label class="form-label">الحد الأقصى للخصم (ج.م)</label>
                    <input type="number" name="max_discount" class="form-control"
                        value="{{ old('max_discount', $coupon->max_discount) }}" step="0.01" min="0">
                </div>

                <!-- Usage Limit -->
                <div class="col-md-6">
                    <label class="form-label">حد الاستخدام</label>
                    <input type="number" name="usage_limit" class="form-control"
                        value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1">
                    <small class="text-light opacity-75">تم الاستخدام: {{ $coupon->usage_count }} مرة</small>
                </div>

                <!-- Starts At -->
                <div class="col-md-6">
                    <label class="form-label">تاريخ البداية</label>
                    <input type="datetime-local" name="starts_at" class="form-control"
                        value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}">
                </div>

                <!-- Expires At -->
                <div class="col-md-6">
                    <label class="form-label">تاريخ الانتهاء</label>
                    <input type="datetime-local" name="expires_at" class="form-control"
                        value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}">
                </div>

                <!-- Active -->
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1"
                            {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">الكوبون نشط</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-admin-primary">
                    <i class="bi bi-check-lg me-2"></i>تحديث الكوبون
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </form>
    </div>
@endsection
