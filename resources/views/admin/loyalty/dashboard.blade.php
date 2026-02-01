@extends('admin.layouts.app')

@section('title', 'نظام الولاء والنقاط')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">🎖️ نظام الولاء والنقاط</h1>
                <p class="text-muted mb-0">إدارة كاملة لبرنامج الولاء والمكافآت</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.loyalty.rules') }}" class="btn btn-outline-primary">
                    <i class="bi bi-gear me-1"></i> قواعد النقاط
                </a>
                <a href="{{ route('admin.loyalty.rewards') }}" class="btn btn-outline-primary">
                    <i class="bi bi-gift me-1"></i> المكافآت
                </a>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row g-4 mb-4">
            <!-- Total Points Issued -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stat-icon bg-warning-subtle text-warning rounded-circle p-3">
                                    <i class="bi bi-coin fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">إجمالي النقاط الصادرة</h6>
                                <h3 class="mb-0 fw-bold text-warning">{{ number_format($stats['total_points_issued']) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Points Redeemed -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stat-icon bg-success-subtle text-success rounded-circle p-3">
                                    <i class="bi bi-arrow-down-circle fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">النقاط المستخدمة</h6>
                                <h3 class="mb-0 fw-bold text-success">{{ number_format($stats['total_points_redeemed']) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Points in Circulation -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stat-icon bg-primary-subtle text-primary rounded-circle p-3">
                                    <i class="bi bi-graph-up fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">النقاط المتداولة</h6>
                                <h3 class="mb-0 fw-bold text-primary">{{ number_format($stats['points_in_circulation']) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stat-icon bg-info-subtle text-info rounded-circle p-3">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">الأعضاء النشطون</h6>
                                <h3 class="mb-0 fw-bold text-info">{{ number_format($stats['active_users']) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Stats -->
        <div class="row g-4 mb-4">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0"><i class="bi bi-calendar-month me-2"></i>إحصائيات الشهر</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <span class="text-muted">نقاط مكتسبة هذا الشهر</span>
                                    <span
                                        class="fw-bold text-success fs-5">+{{ number_format($stats['monthly_earned']) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <span class="text-muted">نقاط مستخدمة هذا الشهر</span>
                                    <span
                                        class="fw-bold text-danger fs-5">-{{ number_format($stats['monthly_redeemed']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions & Top Earners -->
        <div class="row g-4">
            <!-- Recent Transactions -->
            <div class="col-lg-7">
                <div class="admin-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>آخر العمليات</h5>
                        <a href="{{ route('admin.loyalty.transactions') }}" class="btn btn-sm btn-outline-light">
                            عرض الكل
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>المستخدم</th>
                                    <th>النوع</th>
                                    <th>النقاط</th>
                                    <th>المصدر</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 35px; height: 35px; font-size: 0.85rem;">
                                                    {{ mb_substr($transaction->user->name ?? 'U', 0, 1) }}
                                                </div>
                                                <span>{{ $transaction->user->name ?? 'مستخدم محذوف' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $transaction->type === 'earned' ? 'success' : ($transaction->type === 'redeemed' ? 'warning' : 'secondary') }}">
                                                {{ $transaction->type_label }}
                                            </span>
                                        </td>
                                        <td class="fw-bold {{ $transaction->is_positive ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->formatted_points }}
                                        </td>
                                        <td>{{ $transaction->source_label }}</td>
                                        <td><small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">لا توجد عمليات بعد</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top Earners & Recent Referrals -->
            <div class="col-lg-5">
                <!-- Top Earners -->
                <div class="admin-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0"><i class="bi bi-trophy me-2 text-warning"></i>أكثر الأعضاء نقاطاً</h5>
                        <a href="{{ route('admin.loyalty.users') }}" class="btn btn-sm btn-outline-light">إدارة</a>
                    </div>
                    <div class="list-group list-group-flush" style="background: transparent;">
                        @forelse($topEarners as $index => $earner)
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-secondary">
                                <div class="d-flex align-items-center">
                                    <span
                                        class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }} me-2">{{ $index + 1 }}</span>
                                    <div>
                                        <span class="fw-medium">{{ $earner->user->name ?? 'مستخدم محذوف' }}</span>
                                    </div>
                                </div>
                                <span class="fw-bold text-warning">{{ number_format($earner->total_earned) }}
                                    نقطة</span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">لا يوجد أعضاء بعد</div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Referrals -->
                <div class="admin-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0"><i class="bi bi-people me-2 text-info"></i>آخر الإحالات</h5>
                    </div>
                    <div class="list-group list-group-flush" style="background: transparent;">
                        @forelse($recentReferrals as $referral)
                            <div class="py-3 border-bottom border-secondary">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $referral->referrer->name ?? 'محذوف' }}</strong>
                                        <i class="bi bi-arrow-left mx-2"></i>
                                        <span>{{ $referral->referred->name ?? 'محذوف' }}</span>
                                    </div>
                                    <span class="badge bg-{{ $referral->status_color }}">{{ $referral->status_label }}</span>
                                </div>
                                <small class="text-muted">{{ $referral->created_at->diffForHumans() }}</small>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">لا توجد إحالات بعد</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stat-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection