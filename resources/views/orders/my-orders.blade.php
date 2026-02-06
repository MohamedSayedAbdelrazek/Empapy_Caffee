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
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye me-1"></i>التفاصيل
                                        </a>
                                        <a href="{{ route('orders.track') }}?order_number={{ $order->order_number }}"
                                            class="btn btn-sm btn-golden">
                                            <i class="bi bi-geo-alt me-1"></i>تتبع
                                        </a>
                                        @if ($order->canBeCancelled())
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmCancelOrder('{{ route('orders.cancel', $order) }}', '{{ $order->order_number }}')">
                                                <i class="bi bi-x-circle me-1"></i>إلغاء
                                            </button>
                                        @endif
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
                    <svg class="cart-icon-empty" xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960"
                        width="80px" fill="currentColor" style="opacity: 0.5;">
                        <path
                            d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
                    </svg>
                    <h4 class="mt-3">لا توجد طلبات</h4>
                    <p class="text-muted">لم تقم بأي طلبات بعد</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-golden mt-3">تسوق الآن</a>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* Badge status styles (in case not inherited) */
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

        /* Dark mode for buttons */
        [data-theme="dark"] .btn-outline-secondary {
            color: #9ca3af;
            border-color: rgba(255, 255, 255, 0.2);
        }

        [data-theme="dark"] .btn-outline-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.3);
        }

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
