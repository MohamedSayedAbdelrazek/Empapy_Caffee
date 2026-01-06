@extends('admin.layouts.app')

@section('title', 'إضافة قاعدة نقاط')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.dashboard') }}">نظام الولاء</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.rules') }}">قواعد النقاط</a></li>
                    <li class="breadcrumb-item active">إضافة قاعدة</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 mt-2">➕ إضافة قاعدة نقاط جديدة</h1>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.loyalty.rules.store') }}" method="POST">
                            @csrf

                            <div class="row g-4">
                                <!-- Slug -->
                                <div class="col-md-6">
                                    <label class="form-label">المعرف (Slug) <span class="text-danger">*</span></label>
                                    <input type="text" name="slug"
                                        class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}"
                                        placeholder="order_points" required>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">معرف فريد باللغة الإنجليزية</small>
                                </div>

                                <!-- Trigger -->
                                <div class="col-md-6">
                                    <label class="form-label">حدث التفعيل <span class="text-danger">*</span></label>
                                    <select name="trigger" class="form-select @error('trigger') is-invalid @enderror"
                                        required>
                                        <option value="">اختر...</option>
                                        <option value="order_complete"
                                            {{ old('trigger') === 'order_complete' ? 'selected' : '' }}>إتمام الطلب</option>
                                        <option value="signup" {{ old('trigger') === 'signup' ? 'selected' : '' }}>التسجيل
                                        </option>
                                        <option value="review" {{ old('trigger') === 'review' ? 'selected' : '' }}>كتابة
                                            تقييم</option>
                                        <option value="referral_made"
                                            {{ old('trigger') === 'referral_made' ? 'selected' : '' }}>إحالة ناجحة (للمُحيل)
                                        </option>
                                        <option value="referral_signup"
                                            {{ old('trigger') === 'referral_signup' ? 'selected' : '' }}>التسجيل بإحالة
                                            (للمُحال)</option>
                                        <option value="birthday" {{ old('trigger') === 'birthday' ? 'selected' : '' }}>عيد
                                            الميلاد</option>
                                    </select>
                                    @error('trigger')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Name -->
                                <div class="col-md-12">
                                    <label class="form-label">الاسم <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" placeholder="نقاط الطلب" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Type -->
                                <div class="col-md-6">
                                    <label class="form-label">نوع القاعدة <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>نقاط ثابتة
                                        </option>
                                        <option value="per_currency"
                                            {{ old('type', 'per_currency') === 'per_currency' ? 'selected' : '' }}>لكل 1
                                            ج.م</option>
                                        <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>
                                            نسبة مئوية</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Value -->
                                <div class="col-md-6">
                                    <label class="form-label">القيمة <span class="text-danger">*</span></label>
                                    <input type="number" name="value" step="0.01" min="0"
                                        class="form-control @error('value') is-invalid @enderror"
                                        value="{{ old('value', 1) }}" required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">عدد النقاط أو النسبة أو المضاعف</small>
                                </div>

                                <!-- Min Order Amount -->
                                <div class="col-md-6">
                                    <label class="form-label">الحد الأدنى للطلب</label>
                                    <div class="input-group">
                                        <input type="number" name="min_order_amount" step="0.01" min="0"
                                            class="form-control @error('min_order_amount') is-invalid @enderror"
                                            value="{{ old('min_order_amount') }}" placeholder="0">
                                        <span class="input-group-text">ج.م</span>
                                    </div>
                                    @error('min_order_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Max Points -->
                                <div class="col-md-6">
                                    <label class="form-label">الحد الأقصى للنقاط</label>
                                    <input type="number" name="max_points_per_order" min="1"
                                        class="form-control @error('max_points_per_order') is-invalid @enderror"
                                        value="{{ old('max_points_per_order') }}" placeholder="بدون حد">
                                    @error('max_points_per_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Priority -->
                                <div class="col-md-6">
                                    <label class="form-label">الأولوية</label>
                                    <input type="number" name="priority" min="0"
                                        class="form-control @error('priority') is-invalid @enderror"
                                        value="{{ old('priority', 0) }}">
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">الأعلى يُطبق أولاً</small>
                                </div>

                                <!-- Dates -->
                                <div class="col-md-6">
                                    <label class="form-label">تاريخ البداية</label>
                                    <input type="date" name="starts_at"
                                        class="form-control @error('starts_at') is-invalid @enderror"
                                        value="{{ old('starts_at') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">تاريخ النهاية</label>
                                    <input type="date" name="ends_at"
                                        class="form-control @error('ends_at') is-invalid @enderror"
                                        value="{{ old('ends_at') }}">
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label class="form-label">الوصف</label>
                                    <textarea name="description" rows="2" class="form-control @error('description') is-invalid @enderror"
                                        placeholder="وصف اختياري للقاعدة">{{ old('description') }}</textarea>
                                </div>

                                <!-- Is Active -->
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">نشط</label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i> حفظ القاعدة
                                </button>
                                <a href="{{ route('admin.loyalty.rules') }}" class="btn btn-outline-secondary">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-header bg-warning bg-opacity-10 border-0">
                        <h6 class="mb-0"><i class="bi bi-eye me-2"></i>معاينة</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="fs-1 mb-3">🎯</div>
                        <p class="mb-2">عندما يقوم العميل بـ:</p>
                        <p class="h5 fw-bold text-info mb-3" id="previewTrigger">إتمام الطلب</p>
                        <p class="mb-2">سيحصل على:</p>
                        <p class="h3 fw-bold text-warning" id="previewValue">1 نقطة لكل 1 ج.م</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
