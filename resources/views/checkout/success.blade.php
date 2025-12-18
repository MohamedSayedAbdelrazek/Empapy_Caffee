@extends('layouts.app')

@section('title', 'تم الطلب بنجاح - إمبابي كافيه')

@section('content')
    <section class="py-5" style="min-height: 80vh; display: flex; align-items: center;">
        <div class="container">
            <div class="text-center" data-aos="fade-up">
                <div class="glass-card p-5 mx-auto" style="max-width: 600px;">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill display-1 text-success"></i>
                    </div>
                    <h2 class="mb-3">تم إنشاء طلبك بنجاح!</h2>
                    <p class="text-muted mb-4">
                        شكراً لك على طلبك من إمبابي كافيه. سيتم التواصل معك قريباً لتأكيد الطلب.
                    </p>

                    <div class="glass-card p-4 mb-4 text-end">
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">رقم الطلب</small>
                                <strong>{{ $order->order_number }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">الإجمالي</small>
                                <strong style="color: var(--gold);">{{ number_format($order->total) }} ج.م</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">طريقة الدفع</small>
                                <strong>الدفع عند الاستلام</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">الحالة</small>
                                <span class="badge bg-warning text-dark">قيد الانتظار</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('orders.track') }}?order_number={{ $order->order_number }}"
                            class="btn btn-golden">
                            <i class="bi bi-geo-alt me-2"></i>تتبع طلبك
                        </a>
                        <a href="{{ route('shop.index') }}" class="btn btn-outline-golden">
                            <i class="bi bi-bag me-2"></i>متابعة التسوق
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
