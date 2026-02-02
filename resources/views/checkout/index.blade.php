@extends('layouts.app')

@section('title', 'إتمام الطلب - إمبابي كافيه')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="padding: 40px 0 4/track0px;">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">إتمام الطلب</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">السلة</a></li>
                    <li class="breadcrumb-item active">إتمام الطلب</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            {{-- Warning Message --}}
            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Global Messages Display --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Validation Errors Summary --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <h6 class="alert-heading"><i class="bi bi-exclamation-octagon me-2"></i>يرجى تصحيح الأخطاء التالية:
                    </h6>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="row g-5">
                    <!-- Checkout Form -->
                    <div class="col-lg-7" data-aos="fade-up">
                        <div class="glass-card p-4 mb-4">
                            <h5 class="mb-4"><i class="bi bi-person me-2"></i>معلومات العميل</h5>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">الاسم الكامل *</label>
                                    <input type="text" name="customer_name"
                                        class="form-control @error('customer_name') is-invalid @enderror"
                                        value="{{ old('customer_name', auth()->user()?->name) }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">البريد الإلكتروني *</label>
                                    <input type="email" name="customer_email"
                                        class="form-control @error('customer_email') is-invalid @enderror"
                                        value="{{ old('customer_email', auth()->user()?->email) }}" required>
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">رقم الهاتف *</label>
                                    <input type="tel" name="customer_phone"
                                        class="form-control @error('customer_phone') is-invalid @enderror"
                                        value="{{ old('customer_phone', auth()->user()?->phone) }}" required
                                        placeholder="01xxxxxxxxx">
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="glass-card p-4 mb-4">
                            <h5 class="mb-4"><i class="bi bi-geo-alt me-2"></i>عنوان التوصيل</h5>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">العنوان التفصيلي *</label>
                                    <textarea name="shipping_address"
                                        class="form-control @error('shipping_address') is-invalid @enderror" rows="3"
                                        required
                                        placeholder="الشارع، المبنى، الطابق، الشقة">{{ old('shipping_address', auth()->user()?->address) }}</textarea>
                                    @error('shipping_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">المدينة *</label>
                                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                        value="{{ old('city', auth()->user()?->city) }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">المحافظة *</label>
                                    <select name="governorate" id="governorateSelect"
                                        class="form-select @error('governorate') is-invalid @enderror" required
                                        onchange="updateShippingFee()">
                                        <option value="">اختر المحافظة</option>
                                        @php $userGov = old('governorate', auth()->user()?->governorate); @endphp
                                        @foreach ($shippingZones as $zone)
                                            <option value="{{ $zone->name }}" {{ $userGov === $zone->name ? 'selected' : '' }}
                                                data-fee="{{ $zone->fee }}">
                                                {{ $zone->name }} ({{ number_format($zone->fee) }} ج.م)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('governorate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">ملاحظات إضافية</label>
                                    <textarea name="notes" class="form-control" rows="2"
                                        placeholder="أي تعليمات خاصة للتوصيل">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="glass-card p-4">
                            <h5 class="mb-4"><i class="bi bi-credit-card me-2"></i>طريقة الدفع</h5>

                            <!-- Cash on Delivery Option -->
                            <div class="payment-option-card glass-card p-4 mb-3 position-relative" id="cod-option">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                                        <div class="payment-icon-wrapper">
                                            <i class="bi bi-cash-coin fs-3 text-success"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong class="d-block mb-1">الدفع عند الاستلام</strong>
                                            <small class="text-muted">ادفع نقداً عند استلام طلبك</small>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-reverse m-0">
                                        <input class="form-check-input payment-radio" type="radio" name="payment_method"
                                            value="cash_on_delivery" id="cod" checked>
                                        <label class="form-check-label visually-hidden" for="cod">الدفع عند
                                            الاستلام</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Payment Option - Coming Soon -->
                            <div class="glass-card p-4 position-relative opacity-75" id="card-option">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                                        <div class="payment-icon-wrapper">
                                            <i class="bi bi-credit-card fs-3 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong class="d-block mb-1 text-muted">الدفع الإلكتروني</strong>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                قريباً - سيتم توفير الدفع أونلاين
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass-split me-1"></i>قريباً
                                    </span>
                                </div>
                            </div>
                        </div>

                        <style>
                            /* Payment Option Cards */
                            .payment-option-card {
                                cursor: pointer;
                                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                border: 2px solid transparent;
                                background: rgba(255, 255, 255, 0.05);
                                backdrop-filter: blur(10px);
                            }

                            .payment-option-card:hover {
                                border-color: rgba(201, 169, 97, 0.3);
                                transform: translateX(-2px);
                                box-shadow: 0 4px 12px rgba(201, 169, 97, 0.15);
                            }

                            .payment-option-card.active {
                                border-color: var(--gold);
                                background: rgba(201, 169, 97, 0.08);
                                box-shadow: 0 4px 16px rgba(201, 169, 97, 0.25);
                            }

                            /* Payment Icon Wrapper */
                            .payment-icon-wrapper {
                                width: 50px;
                                height: 50px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                border-radius: 12px;
                                background: rgba(255, 255, 255, 0.1);
                                transition: all 0.3s ease;
                            }

                            .payment-option-card.active .payment-icon-wrapper {
                                background: rgba(201, 169, 97, 0.15);
                                transform: scale(1.05);
                            }

                            /* Custom Radio Button */
                            .payment-radio {
                                width: 24px;
                                height: 24px;
                                min-width: 24px;
                                cursor: pointer;
                                border: 2px solid #dee2e6;
                                margin: 0;
                                flex-shrink: 0;
                            }

                            .payment-radio:checked {
                                background-color: var(--gold);
                                border-color: var(--gold);
                                box-shadow: 0 0 0 4px rgba(201, 169, 97, 0.2);
                            }

                            .payment-radio:focus {
                                box-shadow: 0 0 0 4px rgba(201, 169, 97, 0.25);
                                border-color: var(--gold);
                            }

                            .payment-radio:hover {
                                border-color: var(--gold);
                            }

                            /* Card Input Container Animation */
                            .card-input-container {
                                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                            }

                            .card-input-container.show {
                                max-height: 500px !important;
                                opacity: 1 !important;
                                overflow: visible !important;
                                margin-top: 1rem !important;
                            }

                            /* Stripe Card Element Styling */
                            .stripe-card-element {
                                background: white;
                                padding: 14px;
                                border: 2px solid #e9ecef;
                                border-radius: 8px;
                                transition: all 0.3s ease;
                                min-height: 45px;
                            }

                            .stripe-card-element:hover {
                                border-color: var(--gold);
                            }

                            .stripe-card-element:focus-within {
                                border-color: var(--gold);
                                box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.15);
                            }

                            /* Dark mode adjustments */
                            [data-bs-theme="dark"] .stripe-card-element {
                                background: rgba(255, 255, 255, 0.05);
                                border-color: rgba(255, 255, 255, 0.1);
                            }

                            [data-bs-theme="dark"] .payment-option-card {
                                background: rgba(255, 255, 255, 0.02);
                            }

                            /* RTL specific fixes */
                            [dir="rtl"] .payment-option-card {
                                direction: rtl;
                            }

                            [dir="rtl"] .form-check-reverse {
                                padding-right: 0;
                                padding-left: 0;
                            }

                            /* Pulse animation for active card */
                            @keyframes pulse-border {

                                0%,
                                100% {
                                    border-color: var(--gold);
                                }

                                50% {
                                    border-color: rgba(201, 169, 97, 0.5);
                                }
                            }

                            .payment-option-card.active.pulse {
                                animation: pulse-border 2s ease-in-out infinite;
                            }
                        </style>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
                        <div class="glass-card p-4 position-sticky" style="top: 100px;">
                            <h5 class="mb-4"><i class="bi bi-receipt me-2"></i>ملخص الطلب</h5>

                            <div class="order-items mb-4">
                                @foreach ($cartItems as $item)
                                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                        <img src="{{ $item['product']->image }}" alt="{{ $item['product']->name }}"
                                            class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 small">{{ $item['product']->name }}</h6>
                                            <small class="text-muted">الكمية: {{ $item['quantity'] }}</small>
                                        </div>
                                        <span class="fw-bold">{{ number_format($item['subtotal']) }} ج.م</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>المجموع الفرعي</span>
                                <span>{{ number_format($subtotal) }} ج.م</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>التوصيل</span>
                                <span id="shippingFeeDisplay" class="{{ $shipping == 0 ? 'text-success' : '' }}">
                                    {{ $shipping == 0 ? 'مجاني' : number_format($shipping) . ' ج.م' }}
                                </span>
                            </div>

                            <!-- Coupon/Reward Code Section -->
                            <div class="coupon-section my-3 p-3 rounded" style="background: var(--cream-dark);">
                                <label class="form-label mb-2"><i class="bi bi-ticket-perforated me-2"></i>كود الخصم
                                    أو
                                    المكافأة</label>
                                <div class="input-group">
                                    <input type="text" id="couponCode" class="form-control"
                                        placeholder="كود الخصم أو كود المكافأة (RWD-XXXXXX)">
                                    <button type="button" class="btn btn-outline-secondary" id="applyCouponBtn"
                                        onclick="applyCoupon()">
                                        تطبيق
                                    </button>
                                </div>
                                <input type="hidden" name="coupon_code" id="appliedCouponCode" value="">
                                <div id="couponMessage" class="mt-2 small"></div>
                                <small class="text-muted d-block mt-1"><i class="bi bi-info-circle me-1"></i>يمكنك
                                    استخدام
                                    كود الخصم العادي أو كود مكافأة من نقاطك</small>
                            </div>

                            <!-- Discount Row (hidden initially) -->
                            <div id="discountRow" class="d-flex justify-content-between mb-2 text-success"
                                style="display: none !important;">
                                <span><i class="bi bi-tag me-1"></i>الخصم</span>
                                <span id="discountAmount">0 ج.م</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fs-5 fw-bold">الإجمالي</span>
                                <span class="fs-4 fw-bold" style="color: var(--gold);"
                                    id="totalAmount">{{ number_format($total) }}
                                    ج.م</span>
                            </div>

                            <button type="submit" class="btn btn-golden w-100 btn-lg">
                                <i class="bi bi-check-circle me-2"></i>تأكيد الطلب
                            </button>

                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                                <i class="bi bi-arrow-right me-2"></i>العودة للسلة
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- Load Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // تعريف المتغيرات
        let originalTotal = {{ $total }};
        const subtotal = {{ $subtotal }};
        let shipping = {{ $shipping }};
        let currentDiscount = 0;
        let currentTotal = {{ $total }};

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            // Mark COD as active
            document.getElementById('cod-option').classList.add('active');

            if (document.getElementById('governorateSelect').value) {
                updateShippingFee();
            }
        });

        // Coupon Logic - Complete Implementation
        async function applyCoupon() {
            const code = document.getElementById('couponCode').value.trim();
            const btn = document.getElementById('applyCouponBtn');
            const messageDiv = document.getElementById('couponMessage');
            const discountRow = document.getElementById('discountRow');
            const discountAmount = document.getElementById('discountAmount');
            const totalAmount = document.getElementById('totalAmount');
            const hiddenInput = document.getElementById('appliedCouponCode');

            // Reset previous state
            messageDiv.innerHTML = '';
            messageDiv.className = 'mt-2 small';

            if (!code) {
                messageDiv.innerHTML =
                    '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>الرجاء إدخال كود الخصم</span>';
                return;
            }

            // Disable button during request
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري التحقق...';

            try {
                const response = await fetch('/coupon/validate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        code: code,
                        order_total: subtotal
                    })
                });

                const data = await response.json();
                console.log('Coupon Response:', data);

                if (data.valid) {
                    // Success - Apply discount
                    currentDiscount = parseFloat(data.discount) || 0;
                    hiddenInput.value = code;

                    // Update discount display
                    if (currentDiscount > 0 || data.reward_type === 'free_shipping') {
                        discountRow.style.display = 'flex !important';
                        discountRow.classList.add('d-flex');
                        discountRow.classList.remove('d-none');
                        discountAmount.textContent = '-' + currentDiscount.toLocaleString('ar-EG') + ' ج.م';
                    }

                    // Calculate new total
                    currentTotal = originalTotal - currentDiscount;
                    if (currentTotal < 0) currentTotal = 0;
                    totalAmount.textContent = currentTotal.toLocaleString('ar-EG') + ' ج.م';

                    // Show success message
                    messageDiv.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>' + data
                        .message + '</span>';

                    // Disable coupon input after successful apply
                    document.getElementById('couponCode').disabled = true;
                    btn.textContent = 'تم التطبيق ✓';
                    btn.disabled = true;
                    btn.classList.remove('btn-outline-secondary');
                    btn.classList.add('btn-success');

                } else {
                    // Invalid coupon
                    messageDiv.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>' + data
                        .message + '</span>';
                    btn.disabled = false;
                    btn.innerHTML = 'تطبيق';
                }

            } catch (error) {
                console.error('Coupon validation error:', error);
                messageDiv.innerHTML =
                    '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>حدث خطأ أثناء التحقق من الكود</span>';
                btn.disabled = false;
                btn.innerHTML = 'تطبيق';
            }
        }

        // Initialize on load if value exists
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById('governorateSelect').value) {
                updateShippingFee();
            }
        });

        // Update Shipping Fee based on Governorate
        async function updateShippingFee() {
            const select = document.getElementById('governorateSelect');
            const gov = select.value;
            const feeDisplay = document.getElementById('shippingFeeDisplay');
            const totalDisplay = document.getElementById('totalAmount');

            if (!gov) return;

            // Show loading state
            feeDisplay.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            try {
                const response = await fetch('/checkout/calculate-shipping', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        governorate: gov,
                        subtotal: subtotal
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Update global shipping variable
                    shipping = parseFloat(data.shipping);

                    // Update Fee Display
                    if (data.is_free) {
                        feeDisplay.textContent = 'مجاني';
                        feeDisplay.className = 'text-success fw-bold';
                    } else {
                        feeDisplay.textContent = data.message;
                        feeDisplay.className = '';
                    }

                    // Recalculate Total with Discount
                    // Note: originalTotal includes initial shipping, so we should recalculate base
                    // Base Total = Subtotal + New Shipping - Discount
                    currentTotal = subtotal + shipping - currentDiscount;

                    // Update Total Display
                    totalDisplay.textContent = currentTotal.toLocaleString('ar-EG') + ' ج.م';

                    // Update originalTotal reference if needed (though we calculate from subtotal)
                    originalTotal = subtotal + shipping;

                }

            } catch (error) {
                console.error('Error updating shipping:', error);
                feeDisplay.textContent = 'خطأ في الحساب';
            }
        }
    </script>
@endpush