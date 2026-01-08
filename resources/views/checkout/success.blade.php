@extends('layouts.app')

@section('title', 'تم الطلب بنجاح - إمبابي كافيه')

@section('content')
    <!-- Page Header for proper navbar visibility -->
    <div class="page-header" style="padding: 120px 0 40px; background: linear-gradient(135deg, #2C1810 0%, #3D2317 100%);">
        <div class="container text-center text-white">
            <h1 class="page-title" data-aos="fade-up">🎉 تم الطلب بنجاح!</h1>
        </div>
    </div>

    <section class="py-5">
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
                            <svg class="cart-icon me-2" xmlns="http://www.w3.org/2000/svg" height="20px"
                                viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                <path
                                    d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
                            </svg>متابعة التسوق
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
