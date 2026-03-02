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
                                                        <x-optimized-image :src="$item['product']->image" :alt="$item['product']->name"
                                                            class="rounded"
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

                                                            <div
                                                                class="d-flex justify-content-between align-items-center mt-2">
                                                                <span
                                                                    class="fw-bold text-warning">{{ number_format($item['price']) }}
                                                                    ج.م</span>
                                                                <div class="quantity-controls"
                                                                    data-key="{{ $item['key'] }}"
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
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mt-2">
                                                                <span
                                                                    class="fw-bold item-subtotal">{{ number_format($item['subtotal']) }}
                                                                    ج.م</span>
                                                                <button
                                                                    class="btn btn-sm btn-outline-danger remove-item-btn">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <!-- Desktop Table Layout -->
                                                <td class="d-none d-md-table-cell">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <x-optimized-image :src="$item['product']->image" :alt="$item['product']->name"
                                                            class="rounded"
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
                                                </td>
                                                <td class="d-none d-md-table-cell">
                                                    <span
                                                        class="fw-bold item-subtotal">{{ number_format($item['subtotal']) }}
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

                            {{-- Smart Shipping Estimator --}}
                            <div class="shipping-estimator mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>التوصيل</span>
                                    <span class="fw-bold" id="shippingFeeDisplay">
                                        @if ($total >= $freeShippingThreshold)
                                            <span class="text-success">مجاني 🎉</span>
                                        @else
                                            <span class="shipping-range-badge">{{ number_format($minFee) }} -
                                                {{ number_format($maxFee) }} ج.م</span>
                                        @endif
                                    </span>
                                </div>

                                {{-- Governorate Selector Toggle --}}
                                @if ($total < $freeShippingThreshold)
                                    <div class="gov-selector-wrapper" id="govSelectorWrapper">
                                        <button type="button" class="btn btn-gov-selector w-100" id="govToggleBtn"
                                            onclick="toggleGovSelector()">
                                            <span class="gov-btn-content">
                                                <i class="bi bi-geo-alt-fill"></i>
                                                <span id="govBtnText">📍 حدد محافظتك لمعرفة سعر التوصيل</span>
                                            </span>
                                            <i class="bi bi-chevron-down gov-chevron" id="govChevron"></i>
                                        </button>

                                        <div class="gov-dropdown-container" id="govDropdownContainer">
                                            <select class="form-select gov-select" id="cartGovernorateSelect"
                                                onchange="updateCartShipping()">
                                                <option value="">اختر المحافظة...</option>
                                                @foreach ($shippingZones as $zone)
                                                    <option value="{{ $zone->name }}" data-fee="{{ $zone->fee }}">
                                                        {{ $zone->name }} — {{ number_format($zone->fee) }} ج.م
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                {{-- Free Shipping Progress --}}
                                @if ($total < $freeShippingThreshold)
                                    <div class="free-shipping-hint mt-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="text-muted">
                                                <i class="bi bi-truck me-1"></i>
                                                أضف <strong
                                                    style="color: var(--gold);">{{ number_format($freeShippingThreshold - $total) }}
                                                    ج.م</strong> للتوصيل المجاني
                                            </small>
                                            <small
                                                class="text-muted">{{ number_format(min(100, ($total / $freeShippingThreshold) * 100), 0) }}%</small>
                                        </div>
                                        <div class="progress shipping-progress" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ min(100, ($total / $freeShippingThreshold) * 100) }}%; background: linear-gradient(90deg, var(--gold), #e8c547);"
                                                aria-valuenow="{{ $total }}" aria-valuemin="0"
                                                aria-valuemax="{{ $freeShippingThreshold }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Shipping Estimator Styles --}}
                            <style>
                                .shipping-range-badge {
                                    background: linear-gradient(135deg, rgba(201, 162, 39, 0.15), rgba(232, 197, 71, 0.1));
                                    color: var(--gold);
                                    padding: 4px 12px;
                                    border-radius: 20px;
                                    font-size: 0.9rem;
                                    font-weight: 600;
                                    border: 1px solid rgba(201, 162, 39, 0.25);
                                    display: inline-block;
                                    animation: shimmer 3s ease-in-out infinite;
                                }

                                @keyframes shimmer {

                                    0%,
                                    100% {
                                        opacity: 1;
                                    }

                                    50% {
                                        opacity: 0.75;
                                    }
                                }

                                .btn-gov-selector {
                                    background: rgba(201, 162, 39, 0.08);
                                    border: 1.5px dashed rgba(201, 162, 39, 0.35);
                                    border-radius: 12px;
                                    padding: 10px 16px;
                                    color: var(--text-color, #333);
                                    font-size: 0.85rem;
                                    display: flex;
                                    align-items: center;
                                    justify-content: space-between;
                                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                    cursor: pointer;
                                }

                                .btn-gov-selector:hover {
                                    background: rgba(201, 162, 39, 0.15);
                                    border-color: var(--gold);
                                    transform: translateY(-1px);
                                    box-shadow: 0 4px 12px rgba(201, 162, 39, 0.15);
                                }

                                .btn-gov-selector.active {
                                    border-style: solid;
                                    border-color: var(--gold);
                                    background: rgba(201, 162, 39, 0.12);
                                }

                                .gov-btn-content {
                                    display: flex;
                                    align-items: center;
                                    gap: 8px;
                                }

                                .gov-btn-content i {
                                    color: var(--gold);
                                    font-size: 1rem;
                                }

                                .gov-chevron {
                                    transition: transform 0.3s ease;
                                    color: var(--gold);
                                    font-size: 0.8rem;
                                }

                                .gov-chevron.rotated {
                                    transform: rotate(180deg);
                                }

                                .gov-dropdown-container {
                                    max-height: 0;
                                    overflow: hidden;
                                    transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease, margin 0.3s ease;
                                    opacity: 0;
                                    margin-top: 0;
                                }

                                .gov-dropdown-container.expanded {
                                    max-height: 80px;
                                    opacity: 1;
                                    margin-top: 10px;
                                }

                                .gov-select {
                                    border-radius: 10px;
                                    border: 1.5px solid rgba(201, 162, 39, 0.3);
                                    padding: 8px 12px;
                                    font-size: 0.9rem;
                                    background: rgba(255, 255, 255, 0.05);
                                    transition: all 0.3s ease;
                                }

                                .gov-select:focus {
                                    border-color: var(--gold);
                                    box-shadow: 0 0 0 3px rgba(201, 162, 39, 0.15);
                                }

                                .shipping-progress {
                                    border-radius: 10px;
                                    background: rgba(201, 162, 39, 0.1);
                                    overflow: hidden;
                                }

                                .shipping-progress .progress-bar {
                                    border-radius: 10px;
                                    transition: width 0.6s ease;
                                }

                                /* Selected Gov Display */
                                .gov-selected-display {
                                    color: var(--gold);
                                    font-weight: 600;
                                }

                                /* Shipping update animation */
                                @keyframes shippingUpdate {
                                    0% {
                                        transform: scale(1);
                                    }

                                    50% {
                                        transform: scale(1.15);
                                    }

                                    100% {
                                        transform: scale(1);
                                    }
                                }

                                .shipping-updated {
                                    animation: shippingUpdate 0.5s ease;
                                }

                                /* Dark mode */
                                [data-bs-theme="dark"] .btn-gov-selector {
                                    color: #e0e0e0;
                                }

                                [data-bs-theme="dark"] .gov-select {
                                    background: rgba(255, 255, 255, 0.08);
                                    color: #e0e0e0;
                                }
                            </style>

                            <hr>

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fs-5">الإجمالي</span>
                                <span class="fs-4 fw-bold" style="color: var(--gold);" id="cartTotal">
                                    {{ number_format($total) }} ج.م+
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
                        <svg class="cart-icon-empty" xmlns="http://www.w3.org/2000/svg" height="80px"
                            viewBox="0 -960 960 960" width="80px" fill="currentColor" style="opacity: 0.5;">
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
        // Global shipping estimator state
        let currentShippingFee = 0;
        let currentSubtotal = {{ $total }};
        const FREE_SHIPPING_THRESHOLD_GLOBAL = {{ $freeShippingThreshold }};

        // Toggle governorate selector
        function toggleGovSelector() {
            const container = document.getElementById('govDropdownContainer');
            const chevron = document.getElementById('govChevron');
            const btn = document.getElementById('govToggleBtn');

            container.classList.toggle('expanded');
            chevron.classList.toggle('rotated');
            btn.classList.toggle('active');
        }

        // Update shipping fee when governorate is selected
        async function updateCartShipping() {
            const select = document.getElementById('cartGovernorateSelect');
            const gov = select.value;
            const feeDisplay = document.getElementById('shippingFeeDisplay');
            const totalDisplay = document.getElementById('cartTotal');
            const govBtnText = document.getElementById('govBtnText');

            if (!gov) {
                // Reset to range
                feeDisplay.innerHTML =
                    '<span class="shipping-range-badge">{{ number_format($minFee) }} - {{ number_format($maxFee) }} ج.م</span>';
                currentShippingFee = 0;
                totalDisplay.textContent = formatNumberGlobal(currentSubtotal) + ' ج.م+';
                govBtnText.textContent = '📍 حدد محافظتك لمعرفة سعر التوصيل';
                localStorage.removeItem('empapy_cart_gov');
                return;
            }

            // Show loading
            feeDisplay.innerHTML = '<span class="spinner-border spinner-border-sm" style="color: var(--gold);"></span>';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                const response = await fetch('/checkout/calculate-shipping', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        governorate: gov,
                        subtotal: currentSubtotal
                    })
                });

                const data = await response.json();

                if (data.success) {
                    currentShippingFee = parseFloat(data.shipping);

                    // Update shipping display with animation
                    if (data.is_free) {
                        feeDisplay.innerHTML = '<span class="text-success shipping-updated">مجاني 🎉</span>';
                    } else {
                        feeDisplay.innerHTML = '<span class="fw-bold shipping-updated">' + data.message + '</span>';
                    }

                    // Update total
                    const newTotal = currentSubtotal + currentShippingFee;
                    totalDisplay.classList.add('shipping-updated');
                    totalDisplay.textContent = formatNumberGlobal(newTotal) + ' ج.م';
                    setTimeout(() => totalDisplay.classList.remove('shipping-updated'), 500);

                    // Update button text
                    govBtnText.textContent = '📍 ' + gov;

                    // Collapse dropdown
                    document.getElementById('govDropdownContainer').classList.remove('expanded');
                    document.getElementById('govChevron').classList.remove('rotated');

                    // Save to localStorage
                    localStorage.setItem('empapy_cart_gov', gov);
                }
            } catch (error) {
                console.error('Error calculating shipping:', error);
                feeDisplay.innerHTML = '<span class="text-danger small">خطأ في الحساب</span>';
            }
        }

        function formatNumberGlobal(num) {
            return new Intl.NumberFormat('ar-EG').format(num);
        }

        // On page load - restore saved governorate (priority: DB profile > localStorage)
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('cartGovernorateSelect');
            if (!select) return;

            // Priority 1: User's saved governorate from profile (DB)
            const userGov = @json($userGovernorate ?? null);
            // Priority 2: Previously selected governorate from localStorage
            const savedGov = localStorage.getItem('empapy_cart_gov');

            const govToUse = userGov || savedGov;

            if (govToUse) {
                select.value = govToUse;
                if (select.value === govToUse) {
                    // Auto-open and calculate
                    toggleGovSelector();
                    updateCartShipping();
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const FREE_SHIPPING_THRESHOLD = {{ $freeShippingThreshold }};
            const SHIPPING_FEE = {{ $shippingFee }};

            // Quantity buttons
            document.querySelectorAll('.qty-btn-modern').forEach(btn => {
                btn.addEventListener('click', function() {
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
                btn.addEventListener('click', function() {
                    const row = this.closest('.cart-item');
                    const key = row.dataset.key;
                    removeCartItem(key, row);
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

                // Update global subtotal for shipping estimator
                currentSubtotal = cartData.total;

                // Recalculate with current shipping selection
                const totalWithDelivery = cartData.total + currentShippingFee;

                // Update shipping display based on new subtotal
                const feeDisplay = document.getElementById('shippingFeeDisplay');
                if (cartData.total >= FREE_SHIPPING_THRESHOLD) {
                    if (feeDisplay) {
                        feeDisplay.innerHTML = '<span class="text-success">مجاني 🎉</span>';
                    }
                    currentShippingFee = 0;
                } else if (currentShippingFee === 0) {
                    // No gov selected yet, show range
                    if (feeDisplay) {
                        feeDisplay.innerHTML =
                            '<span class="shipping-range-badge">{{ number_format($minFee) }} - {{ number_format($maxFee) }} ج.م</span>';
                    }
                }

                // Update free shipping progress bar
                const progressBar = document.querySelector('.shipping-progress .progress-bar');
                const progressHint = document.querySelector('.free-shipping-hint');
                if (cartData.total >= FREE_SHIPPING_THRESHOLD) {
                    if (progressHint) progressHint.style.display = 'none';
                    // Also hide gov selector
                    const govWrapper = document.getElementById('govSelectorWrapper');
                    if (govWrapper) govWrapper.style.display = 'none';
                } else if (progressHint) {
                    progressHint.style.display = '';
                    const remaining = FREE_SHIPPING_THRESHOLD - cartData.total;
                    const pct = Math.min(100, (cartData.total / FREE_SHIPPING_THRESHOLD) * 100);
                    const hintText = progressHint.querySelector('small:first-child');
                    const hintPct = progressHint.querySelector('small:last-child');
                    if (hintText) {
                        hintText.innerHTML =
                            `<i class="bi bi-truck me-1"></i>أضف <strong style="color: var(--gold);">${formatNumber(remaining)} ج.م</strong> للتوصيل المجاني`;
                    }
                    if (hintPct) hintPct.textContent = Math.round(pct) + '%';
                    if (progressBar) progressBar.style.width = pct + '%';
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
