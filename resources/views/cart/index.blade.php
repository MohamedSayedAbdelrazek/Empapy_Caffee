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
                                <table class="table table-borderless align-middle mb-0 cart-table">
                                    <thead class="border-bottom d-none d-md-table-header-group">
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
                                            <tr class="cart-item cart-item-mobile" data-key="{{ $item['key'] }}">
                                                <td colspan="5" class="d-md-none p-3">
                                                    <!-- Mobile Card Layout -->
                                                    <div class="d-flex gap-3">
                                                        <x-optimized-image :src="$item['product']->image"
                                                            :alt="$item['product']->name" class="rounded"
                                                            style="width: 80px; height: 80px; object-fit: cover; flex-shrink: 0;" />
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">{{ $item['product']->name }}</h6>
                                                            <small
                                                                class="text-muted d-block mb-1">{{ $item['product']->category?->name }}</small>

                                                            @if (!empty($item['options']))
                                                                <div class="d-flex flex-wrap gap-1 mb-2">
                                                                    @foreach ($item['options'] as $option)
                                                                        <span class="badge bg-light text-dark border"
                                                                            style="font-size: 0.7rem;">
                                                                            {{ $option['label'] }}: {{ $option['value'] }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                                <span
                                                                    class="fw-bold text-warning">{{ number_format($item['price']) }}
                                                                    ج.م</span>
                                                                <div class="quantity-controls" data-key="{{ $item['key'] }}"
                                                                    style="transform: scale(0.85);">
                                                                    <button type="button" class="qty-btn-modern"
                                                                        data-action="decrease">
                                                                        <i class="bi bi-dash"></i>
                                                                    </button>
                                                                    <div class="qty-display">{{ $item['quantity'] }}</div>
                                                                    <button type="button" class="qty-btn-modern"
                                                                        data-action="increase">
                                                                        <i class="bi bi-plus"></i>
                                                                    </button>
                                                                    <div class="qty-loading">
                                                                        <div class="qty-spinner"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                                <span
                                                                    class="fw-bold item-subtotal">{{ number_format($item['subtotal']) }}
                                                                    ج.م</span>
                                                                <button class="btn btn-sm btn-outline-danger remove-item-btn">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <!-- Desktop Table Layout -->
                                                <td class="d-none d-md-table-cell">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <x-optimized-image :src="$item['product']->image"
                                                            :alt="$item['product']->name" class="rounded"
                                                            style="width: 80px; height: 80px; object-fit: cover;" />
                                                        <div>
                                                            <h6 class="mb-1">{{ $item['product']->name }}</h6>
                                                            <small
                                                                class="text-muted d-block mb-1">{{ $item['product']->category?->name }}</small>

                                                            @if (!empty($item['options']))
                                                                <div class="d-flex flex-wrap gap-1">
                                                                    @foreach ($item['options'] as $option)
                                                                        <span class="badge bg-light text-dark border">
                                                                            {{ $option['label'] }}: {{ $option['value'] }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                </td>
                                                <td class="d-none d-md-table-cell">
                                                    <span class="fw-bold">{{ number_format($item['price']) }}
                                                        ج.م</span>
                                                </td>
                                                <td class="d-none d-md-table-cell">
                                                    <div class="quantity-controls" data-key="{{ $item['key'] }}">
                                                        <button type="button" class="qty-btn-modern" data-action="decrease">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                        <div class="qty-display">{{ $item['quantity'] }}</div>
                                                        <button type="button" class="qty-btn-modern" data-action="increase">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                        <div class="qty-loading">
                                                            <div class="qty-spinner"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="d-none d-md-table-cell">
                                                    <span class="fw-bold item-subtotal">{{ number_format($item['subtotal']) }}
                                                        ج.م</span>
                                                </td>
                                                <td class="d-none d-md-table-cell">
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
                                    @if ($total >= $freeShippingThreshold)
                                        مجاني
                                    @else
                                        {{ $shippingFee }} ج.م
                                    @endif
                                </span>
                            </div>

                            @if ($total < $freeShippingThreshold)
                                <div class="alert alert-info small mb-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    أضف {{ number_format($freeShippingThreshold - $total) }} ج.م للحصول على توصيل مجاني
                                </div>
                            @endif

                            <hr>

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fs-5">الإجمالي</span>
                                <span class="fs-4 fw-bold" style="color: var(--gold);" id="cartTotal">
                                    {{ number_format($total + ($total >= $freeShippingThreshold ? 0 : $shippingFee)) }} ج.م
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
                        <svg class="cart-icon-empty" xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960"
                            width="80px" fill="currentColor" style="opacity: 0.5;">
                            <path
                                d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
                        </svg>
                        <h3 class="mt-4">سلتك فارغة!</h3>
                        <p class="text-muted mb-4">لم تقم بإضافة أي منتجات إلى سلة التسوق بعد</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-golden btn-lg">
                            <svg class="cart-icon me-2" xmlns="http://www.w3.org/2000/svg" height="20px"
                                viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                <path
                                    d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
                            </svg>تسوق الآن
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const FREE_SHIPPING_THRESHOLD = {{ $freeShippingThreshold }};
            const SHIPPING_FEE = {{ $shippingFee }};

            // Quantity buttons
            document.querySelectorAll('.qty-btn-modern').forEach(btn => {
                btn.addEventListener('click', function () {
                    const controls = this.closest('.quantity-controls');
                    const display = controls.querySelector('.qty-display');
                    const key = controls.dataset.key;
                    let quantity = parseInt(display.textContent);

                    if (this.dataset.action === 'increase' && quantity < 10) {
                        quantity++;
                    } else if (this.dataset.action === 'decrease' && quantity > 1) {
                        quantity--;
                    } else {
                        return; // Don't update if at limits
                    }

                    // Update display immediately for better UX
                    display.textContent = quantity;
                    updateCartItem(key, quantity, controls);
                });
            });

            // Remove buttons
            document.querySelectorAll('.remove-item-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const row = this.closest('.cart-item');
                    const key = row.dataset.key;
                    removeCartItem(key, row);
                });
            });

            // Clear cart
            document.getElementById('clearCartBtn')?.addEventListener('click', function () {
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

            function updateCartItem(key, quantity, controls) {
                // Add loading state
                controls.classList.add('loading');
                const buttons = controls.querySelectorAll('.qty-btn-modern');
                buttons.forEach(btn => btn.disabled = true);

                fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        key: key,
                        quantity: quantity
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove loading state
                            controls.classList.remove('loading');
                            controls.classList.add('success');
                            setTimeout(() => controls.classList.remove('success'), 400);
                            buttons.forEach(btn => btn.disabled = false);

                            // Update cart data dynamically without reload
                            updateCartDisplay(data.cart);
                        } else {
                            // Show error state
                            controls.classList.remove('loading');
                            controls.classList.add('error');
                            setTimeout(() => controls.classList.remove('error'), 400);
                            buttons.forEach(btn => btn.disabled = false);

                            // Revert quantity display
                            const display = controls.querySelector('.qty-display');
                            const row = controls.closest('.cart-item');
                            const currentQty = row.dataset.quantity || quantity;
                            display.textContent = currentQty;
                        }
                    })
                    .catch(error => {
                        console.error('Error updating cart:', error);
                        controls.classList.remove('loading');
                        controls.classList.add('error');
                        setTimeout(() => controls.classList.remove('error'), 400);
                        buttons.forEach(btn => btn.disabled = false);
                    });
            }

            function updateCartDisplay(cartData) {
                // Update each item's quantity and subtotal
                cartData.items.forEach(item => {
                    const rows = document.querySelectorAll(`[data-key="${item.key}"]`);
                    rows.forEach(row => {
                        // Update subtotal
                        const subtotals = row.querySelectorAll('.item-subtotal');
                        subtotals.forEach(subtotal => {
                            subtotal.classList.add('cart-total-updating');
                            subtotal.textContent = formatNumber(item.subtotal) + ' ج.م';
                            setTimeout(() => subtotal.classList.remove(
                                'cart-total-updating'), 300);
                        });

                        // Update quantity display in both mobile and desktop views
                        const qtyDisplays = row.querySelectorAll('.qty-display');
                        qtyDisplays.forEach(qtyDisplay => {
                            qtyDisplay.textContent = item.quantity;
                        });
                    });
                });

                // Update cart subtotal
                const subtotalEl = document.getElementById('cartSubtotal');
                if (subtotalEl) {
                    subtotalEl.classList.add('cart-total-updating');
                    subtotalEl.textContent = formatNumber(cartData.total) + ' ج.م';
                    setTimeout(() => subtotalEl.classList.remove('cart-total-updating'), 300);
                }

                // Update delivery fee and total
                const deliveryFee = cartData.total >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_FEE;
                const totalWithDelivery = cartData.total + deliveryFee;

                // Update delivery text
                const deliveryTextElements = document.querySelectorAll('.d-flex.justify-content-between.mb-3');
                deliveryTextElements.forEach(el => {
                    const spans = el.querySelectorAll('span');
                    if (spans[0] && spans[0].textContent.includes('التوصيل')) {
                        const deliverySpan = spans[1];
                        if (deliverySpan) {
                            deliverySpan.classList.add('cart-total-updating');
                            deliverySpan.innerHTML = deliveryFee === 0 ?
                                'مجاني' :
                                deliveryFee + ' ج.م';
                            deliverySpan.className = deliveryFee === 0 ? 'fw-bold text-success' : 'fw-bold';
                            setTimeout(() => deliverySpan.classList.remove('cart-total-updating'), 300);
                        }
                    }
                });

                // Update or remove free shipping alert
                const existingAlert = document.querySelector('.alert.alert-info');
                if (cartData.total < FREE_SHIPPING_THRESHOLD) {
                    const remaining = FREE_SHIPPING_THRESHOLD - cartData.total;
                    if (existingAlert) {
                        existingAlert.innerHTML = `
                                <i class="bi bi-info-circle me-2"></i>
                                أضف ${formatNumber(remaining)} ج.م للحصول على توصيل مجاني
                            `;
                    }
                } else if (existingAlert) {
                    existingAlert.style.display = 'none';
                }

                // Update final total
                const totalEl = document.getElementById('cartTotal');
                if (totalEl) {
                    totalEl.classList.add('cart-total-updating');
                    totalEl.textContent = formatNumber(totalWithDelivery) + ' ج.م';
                    setTimeout(() => totalEl.classList.remove('cart-total-updating'), 300);
                }
            }

            function formatNumber(num) {
                return new Intl.NumberFormat('ar-EG').format(num);
            }

            function removeCartItem(key, row) {
                // OPTIMISTIC UI: Fade out immediately
                row.style.transition = 'opacity 0.3s, transform 0.3s';
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';

                fetch('/cart/remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        key: key
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove row after animation
                            setTimeout(() => {
                                row.remove();

                                // Update totals from server response
                                if (data.cart) {
                                    updateCartDisplay(data.cart);
                                }

                                // Reload only if cart is empty
                                if (document.querySelectorAll('.cart-item').length === 0) {
                                    location.reload();
                                }
                            }, 300);

                            // Show success toast
                            if (window.Toast) {
                                window.Toast.success('تم الحذف', 'تم حذف المنتج من السلة');
                            }
                        } else {
                            // ROLLBACK: Show row again
                            row.style.opacity = '1';
                            row.style.transform = '';
                            if (window.Toast) {
                                window.Toast.error('خطأ', data.message || 'حدث خطأ أثناء الحذف');
                            }
                        }
                    })
                    .catch(error => {
                        // ROLLBACK: Show row again
                        row.style.opacity = '1';
                        row.style.transform = '';
                        console.error('Error removing item:', error);
                    });
            }
        });
    </script>
@endpush