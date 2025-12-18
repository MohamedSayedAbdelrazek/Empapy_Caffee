@extends('layouts.app')

@section('title', 'تفاصيل الطلب - إمبابي كافيه')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">تفاصيل الطلب</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.my-orders') }}">طلباتي</a></li>
                    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Order Info -->
                <div class="col-lg-8">
                    <div class="glass-card p-4 mb-4" data-aos="fade-up">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5>عناصر الطلب</h5>
                            <span class="badge-status badge-{{ $order->status }} fs-6">{{ $order->status_ar }}</span>
                        </div>

                        @foreach ($order->items as $item)
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                                @if ($item->product)
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product_name_ar }}"
                                        class="rounded" style="width: 70px; height: 70px; object-fit: cover;">
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->product_name_ar }}</h6>
                                    <small class="text-muted">{{ $item->quantity }} × {{ number_format($item->price) }}
                                        ج.م</small>
                                </div>
                                <strong>{{ number_format($item->total) }} ج.م</strong>
                            </div>
                        @endforeach
                    </div>

                    <!-- Shipping Address -->
                    <div class="glass-card p-4" data-aos="fade-up">
                        <h5 class="mb-3"><i class="bi bi-geo-alt me-2"></i>عنوان التوصيل</h5>
                        <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                        <p class="mb-1">{{ $order->shipping_address }}</p>
                        <p class="mb-1">{{ $order->city }}{{ $order->governorate ? ' / ' . $order->governorate : '' }}
                        </p>
                        <p class="mb-0"><i class="bi bi-telephone me-1"></i>{{ $order->customer_phone }}</p>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="glass-card p-4 position-sticky" style="top: 100px;" data-aos="fade-up">
                        <h5 class="mb-4">ملخص الطلب</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>رقم الطلب</span>
                            <strong>{{ $order->order_number }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>تاريخ الطلب</span>
                            <span>{{ $order->created_at->format('Y/m/d') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>طريقة الدفع</span>
                            <span>{{ $order->payment_method === 'cash_on_delivery' ? 'عند الاستلام' : $order->payment_method }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>المجموع الفرعي</span>
                            <span>{{ number_format($order->subtotal) }} ج.م</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>التوصيل</span>
                            <span>{{ $order->shipping == 0 ? 'مجاني' : number_format($order->shipping) . ' ج.م' }}</span>
                        </div>
                        @if ($order->discount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span><i class="bi bi-tag me-1"></i>الخصم
                                    {{ $order->coupon_code ? '(' . $order->coupon_code . ')' : '' }}</span>
                                <span>- {{ number_format($order->discount) }} ج.م</span>
                            </div>
                        @endif

                        <hr>

                        <div class="d-flex justify-content-between">
                            <strong class="fs-5">الإجمالي</strong>
                            <strong class="fs-5" style="color: var(--gold);">{{ number_format($order->total) }}
                                ج.م</strong>
                        </div>

                        <a href="{{ route('orders.track') }}?order_number={{ $order->order_number }}"
                            class="btn btn-golden w-100 mt-4">
                            <i class="bi bi-geo-alt me-2"></i>تتبع الطلب
                        </a>

                        <a href="{{ route('orders.my-orders') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-arrow-right me-2"></i>العودة لطلباتي
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
