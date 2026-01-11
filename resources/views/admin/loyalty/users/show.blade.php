@extends('admin.layouts.app')

@section('title', 'تفاصيل العضو - ' . $user->name)

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.dashboard') }}">نظام الولاء</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.users') }}">الأعضاء</a></li>
                        <li class="breadcrumb-item active">{{ $user->name }}</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">👤 تفاصيل العضو</h1>
            </div>
            <a href="{{ route('admin.loyalty.users') }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-right me-1"></i> رجوع
            </a>
        </div>

        <div class="row g-4">
            <!-- User Info Card -->
            <div class="col-lg-4">
                <div class="admin-card text-center">
                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    @if ($loyalty)
                        @if ($loyalty->tier)
                            <span class="badge fs-6 mb-3" style="background: {{ $loyalty->tier->color ?? '#6c757d' }};">
                                {{ $loyalty->tier->icon }} {{ $loyalty->tier->name }}
                            </span>
                        @else
                            <span class="badge bg-secondary fs-6 mb-3">{{ $loyalty->current_tier }}</span>
                        @endif

                        <div class="border-top border-secondary pt-3 mt-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="fw-bold text-warning fs-4">{{ number_format($loyalty->available_points) }}
                                    </div>
                                    <small class="text-muted">متاح</small>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold text-success fs-4">{{ number_format($loyalty->total_earned) }}
                                    </div>
                                    <small class="text-muted">مكتسب</small>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold text-danger fs-4">{{ number_format($loyalty->total_redeemed) }}
                                    </div>
                                    <small class="text-muted">مستخدم</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">لا توجد نقاط لهذا المستخدم</p>
                    @endif
                </div>

                <!-- Adjust Points Card -->
                @if ($loyalty)
                    <div class="admin-card mt-4">
                        <h6 class="mb-3"><i class="bi bi-sliders me-2"></i>تعديل النقاط</h6>
                        <form action="{{ route('admin.loyalty.users.adjust', $user) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">النقاط</label>
                                <input type="number" name="points" class="form-control" placeholder="مثال: 100 أو -50"
                                    required>
                                <small class="text-muted">استخدم قيمة سالبة للخصم</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">السبب</label>
                                <input type="text" name="reason" class="form-control" placeholder="سبب التعديل"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> تطبيق
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Transactions & Redemptions -->
            <div class="col-lg-8">
                <!-- Recent Transactions -->
                <div class="admin-card mb-4">
                    <h5 class="mb-4"><i class="bi bi-clock-history me-2"></i>سجل العمليات</h5>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>النوع</th>
                                    <th>المصدر</th>
                                    <th>النقاط</th>
                                    <th>الرصيد بعد</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $t)
                                    <tr>
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
                                        <td><small class="text-muted">{{ $t->created_at->format('Y/m/d H:i') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">لا توجد عمليات</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($transactions->hasPages())
                        <div class="mt-3">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>

                <!-- Redemptions -->
                @if ($redemptions->count() > 0)
                    <div class="admin-card mb-4">
                        <h5 class="mb-4"><i class="bi bi-gift me-2"></i>المكافآت المستخدمة</h5>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>المكافأة</th>
                                        <th>النقاط</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($redemptions as $r)
                                        <tr>
                                            <td>
                                                <span class="fs-5 me-2">{{ $r->reward->icon ?? '🎁' }}</span>
                                                {{ $r->reward->name ?? 'مكافأة محذوفة' }}
                                            </td>
                                            <td class="text-warning fw-bold">{{ number_format($r->points_spent) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $r->status === 'used' ? 'success' : ($r->status === 'expired' ? 'danger' : 'warning') }}">
                                                    {{ $r->status_label ?? $r->status }}
                                                </span>
                                            </td>
                                            <td><small class="text-muted">{{ $r->created_at->format('Y/m/d') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Referrals -->
                @if ($referrals->count() > 0)
                    <div class="admin-card">
                        <h5 class="mb-4"><i class="bi bi-people me-2"></i>الإحالات</h5>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>المُحال</th>
                                        <th>الحالة</th>
                                        <th>المكافأة</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($referrals as $ref)
                                        <tr>
                                            <td>{{ $ref->referred->name ?? 'مستخدم محذوف' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $ref->status_color ?? 'secondary' }}">
                                                    {{ $ref->status_label ?? $ref->status }}
                                                </span>
                                            </td>
                                            <td class="text-success">+{{ number_format($ref->referrer_points ?? 0) }}</td>
                                            <td><small class="text-muted">{{ $ref->created_at->format('Y/m/d') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
