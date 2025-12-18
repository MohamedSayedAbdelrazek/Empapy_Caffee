@extends('layouts.app')

@section('title', 'سلة التسوق - إمبابي كافيه')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">سلة التسوق</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">سلة التسوق</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            @if (count($cartItems) > 0)
                <div class="row g-5">
                    <!-- Cart Items -->
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="glass-card">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead class="border-bottom">
                                        <tr>
                                            <th style="width: 50%;">المنتج</th>
                                            <th>السعر</th>
                                            <th>الكمية</th>
                                            <th>المجموع</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cartItems as $item)
                                            <tr class="cart-item" data-product-id="{{ $item['product']->id }}">
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="{{ $item['product']->image }}"
                                                            alt="{{ $item['product']->name_ar }}" class="rounded"
                                                            style="width: 80px; height: 80px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-1">{{ $item['product']->name_ar }}</h6>
                                                            <small
                                                                class="text-muted">{{ $item['product']->category?->name_ar }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-bold">{{ number_format($item['product']->current_price) }}
                                                        ج.م</span>
                                                </td>
                                                <td>
                                                    <div class="quantity-input d-flex align-items-center">
                                                        <button type="button" class="btn btn-sm border qty-btn"
                                                            data-action="decrease">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                        <input type="number" value="{{ $item['quantity'] }}" min="1"
                                                            max="10"
                                                            class="form-control form-control-sm text-center border-0 qty-input"
                                                            style="width: 50px;">
                                                        <button type="button" class="btn btn-sm border qty-btn"
                                                            data-action="increase">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-bold item-subtotal">{{ number_format($item['subtotal']) }}
                                                        ج.م</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-danger remove-item-btn">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Continue Shopping -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('shop.index') }}" class="btn btn-outline-golden">
                                <i class="bi bi-arrow-right me-2"></i>متابعة التسوق
                            </a>
                            <button class="btn btn-outline-danger" id="clearCartBtn">
                                <i class="bi bi-trash me-2"></i>تفريغ السلة
                            </button>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="glass-card p-4 position-sticky" style="top: 100px;">
                            <h5 class="mb-4"><i class="bi bi-receipt me-2"></i>ملخص الطلب</h5>

                            <div class="d-flex justify-content-between mb-3">
                                <span>المجموع الفرعي</span>
                                <span class="fw-bold" id="cartSubtotal">{{ number_format($total) }} ج.م</span>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>التوصيل</span>
                                <span class="fw-bold text-success">
                                    @if ($total >= 500)
                                        مجاني
                                    @else
                                        50 ج.م
                                    @endif
                                </span>
                            </div>

                            @if ($total < 500)
                                <div class="alert alert-info small mb-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    أضف {{ number_format(500 - $total) }} ج.م للحصول على توصيل مجاني
                                </div>
                            @endif

                            <hr>

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fs-5">الإجمالي</span>
                                <span class="fs-4 fw-bold" style="color: var(--gold);" id="cartTotal">
                                    {{ number_format($total + ($total >= 500 ? 0 : 50)) }} ج.م
                                </span>
                            </div>

                            <a href="{{ route('checkout.index') }}" class="btn btn-golden w-100 btn-lg">
                                <i class="bi bi-credit-card me-2"></i>إتمام الطلب
                            </a>

                            <div class="text-center mt-4">
                                <small class="text-muted">
                                    <i class="bi bi-shield-check me-1"></i>دفع آمن 100%
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="text-center py-5" data-aos="fade-up">
                    <div class="glass-card p-5 mx-auto" style="max-width: 500px;">
                        <i class="bi bi-bag-x display-1 text-muted"></i>
                        <h3 class="mt-4">سلتك فارغة!</h3>
                        <p class="text-muted mb-4">لم تقم بإضافة أي منتجات إلى سلة التسوق بعد</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-golden btn-lg">
                            <i class="bi bi-bag-plus me-2"></i>تسوق الآن
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            // Quantity buttons
            document.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('.cart-item');
                    const input = row.querySelector('.qty-input');
                    const productId = row.dataset.productId;
                    let quantity = parseInt(input.value);

                    if (this.dataset.action === 'increase' && quantity < 10) {
                        quantity++;
                    } else if (this.dataset.action === 'decrease' && quantity > 1) {
                        quantity--;
                    }

                    input.value = quantity;
                    updateCartItem(productId, quantity);
                });
            });

            // Remove buttons
            document.querySelectorAll('.remove-item-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('.cart-item');
                    const productId = row.dataset.productId;
                    removeCartItem(productId, row);
                });
            });

            // Clear cart
            document.getElementById('clearCartBtn')?.addEventListener('click', function() {
                if (confirm('هل أنت متأكد من تفريغ السلة؟')) {
                    fetch('/cart/clear', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                }
            });

            function updateCartItem(productId, quantity) {
                fetch('/cart/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload(); // Simple reload to update totals
                        }
                    });
            }

            function removeCartItem(productId, row) {
                fetch('/cart/remove', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            row.remove();
                            showToast(data.message, 'success');
                            updateCartBadge();

                            // Reload if cart is empty
                            if (document.querySelectorAll('.cart-item').length === 0) {
                                location.reload();
                            }
                        }
                    });
            }
        });
    </script>
@endpush
