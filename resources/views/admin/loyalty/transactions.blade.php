@extends('admin.layouts.app')

@section('title', 'سجل العمليات')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.dashboard') }}">نظام الولاء</a></li>
                        <li class="breadcrumb-item active">سجل العمليات</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">📜 سجل عمليات النقاط</h1>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="" method="GET" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">النوع</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">الكل</option>
                            <option value="earned" {{ request('type') === 'earned' ? 'selected' : '' }}>مكتسب</option>
                            <option value="redeemed" {{ request('type') === 'redeemed' ? 'selected' : '' }}>مستخدم</option>
                            <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>تعديل
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">المصدر</label>
                        <select name="source" class="form-select form-select-sm">
                            <option value="">الكل</option>
                            <option value="order" {{ request('source') === 'order' ? 'selected' : '' }}>طلب</option>
                            <option value="signup" {{ request('source') === 'signup' ? 'selected' : '' }}>تسجيل</option>
                            <option value="referral" {{ request('source') === 'referral' ? 'selected' : '' }}>إحالة</option>
                            <option value="review" {{ request('source') === 'review' ? 'selected' : '' }}>تقييم</option>
                            <option value="admin" {{ request('source') === 'admin' ? 'selected' : '' }}>إداري</option>
                            <option value="reward" {{ request('source') === 'reward' ? 'selected' : '' }}>مكافأة</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">من تاريخ</label>
                        <input type="date" name="from" class="form-control form-control-sm"
                            value="{{ request('from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">إلى تاريخ</label>
                        <input type="date" name="to" class="form-control form-control-sm"
                            value="{{ request('to') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-filter me-1"></i> تصفية
                        </button>
                        <a href="{{ route('admin.loyalty.transactions') }}" class="btn btn-outline-secondary btn-sm ms-2">
                            <i class="bi bi-x"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>المستخدم</th>
                                <th>النوع</th>
                                <th>المصدر</th>
                                <th>النقاط</th>
                                <th>الرصيد بعد</th>
                                <th>الوصف</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $t)
                                <tr>
                                    <td>{{ $t->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.loyalty.users.show', $t->user) }}">
                                            {{ $t->user->name ?? 'محذوف' }}
                                        </a>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $t->type === 'earned' ? 'success' : ($t->type === 'redeemed' ? 'warning' : 'secondary') }}">
                                            {{ $t->type_label }}
                                        </span>
                                    </td>
                                    <td>{{ $t->source_label }}</td>
                                    <td class="fw-bold {{ $t->is_positive ? 'text-success' : 'text-danger' }}">
                                        {{ $t->formatted_points }}
                                    </td>
                                    <td>{{ number_format($t->balance_after) }}</td>
                                    <td><small>{{ Str::limit($t->description, 30) }}</small></td>
                                    <td><small class="text-muted">{{ $t->created_at->format('Y/m/d H:i') }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        لا توجد عمليات
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($transactions->hasPages())
                <div class="card-footer bg-transparent">
                    {{ $transactions->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
