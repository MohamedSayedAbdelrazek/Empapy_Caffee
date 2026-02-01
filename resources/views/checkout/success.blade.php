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
                                <strong>
                                    @if ($order->payment_method === 'card')
                                        <i class="bi bi-credit-card me-1 text-primary"></i>بطاقة ائتمانية
                                    @else
                                        <i class="bi bi-cash-coin me-1 text-success"></i>الدفع عند الاستلام
                                    @endif
                                </strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">حالة الدفع</small>
                                @if ($order->payment_status === 'paid')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>مدفوع
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">قيد الانتظار</span>
                                @endif
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">الحالة</small>
                                <span class="badge bg-warning text-dark">قيد الانتظار</span>
                            </div>
                        </div>
                    </div>

                    {{-- Points Earned Preview --}}
                    @auth
                        @php
                            $pointRule = \App\Models\PointRule::active()->forTrigger('order_complete')->first();
                            $expectedPoints = $pointRule ? $pointRule->calculatePoints($order->total) : 0;
                        @endphp
                        @if($expectedPoints > 0)
                            <div class="glass-card p-3 mb-4 text-center"
                                style="background: linear-gradient(135deg, rgba(201, 162, 39, 0.1), rgba(201, 162, 39, 0.2)); border: 1px solid rgba(201, 162, 39, 0.3);">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-gift text-warning fs-4"></i>
                                    <div>
                                        <span class="text-muted">ستحصل على</span>
                                        <strong class="text-warning mx-1">{{ $expectedPoints }}</strong>
                                        <span class="text-muted">نقطة عند التسليم!</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endauth

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

@push('head')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
    <!-- Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Trigger confetti animation on page load
        window.addEventListener('load', function () {
            // Confetti configuration
            const duration = 3 * 1000; // 3 seconds
            const animationEnd = Date.now() + duration;
            const defaults = {
                startVelocity: 30,
                spread: 360,
                ticks: 60,
                zIndex: 9999
            };

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            const interval = setInterval(function () {
                const timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                const particleCount = 50 * (timeLeft / duration);

                // Fire confetti from both sides
                confetti({
                    ...defaults,
                    particleCount,
                    origin: {
                        x: randomInRange(0.1, 0.3),
                        y: Math.random() - 0.2
                    }
                });
                confetti({
                    ...defaults,
                    particleCount,
                    origin: {
                        x: randomInRange(0.7, 0.9),
                        y: Math.random() - 0.2
                    }
                });
            }, 250);

            @if ($order->payment_status === 'paid')
                // Show SweetAlert2 for successful payment
                setTimeout(function () {
                    Swal.fire({
                        title: '🎉 تم الدفع بنجاح!',
                        html: `
                                    <div class="text-center">
                                        <p class="mb-3">تم إتمام عملية الدفع بنجاح.</p>
                                        <div class="glass-card p-3 mb-3">
                                            <div class="row g-2">
                                                <div class="col-6 text-end">
                                                    <small class="text-muted">رقم الطلب:</small>
                                                </div>
                                                <div class="col-6 text-start">
                                                    <strong>{{ $order->order_number }}</strong>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <small class="text-muted">المبلغ المدفوع:</small>
                                                </div>
                                                <div class="col-6 text-start">
                                                    <strong style="color: var(--gold);">{{ number_format($order->total) }} ج.م</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-0">شكراً لثقتك في إمبابي كافيه ☕</p>
                                    </div>
                                `,
                        icon: 'success',
                        confirmButtonText: 'رائع!',
                        confirmButtonColor: '#C9A961',
                        showClass: {
                            popup: 'animate__animated animate__zoomIn'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__zoomOut'
                        }
                    });
                }, 1000);
            @endif
            });
    </script>
@endpush