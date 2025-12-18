@extends('layouts.app')

@section('title', 'طلباتي - إمبابي كافيه')
@section('meta_description', 'عرض سجل الطلبات الخاصة بك في إمبابي كافيه')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">طلباتي</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">طلباتي</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            @if ($orders->count() > 0)
                <div class="row g-4">
                    @foreach ($orders as $order)
                        <div class="col-12" data-aos="fade-up">
                            <div class="glass-card p-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-1">{{ $order->order_number }}</h5>
                                        <small class="text-muted">{{ $order->created_at->format('Y/m/d - h:i A') }}</small>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="badge-status badge-{{ $order->status }}">
                                            {{ $order->status_ar }}
                                        </span>
                                        <strong style="color: var(--gold);">{{ number_format($order->total) }} ج.م</strong>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    <div class="flex-grow-1">
                                        <small class="text-muted">
                                            <i class="bi bi-box me-1"></i>{{ $order->items->count() }} منتجات
                                            @if ($order->coupon_code)
                                                <span class="ms-3 text-success">
                                                    <i class="bi bi-tag me-1"></i>{{ $order->coupon_code }}
                                                </span>
                                            @endif
                                        </small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye me-1"></i>التفاصيل
                                        </a>
                                        <a href="{{ route('orders.track') }}?order_number={{ $order->order_number }}"
                                            class="btn btn-sm btn-golden">
                                            <i class="bi bi-geo-alt me-1"></i>تتبع
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="glass-card text-center py-5" data-aos="fade-up">
                    <i class="bi bi-bag-x display-1 text-muted"></i>
                    <h4 class="mt-3">لا توجد طلبات</h4>
                    <p class="text-muted">لم تقم بأي طلبات بعد</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-golden mt-3">تسوق الآن</a>
                </div>
            @endif
        </div>
    </section>
@endsection
