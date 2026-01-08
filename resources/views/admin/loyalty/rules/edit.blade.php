@extends('admin.layouts.app')

@section('title', 'تعديل قاعدة نقاط')

@section('content')
    <div class="container-fluid py-4">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.dashboard') }}">نظام الولاء</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.rules') }}">قواعد النقاط</a></li>
                    <li class="breadcrumb-item active">تعديل {{ $rule->name }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 mt-2">✏️ تعديل قاعدة: {{ $rule->name }}</h1>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.loyalty.rules.update', $rule) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <!-- ========== الحقول الأساسية ========== -->

                                <div class="col-md-6">
                                    <label class="form-label">حدث التفعيل <span class="text-danger">*</span></label>
                                    <select name="trigger" id="triggerSelect"
                                        class="form-select @error('trigger') is-invalid @enderror" required>
                                        <option value="order_complete"
                                            {{ old('trigger', $rule->trigger) === 'order_complete' ? 'selected' : '' }}>
                                            إتمام الطلب</option>
                                        <option value="signup"
                                            {{ old('trigger', $rule->trigger) === 'signup' ? 'selected' : '' }}>التسجيل
                                        </option>
                                        <option value="review"
                                            {{ old('trigger', $rule->trigger) === 'review' ? 'selected' : '' }}>كتابة تقييم
                                        </option>
                                        <option value="referral_made"
                                            {{ old('trigger', $rule->trigger) === 'referral_made' ? 'selected' : '' }}>إحالة
                                            ناجحة (للمُحيل)</option>
                                        <option value="referral_signup"
                                            {{ old('trigger', $rule->trigger) === 'referral_signup' ? 'selected' : '' }}>
                                            التسجيل بإحالة (للمُحال)</option>
                                        <option value="birthday"
                                            {{ old('trigger', $rule->trigger) === 'birthday' ? 'selected' : '' }}>عيد
                                            الميلاد</option>
                                    </select>
                                    @error('trigger')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- First Order Only (shows when trigger = order_complete) -->
                                <div class="col-md-6" id="firstOrderWrapper" style="display: none;">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_first_order_only"
                                            id="is_first_order_only" value="1"
                                            {{ old('is_first_order_only', $rule->is_first_order_only) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_first_order_only">
                                            <strong>تطبيق على أول طلب فقط</strong>
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        <i class="bi bi-info-circle me-1"></i>
                                        لو مفعل، العميل هياخد النقاط دي مرة واحدة بس على أول طلب ليه
                                    </small>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">الاسم <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $rule->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">نوع القاعدة <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="fixed"
                                            {{ old('type', $rule->type) === 'fixed' ? 'selected' : '' }}>نقاط ثابتة
                                        </option>
                                        <option value="per_currency"
                                            {{ old('type', $rule->type) === 'per_currency' ? 'selected' : '' }}>لكل 1 ج.م
                                        </option>
                                        <option value="percentage"
                                            {{ old('type', $rule->type) === 'percentage' ? 'selected' : '' }}>نسبة مئوية
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">القيمة <span class="text-danger">*</span></label>
                                    <input type="number" name="value" step="0.01" min="0"
                                        class="form-control @error('value') is-invalid @enderror"
                                        value="{{ old('value', $rule->value) }}" required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">عدد النقاط أو النسبة أو المضاعف</small>
                                </div>

                                <!-- Is Active -->
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            id="is_active" {{ old('is_active', $rule->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">نشط</label>
                                    </div>
                                </div>

                                <!-- ========== خيارات متقدمة ========== -->
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-header bg-transparent border-bottom py-2">
                                            <a class="text-decoration-none d-flex align-items-center justify-content-between"
                                                data-bs-toggle="collapse" href="#advancedOptions" role="button"
                                                aria-expanded="false" aria-controls="advancedOptions">
                                                <span>
                                                    <i class="bi bi-gear me-2"></i>
                                                    <strong>خيارات متقدمة</strong>
                                                    <small class="text-muted me-2">(اختياري)</small>
                                                </span>
                                                <i class="bi bi-chevron-down"></i>
                                            </a>
                                        </div>

                                        <div class="collapse" id="advancedOptions">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <!-- Min Order Amount -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">الحد الأدنى للطلب</label>
                                                        <div class="input-group">
                                                            <input type="number" name="min_order_amount" step="0.01"
                                                                min="0" class="form-control"
                                                                value="{{ old('min_order_amount', $rule->min_order_amount) }}">
                                                            <span class="input-group-text">ج.م</span>
                                                        </div>
                                                        <small class="text-muted">العميل لازم يطلب بأكتر من المبلغ
                                                            ده</small>
                                                    </div>

                                                    <!-- Max Points -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">الحد الأقصى للنقاط</label>
                                                        <input type="number" name="max_points_per_order" min="1"
                                                            class="form-control"
                                                            value="{{ old('max_points_per_order', $rule->max_points_per_order) }}">
                                                        <small class="text-muted">أقصى عدد نقاط للطلب الواحد</small>
                                                    </div>

                                                    <!-- Priority -->
                                                    <div class="col-md-4">
                                                        <label class="form-label">الأولوية</label>
                                                        <input type="number" name="priority" min="0"
                                                            class="form-control"
                                                            value="{{ old('priority', $rule->priority) }}">
                                                        <small class="text-muted">الأعلى يُطبق أولاً</small>
                                                    </div>

                                                    <!-- Start Date -->
                                                    <div class="col-md-4">
                                                        <label class="form-label">تاريخ البداية</label>
                                                        <input type="date" name="starts_at" class="form-control"
                                                            value="{{ old('starts_at', $rule->starts_at?->format('Y-m-d')) }}">
                                                        <small class="text-muted">للعروض الموسمية</small>
                                                    </div>

                                                    <!-- End Date -->
                                                    <div class="col-md-4">
                                                        <label class="form-label">تاريخ النهاية</label>
                                                        <input type="date" name="ends_at" class="form-control"
                                                            value="{{ old('ends_at', $rule->ends_at?->format('Y-m-d')) }}">
                                                    </div>

                                                    <!-- Description -->
                                                    <div class="col-12">
                                                        <label class="form-label">الوصف</label>
                                                        <textarea name="description" rows="2" class="form-control">{{ old('description', $rule->description) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i> حفظ التغييرات
                                </button>
                                <a href="{{ route('admin.loyalty.rules') }}" class="btn btn-outline-secondary">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const triggerSelect = document.getElementById('triggerSelect');
            const firstOrderWrapper = document.getElementById('firstOrderWrapper');

            function toggleFirstOrderOption() {
                if (triggerSelect.value === 'order_complete') {
                    firstOrderWrapper.style.display = 'block';
                } else {
                    firstOrderWrapper.style.display = 'none';
                }
            }

            triggerSelect.addEventListener('change', toggleFirstOrderOption);
            toggleFirstOrderOption(); // Run on page load
        });
    </script>
@endpush
