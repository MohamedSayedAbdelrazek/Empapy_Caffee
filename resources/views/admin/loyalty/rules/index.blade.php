@extends('admin.layouts.app')

@section('title', 'قواعد النقاط')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.dashboard') }}">نظام الولاء</a></li>
                        <li class="breadcrumb-item active">قواعد النقاط</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">⚙️ قواعد كسب النقاط</h1>
            </div>
            <a href="{{ route('admin.loyalty.rules.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> إضافة قاعدة جديدة
            </a>
        </div>

        <!-- Rules Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>القاعدة</th>
                                <th>الحدث</th>
                                <th>النوع</th>
                                <th>القيمة</th>
                                <th>الحالة</th>
                                <th>الفترة</th>
                                <th class="text-end">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rules as $rule)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $rule->name_ar }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $rule->slug }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $rule->trigger_label }}</span>
                                    </td>
                                    <td>{{ $rule->type_label }}</td>
                                    <td class="fw-bold text-warning">{{ $rule->value_display }}</td>
                                    <td>
                                        @if ($rule->is_valid)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($rule->starts_at || $rule->ends_at)
                                            <small class="text-muted">
                                                {{ $rule->starts_at?->format('Y/m/d') ?? 'بداية' }} -
                                                {{ $rule->ends_at?->format('Y/m/d') ?? 'مفتوح' }}
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.loyalty.rules.edit', $rule) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.loyalty.rules.destroy', $rule) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه القاعدة؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-3">لا توجد قواعد نقاط بعد</p>
                                        <a href="{{ route('admin.loyalty.rules.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-lg me-1"></i> إضافة قاعدة
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-info bg-opacity-10 border-0">
                <h6 class="mb-0"><i class="bi bi-lightbulb me-2 text-info"></i>شرح أنواع القواعد</h6>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h6 class="fw-bold">نقاط ثابتة (Fixed)</h6>
                        <p class="text-muted small mb-0">عدد ثابت من النقاط يُمنح عند تحقق الشرط. مثال: 50 نقطة عند التسجيل.
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="fw-bold">لكل 1 ج.م (Per Currency)</h6>
                        <p class="text-muted small mb-0">نقاط بناءً على قيمة الطلب. مثال: 1 نقطة لكل 1 ج.م = طلب 150 ج.م =
                            150 نقطة.</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="fw-bold">نسبة مئوية (Percentage)</h6>
                        <p class="text-muted small mb-0">نسبة من قيمة الطلب. مثال: 10% = طلب 200 ج.م = 20 نقطة.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
