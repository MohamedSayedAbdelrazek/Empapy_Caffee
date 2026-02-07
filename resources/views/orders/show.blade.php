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
            {{-- Success/Error Alerts --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" data-aos="fade-down">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" data-aos="fade-down">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            @endif

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
                                    <x-optimized-image :src="$item->product->image" :alt="$item->product_name" class="rounded"
                                        style="width: 70px; height: 70px; object-fit: cover;" />
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
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

                        {{-- Free Product Gift Display --}}
                        @if (isset($rewardRedemption) &&
                                $rewardRedemption &&
                                $rewardRedemption->reward &&
                                $rewardRedemption->reward->reward_type === 'free_product' &&
                                $rewardRedemption->gift_fulfilled &&
                                $rewardRedemption->gift_note)
                            <div class="alert alert-success py-2 px-3 mb-2"
                                style="background: rgba(16, 185, 129, 0.15); border: none;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-gift text-success" style="font-size: 1.2rem;"></i>
                                    <div>
                                        <small class="d-block text-success fw-bold">🎁 هدية مجانية</small>
                                        <span>{{ $rewardRedemption->gift_note }}</span>
                                    </div>
                                </div>
                            </div>
                        @elseif (isset($rewardRedemption) &&
                                $rewardRedemption &&
                                $rewardRedemption->reward &&
                                $rewardRedemption->reward->reward_type === 'free_product')
                            <div class="alert alert-warning py-2 px-3 mb-2"
                                style="background: rgba(245, 158, 11, 0.15); border: none;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-gift text-warning" style="font-size: 1.2rem;"></i>
                                    <span class="text-warning">🎁 هديتك المجانية قيد التجهيز...</span>
                                </div>
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

                        {{-- Cancel Order Button - Only show for cancellable orders --}}
                        @if ($order->canBeCancelled())
                            <button type="button" 
                                    class="btn btn-outline-danger w-100 mt-2"
                                    onclick="confirmCancelOrder('{{ route('orders.cancel', $order) }}', '{{ $order->order_number }}')">
                                <i class="bi bi-x-circle me-2"></i>إلغاء الطلب
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* Cancel button styles */
        .btn-outline-danger {
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.5);
            background: transparent;
        }

        .btn-outline-danger:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border-color: #ef4444;
        }

        [data-theme="dark"] .btn-outline-danger {
            color: #f87171;
            border-color: rgba(248, 113, 113, 0.4);
        }

        [data-theme="dark"] .btn-outline-danger:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border-color: rgba(248, 113, 113, 0.6);
        }

        /* Badge status styles */
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

@push('scripts')
    {{-- Cancel Order Confirmation --}}
    <script>
        function confirmCancelOrder(cancelUrl, orderNumber) {
            // Use SweetAlert2 if available, otherwise use native confirm
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'إلغاء الطلب؟',
                    html: `<p>هل أنت متأكد من إلغاء الطلب <strong>${orderNumber}</strong>؟</p><p class="text-muted small">لا يمكن التراجع عن هذا الإجراء.</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'نعم، إلغاء الطلب',
                    cancelButtonText: 'تراجع',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitCancelForm(cancelUrl);
                    }
                });
            } else {
                // Fallback to native confirm
                if (confirm(`هل أنت متأكد من إلغاء الطلب ${orderNumber}؟`)) {
                    submitCancelForm(cancelUrl);
                }
            }
        }

        function submitCancelForm(cancelUrl) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = cancelUrl;
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush
