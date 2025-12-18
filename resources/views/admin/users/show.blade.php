@extends('admin.layouts.app')

@section('title', 'تفاصيل العميل')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header-admin">
            <h1 class="page-title-admin">{{ $user->name }}</h1>
            <p class="page-subtitle-admin">تفاصيل العميل</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-right me-2"></i>العودة للعملاء
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="admin-card text-center mb-4">
                <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>

                <hr class="border-secondary">

                <div class="row text-center">
                    <div class="col-6">
                        <div class="stat-value" style="font-size: 1.5rem;">{{ $user->orders->count() }}</div>
                        <div class="stat-label">الطلبات</div>
                    </div>
                    <div class="col-6">
                        <div class="stat-value" style="font-size: 1.5rem; color: var(--admin-primary);">
                            {{ number_format($totalSpent) }}</div>
                        <div class="stat-label">إجمالي الإنفاق (ج.م)</div>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <h5 class="mb-4">معلومات الاتصال</h5>

                <div class="mb-3">
                    <p class="text-muted mb-1">الهاتف</p>
                    <p dir="ltr" class="text-end"><strong>{{ $user->phone ?? 'غير محدد' }}</strong></p>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">العنوان</p>
                    <p><strong>{{ $user->address ?? 'غير محدد' }}</strong></p>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">المدينة</p>
                    <p><strong>{{ $user->city ?? 'غير محدد' }}</strong></p>
                </div>

                <div>
                    <p class="text-muted mb-1">تاريخ التسجيل</p>
                    <p class="mb-0"><strong>{{ $user->created_at->format('Y/m/d H:i') }}</strong></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="admin-card">
                <h5 class="mb-4">آخر الطلبات</h5>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>الإجمالي</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ number_format($order->total) }} ج.م</td>
                                    <td>
                                        <span class="badge-status badge-{{ $order->status }}">
                                            {{ $order->status_ar }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('Y/m/d') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-light">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        لا توجد طلبات لهذا العميل
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
