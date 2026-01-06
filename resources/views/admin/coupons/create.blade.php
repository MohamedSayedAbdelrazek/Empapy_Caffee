@extends('admin.layouts.app')

@section('title', 'إضافة كوبون')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header-admin">
            <h1 class="page-title-admin">إضافة كوبون جديد</h1>
            <p class="page-subtitle-admin">إنشاء كوبون خصم جديد</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-right me-2"></i>العودة
        </a>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                <!-- Code -->
                <div class="col-md-6">
                    <label class="form-label">كود الكوبون *</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                        value="{{ old('code', strtoupper(Str::random(8))) }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-light opacity-75">يمكن للعملاء استخدام هذا الكود للحصول على الخصم</small>
                </div>

                <!-- Name -->
                <div class="col-md-6">
                    <label class="form-label">اسم الكوبون *</label>
                    <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror"
                        value="{{ old('name_ar') }}" required>
                    @error('name_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label class="form-label">الوصف</label>
                    <textarea name="description_ar" class="form-control" rows="2">{{ old('description_ar') }}</textarea>
                </div>

                <!-- Type -->
                <div class="col-md-6">
                    <label class="form-label">نوع الخصم *</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)
                        </option>
                        <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>مبلغ ثابت (ج.م)</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Value -->
                <div class="col-md-6">
                    <label class="form-label">قيمة الخصم *</label>
                    <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                        value="{{ old('value') }}" step="0.01" min="0" required>
                    @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Min Order Amount -->
                <div class="col-md-6">
                    <label class="form-label">الحد الأدنى للطلب (ج.م)</label>
                    <input type="number" name="min_order_amount" class="form-control"
                        value="{{ old('min_order_amount') }}" step="0.01" min="0">
                    <small class="text-light opacity-75">اتركه فارغاً إذا لم تريد حد أدنى</small>
                </div>

                <!-- Max Discount -->
                <div class="col-md-6">
                    <label class="form-label">الحد الأقصى للخصم (ج.م)</label>
                    <input type="number" name="max_discount" class="form-control" value="{{ old('max_discount') }}"
                        step="0.01" min="0">
                    <small class="text-light opacity-75">مفيد للخصومات النسبية</small>
                </div>

                <!-- Usage Limit -->
                <div class="col-md-6">
                    <label class="form-label">حد الاستخدام</label>
                    <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}"
                        min="1">
                    <small class="text-light opacity-75">عدد مرات استخدام الكوبون الإجمالي</small>
                </div>

                <!-- Starts At -->
                <div class="col-md-6">
                    <label class="form-label">تاريخ البداية</label>
                    <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                </div>

                <!-- Expires At -->
                <div class="col-md-6">
                    <label class="form-label">تاريخ الانتهاء</label>
                    <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                </div>

                <!-- Active -->
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">الكوبون نشط</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-admin-primary">
                    <i class="bi bi-check-lg me-2"></i>حفظ الكوبون
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </form>
    </div>
@endsection
