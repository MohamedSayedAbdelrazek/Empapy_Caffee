@extends('layouts.app')

@section('title', 'حسابي - إمبابي كافيه')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="padding: 120px 0 50px; background: linear-gradient(135deg, #2C1810 0%, #3D2317 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-4" data-aos="fade-up">
                        <!-- User Avatar -->
                        <div class="account-avatar-lg">
                            @if ($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                            @else
                                <i class="bi bi-person-fill"></i>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-white mb-2">مرحباً، {{ $user->name }}! 👋</h1>
                            <div class="d-flex flex-wrap gap-3">
                                <span class="badge bg-gold text-dark px-3 py-2">
                                    <i class="bi bi-person-check me-1"></i>عميل منذ {{ $user->created_at->format('Y/m') }}
                                </span>
                                @if ($stats['tier'])
                                    <span class="badge px-3 py-2"
                                        style="background: {{ $stats['tier']->color }}20; color: {{ $stats['tier']->color }}; border: 1px solid {{ $stats['tier']->color }}40;">
                                        {{ $stats['tier']->icon }} {{ $stats['tier']->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-4 mt-md-0" data-aos="fade-up" data-aos-delay="100">
                    <a href="{{ route('account.profile') }}" class="btn btn-golden">
                        <i class="bi bi-gear me-2"></i>تعديل الملف الشخصي
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5" style="background: var(--cream); min-height: 60vh;">
        <div class="container">
            <!-- Stats Row -->
            <div class="row g-4 mb-5" data-aos="fade-up">
                <div class="col-6 col-lg-3">
                    <div class="glass-card p-4 text-center h-100">
                        <div class="account-stat-icon bg-success-subtle text-success mb-3">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <h3 class="fw-bold mb-1">{{ $stats['orders_count'] }}</h3>
                        <p class="text-muted mb-0 small">إجمالي الطلبات</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="glass-card p-4 text-center h-100">
                        <div class="account-stat-icon bg-warning-subtle text-warning mb-3">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <h3 class="fw-bold mb-1">{{ $stats['pending_orders'] }}</h3>
                        <p class="text-muted mb-0 small">طلبات قيد التنفيذ</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="glass-card p-4 text-center h-100">
                        <div class="account-stat-icon bg-primary-subtle text-primary mb-3">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <h3 class="fw-bold mb-1">{{ $stats['completed_orders'] }}</h3>
                        <p class="text-muted mb-0 small">طلبات مكتملة</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="glass-card p-4 text-center h-100">
                        <div class="account-stat-icon mb-3" style="background: rgba(201, 162, 39, 0.15); color: #c9a227;">
                            <i class="bi bi-coin"></i>
                        </div>
                        <h3 class="fw-bold mb-1" style="color: #c9a227;">{{ number_format($stats['points']) }}</h3>
                        <p class="text-muted mb-0 small">نقاط الولاء</p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Quick Actions -->
                <div class="col-lg-4" data-aos="fade-up">
                    <div class="glass-card p-4 h-100">
                        <h5 class="mb-4"><i class="bi bi-lightning-charge me-2" style="color: var(--gold);"></i>إجراءات
                            سريعة</h5>
                        <div class="d-grid gap-3">
                            <a href="{{ route('orders.my-orders') }}" class="account-quick-link">
                                <i class="bi bi-receipt"></i>
                                <span>طلباتي</span>
                                <i class="bi bi-chevron-left ms-auto"></i>
                            </a>
                            <a href="{{ route('loyalty.index') }}" class="account-quick-link">
                                <i class="bi bi-award"></i>
                                <span>نقاط الولاء</span>
                                <i class="bi bi-chevron-left ms-auto"></i>
                            </a>
                            <a href="{{ route('loyalty.rewards') }}" class="account-quick-link">
                                <i class="bi bi-gift"></i>
                                <span>المكافآت</span>
                                <i class="bi bi-chevron-left ms-auto"></i>
                            </a>
                            <a href="{{ route('wishlist.index') }}" class="account-quick-link">
                                <i class="bi bi-heart"></i>
                                <span>المفضلة</span>
                                <i class="bi bi-chevron-left ms-auto"></i>
                            </a>
                            <a href="{{ route('account.profile') }}" class="account-quick-link">
                                <i class="bi bi-person-gear"></i>
                                <span>تعديل الملف الشخصي</span>
                                <i class="bi bi-chevron-left ms-auto"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="glass-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0"><i class="bi bi-clock-history me-2" style="color: var(--gold);"></i>آخر
                                الطلبات</h5>
                            <a href="{{ route('orders.my-orders') }}" class="btn btn-sm btn-outline-golden">عرض الكل</a>
                        </div>

                        @if ($recentOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle">
                                    <thead class="border-bottom">
                                        <tr>
                                            <th>رقم الطلب</th>
                                            <th>التاريخ</th>
                                            <th>الحالة</th>
                                            <th>الإجمالي</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentOrders as $order)
                                            <tr>
                                                <td>
                                                    <strong>{{ $order->order_number }}</strong>
                                                </td>
                                                <td class="text-muted">{{ $order->created_at->format('Y/m/d') }}</td>
                                                <td>
                                                    <span class="badge-status badge-{{ $order->status }}">
                                                        {{ $order->status_ar }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong style="color: var(--gold);">{{ number_format($order->total) }}
                                                        ج.م</strong>
                                                </td>
                                                <td>
                                                    <a href="{{ route('orders.show', $order) }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-receipt fs-1 d-block mb-3"></i>
                                <p>لم تقم بأي طلبات بعد</p>
                                <a href="{{ route('shop.index') }}" class="btn btn-golden mt-2">تسوق الآن</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .account-avatar-lg {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--medium-roast) 0%, var(--dark-roast) 100%);
            border: 4px solid rgba(201, 162, 39, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .account-avatar-lg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .account-avatar-lg i {
            font-size: 48px;
            color: #c9a227;
        }

        .bg-gold {
            background: linear-gradient(135deg, #c9a227 0%, #e8c547 100%);
        }

        .account-stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto;
        }

        .account-quick-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: rgba(0, 0, 0, 0.02);
            border-radius: 12px;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }

        .account-quick-link:hover {
            background: rgba(201, 162, 39, 0.1);
            color: var(--espresso);
            transform: translateX(-5px);
        }

        .account-quick-link i:first-child {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(201, 162, 39, 0.1);
            border-radius: 10px;
            color: #c9a227;
            font-size: 18px;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-pending {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }

        .badge-processing {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
        }

        .badge-shipped {
            background: rgba(99, 102, 241, 0.15);
            color: #6366f1;
        }

        .badge-delivered {
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
        }

        .badge-cancelled {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }
    </style>
@endpush
