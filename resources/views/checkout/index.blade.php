@extends('layouts.app')

@section('title', 'إتمام الطلب - إمبابي كافيه')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="padding: 140px 0 60px;">
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
            <form action="{{ route('checkout.store') }}" method="POST">
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
                                    <textarea name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3"
                                        required placeholder="الشارع، المبنى، الطابق، الشقة">{{ old('shipping_address', auth()->user()?->address) }}</textarea>
                                    @error('shipping_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">المدينة *</label>
                                    <input type="text" name="city"
                                        class="form-control @error('city') is-invalid @enderror"
                                        value="{{ old('city', auth()->user()?->city) }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">المحافظة</label>
                                    <select name="governorate" class="form-select">
                                        <option value="">اختر المحافظة</option>
                                        @php $userGov = old('governorate', auth()->user()?->governorate); @endphp
                                        @foreach (['القاهرة', 'الجيزة', 'الإسكندرية', 'الدقهلية', 'الشرقية', 'المنوفية', 'القليوبية', 'البحيرة', 'الغربية', 'كفر الشيخ', 'دمياط', 'بورسعيد', 'الإسماعيلية', 'السويس', 'الفيوم', 'بني سويف', 'المنيا', 'أسيوط', 'سوهاج', 'قنا', 'الأقصر', 'أسوان', 'البحر الأحمر', 'شمال سيناء', 'جنوب سيناء', 'مطروح', 'الوادي الجديد'] as $gov)
                                            <option value="{{ $gov }}" {{ $userGov === $gov ? 'selected' : '' }}>
                                                {{ $gov }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">ملاحظات إضافية</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="أي تعليمات خاصة للتوصيل">{{ old('notes') }}</textarea>
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

                            <!-- Card Payment Option -->
                            <div class="payment-option-card glass-card p-4 position-relative" id="card-option">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                                        <div class="payment-icon-wrapper">
                                            <i class="bi bi-credit-card fs-3 text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong class="d-block mb-1">الدفع بالبطاقة</strong>
                                            <small class="text-muted">ادفع بأمان باستخدام بطاقتك الائتمانية</small>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-reverse m-0">
                                        <input class="form-check-input payment-radio" type="radio"
                                            name="payment_method" value="card" id="card">
                                        <label class="form-check-label visually-hidden" for="card">الدفع
                                            بالبطاقة</label>
                                    </div>
                                </div>

                                <!-- Stripe Card Element Container -->
                                <div id="cardElementContainer" class="card-input-container mt-3"
                                    style="max-height: 0; opacity: 0; overflow: hidden;">
                                    <div class="glass-card p-3">
                                        <label class="form-label mb-2 d-flex align-items-center">
                                            <i class="bi bi-credit-card-2-front me-2 text-primary"></i>
                                            <span>بيانات البطاقة</span>
                                            <span class="badge bg-success ms-2" style="font-size: 0.65rem;">
                                                <i class="bi bi-shield-check me-1"></i>آمن
                                            </span>
                                        </label>
                                        <div id="card-element" class="stripe-card-element"></div>
                                        <div id="card-errors" class="text-danger small mt-2"></div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="bi bi-lock-fill me-1"></i>
                                                معلوماتك محمية بتقنية Stripe المشفرة
                                            </small>
                                        </div>
                                    </div>
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
                                <span class="{{ $shipping == 0 ? 'text-success' : '' }}">
                                    {{ $shipping == 0 ? 'مجاني' : number_format($shipping) . ' ج.م' }}
                                </span>
                            </div>

                            <!-- Coupon/Reward Code Section -->
                            <div class="coupon-section my-3 p-3 rounded" style="background: var(--cream-dark);">
                                <label class="form-label mb-2"><i class="bi bi-ticket-perforated me-2"></i>كود الخصم أو
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
                                <small class="text-muted d-block mt-1"><i class="bi bi-info-circle me-1"></i>يمكنك استخدام
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
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global variables (accessible to coupon function)
        let stripe, elements, cardElement;
        const originalTotal = {{ $total }};
        const subtotal = {{ $subtotal }};
        const shipping = {{ $shipping }};
        let currentDiscount = 0;
        let currentTotal = {{ $total }};

        // --- THE FIX: Wait for Stripe to load using Polling ---
        let stripeLoadAttempts = 0;
        const maxAttempts = 50; // Max 5 seconds (50 * 100ms)

        const stripeLoader = setInterval(function() {
            stripeLoadAttempts++;

            if (typeof Stripe !== 'undefined') {
                clearInterval(stripeLoader);
                console.log('✅ Stripe Library Loaded Successfully after', stripeLoadAttempts * 100, 'ms');
                initPaymentSystem();
            } else if (stripeLoadAttempts >= maxAttempts) {
                clearInterval(stripeLoader);
                console.error('❌ CRITICAL: Stripe failed to load after 5 seconds');
                const container = document.getElementById('cardElementContainer');
                if (container) {
                    container.innerHTML =
                        '<div class="alert alert-danger mt-3"><i class="bi bi-exclamation-triangle me-2"></i>حدث خطأ في تحميل نظام الدفع. يرجى تحديث الصفحة.</div>';
                    container.style.display = 'block';
                }
            } else {
                console.log('⏳ Waiting for Stripe... Attempt', stripeLoadAttempts);
            }
        }, 100); // Check every 100ms

        // Main Initialization Function
        function initPaymentSystem() {
            try {
                // Initialize Stripe
                const stripeKey = '{{ config('stripe.key') }}';
                if (!stripeKey) {
                    console.error('❌ Stripe Key is missing!');
                    return;
                }

                stripe = Stripe(stripeKey);
                console.log('✅ Stripe instance created');

                // Setup Event Listeners
                setupEventListeners();

                // Initialize Elements
                initializeStripeElements();

                // Initial UI State
                updatePaymentOptionUI();

            } catch (error) {
                console.error('❌ Error initializing payment system:', error);
            }
        }

        function setupEventListeners() {
            // Click Handlers
            const codOption = document.getElementById('cod-option');
            const cardOption = document.getElementById('card-option');

            if (codOption) {
                codOption.addEventListener('click', function(e) {
                    // Don't trigger if clicking on radio itself
                    if (e.target.type !== 'radio') {
                        const radio = document.getElementById('cod');
                        if (radio) {
                            radio.checked = true;
                            togglePaymentMethod();
                        }
                    }
                });
            }

            if (cardOption) {
                cardOption.addEventListener('click', function(e) {
                    if (e.target.type !== 'radio') {
                        const radio = document.getElementById('card');
                        if (radio) {
                            radio.checked = true;
                            togglePaymentMethod();
                        }
                    }
                });
            }

            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.addEventListener('change', togglePaymentMethod);
            });

            // Form Submit
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', async function(e) {
                    const checkedPayment = document.querySelector('input[name="payment_method"]:checked');
                    if (checkedPayment && checkedPayment.value === 'card') {
                        e.preventDefault();
                        await handleCardPayment();
                    }
                });
            }

            console.log('✅ Event listeners attached');
        }

        function initializeStripeElements() {
            if (!elements && stripe) {
                try {
                    elements = stripe.elements({
                        locale: 'ar'
                    });
                    cardElement = elements.create('card', {
                        style: {
                            base: {
                                fontSize: '16px',
                                color: '#212529',
                                fontFamily: '"Cairo", system-ui, -apple-system, sans-serif',
                                '::placeholder': {
                                    color: '#aab7c4'
                                },
                            },
                            invalid: {
                                color: '#dc3545',
                                iconColor: '#dc3545'
                            },
                        },
                        hidePostalCode: true
                    });

                    const cardMountPoint = document.getElementById('card-element');
                    if (cardMountPoint) {
                        cardElement.mount('#card-element');
                        console.log('✅ Card Element mounted');

                        // Error handling
                        cardElement.on('change', function(event) {
                            const displayError = document.getElementById('card-errors');
                            if (displayError) {
                                if (event.error) {
                                    displayError.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>' + event
                                        .error.message;
                                } else {
                                    displayError.textContent = '';
                                }
                            }
                        });

                        // Focus effects
                        cardElement.on('focus', function() {
                            const el = document.querySelector('.stripe-card-element');
                            if (el) {
                                el.style.borderColor = 'var(--gold)';
                                el.style.boxShadow = '0 0 0 3px rgba(201, 169, 97, 0.15)';
                            }
                        });

                        cardElement.on('blur', function() {
                            const el = document.querySelector('.stripe-card-element');
                            if (el) {
                                el.style.borderColor = '#e9ecef';
                                el.style.boxShadow = 'none';
                            }
                        });

                        // Ready event
                        cardElement.on('ready', function() {
                            console.log('✅ Card Element is ready for input');
                        });
                    } else {
                        console.error('❌ #card-element mount point not found');
                    }
                } catch (error) {
                    console.error('❌ Error creating Stripe Elements:', error);
                }
            }
        }

        function togglePaymentMethod() {
            const cardRadio = document.getElementById('card');
            const cardContainer = document.getElementById('cardElementContainer');

            if (!cardRadio || !cardContainer) return;

            const cardSelected = cardRadio.checked;
            updatePaymentOptionUI();

            if (cardSelected) {
                cardContainer.classList.add('show');
                // Focus after animation
                setTimeout(() => {
                    if (cardElement) {
                        try {
                            cardElement.focus();
                        } catch (e) {}
                    }
                }, 500);
            } else {
                cardContainer.classList.remove('show');
            }
        }

        function updatePaymentOptionUI() {
            const cardRadio = document.getElementById('card');
            const cardOption = document.getElementById('card-option');
            const codOption = document.getElementById('cod-option');

            if (!cardRadio || !cardOption || !codOption) return;

            if (cardRadio.checked) {
                cardOption.classList.add('active');
                codOption.classList.remove('active');
            } else {
                codOption.classList.add('active');
                cardOption.classList.remove('active');
            }
        }

        async function handleCardPayment() {
            const submitButton = document.querySelector('button[type="submit"]');
            if (!submitButton) return;

            const originalButtonContent = submitButton.innerHTML;

            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري المعالجة...';

            try {
                // 1. Create Intent
                const response = await fetch('{{ route('payment.create-intent') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        amount: currentTotal,
                        customer_name: document.querySelector('[name="customer_name"]')?.value || '',
                        customer_email: document.querySelector('[name="customer_email"]')?.value || ''
                    })
                });

                const data = await response.json();
                if (data.error) throw new Error(data.error);

                // 2. Confirm Payment
                const {
                    error,
                    paymentIntent
                } = await stripe.confirmCardPayment(data.clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: document.querySelector('[name="customer_name"]')?.value || '',
                            email: document.querySelector('[name="customer_email"]')?.value || '',
                            phone: document.querySelector('[name="customer_phone"]')?.value || ''
                        }
                    }
                });

                if (error) throw error;

                if (paymentIntent.status === 'succeeded') {
                    console.log('✅ Payment succeeded!');

                    // Confetti!
                    if (typeof confetti === 'function') {
                        const duration = 3000;
                        const animationEnd = Date.now() + duration;
                        const defaults = {
                            startVelocity: 30,
                            spread: 360,
                            ticks: 60,
                            zIndex: 9999
                        };

                        const interval = setInterval(function() {
                            const timeLeft = animationEnd - Date.now();
                            if (timeLeft <= 0) return clearInterval(interval);

                            const particleCount = 50 * (timeLeft / duration);
                            confetti({
                                ...defaults,
                                particleCount,
                                origin: {
                                    x: Math.random() * 0.3 + 0.1,
                                    y: Math.random() - 0.2
                                }
                            });
                            confetti({
                                ...defaults,
                                particleCount,
                                origin: {
                                    x: Math.random() * 0.3 + 0.6,
                                    y: Math.random() - 0.2
                                }
                            });
                        }, 250);
                    }

                    // Alert
                    if (typeof Swal !== 'undefined') {
                        await Swal.fire({
                            title: 'تم الدفع بنجاح!',
                            text: 'جاري تسجيل طلبك...',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }

                    // Submit Form
                    const form = document.querySelector('form');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'transaction_id';
                    input.value = paymentIntent.id;
                    form.appendChild(input);
                    form.submit();
                }

            } catch (error) {
                console.error('❌ Payment Error:', error);
                const errorDiv = document.getElementById('card-errors');
                if (errorDiv) errorDiv.textContent = error.message || 'حدث خطأ أثناء معالجة الدفع';

                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonContent;

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'فشلت العملية',
                        text: error.message,
                        icon: 'error',
                        confirmButtonText: 'حاول مرة أخرى'
                    });
                } else {
                    alert(error.message);
                }
            }
        }

        // Coupon Logic (always available, uses global variables)
        function applyCoupon() {
            const code = document.getElementById('couponCode')?.value?.trim();
            const messageDiv = document.getElementById('couponMessage');
            const btn = document.getElementById('applyCouponBtn');

            if (!code) {
                if (messageDiv) messageDiv.innerHTML = '<span class="text-danger">أدخل كود الخصم</span>';
                return;
            }

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
            }

            fetch('/coupon/validate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        code: code,
                        order_total: subtotal
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        currentDiscount = data.discount;
                        const newTotal = subtotal + shipping - currentDiscount;
                        currentTotal = newTotal;

                        const discountRow = document.getElementById('discountRow');
                        if (discountRow) discountRow.style.setProperty('display', 'flex', 'important');

                        const discountAmount = document.getElementById('discountAmount');
                        if (discountAmount) discountAmount.textContent = '- ' + currentDiscount.toLocaleString() +
                            ' ج.م';

                        const totalAmount = document.getElementById('totalAmount');
                        if (totalAmount) totalAmount.textContent = newTotal.toLocaleString() + ' ج.م';

                        const appliedCode = document.getElementById('appliedCouponCode');
                        if (appliedCode) appliedCode.value = code;

                        if (messageDiv) messageDiv.innerHTML =
                            '<span class="text-success"><i class="bi bi-check-circle me-1"></i>' + data.message +
                            '</span>';

                        const couponInput = document.getElementById('couponCode');
                        if (couponInput) couponInput.disabled = true;

                        if (btn) {
                            btn.innerHTML = '<i class="bi bi-check-lg"></i>';
                            btn.classList.replace('btn-outline-secondary', 'btn-success');
                        }
                    } else {
                        if (messageDiv) messageDiv.innerHTML =
                            '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>' + data.message + '</span>';
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = 'تطبيق';
                        }
                    }
                })
                .catch(error => {
                    if (messageDiv) messageDiv.innerHTML = '<span class="text-danger">حدث خطأ. حاول مرة أخرى</span>';
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = 'تطبيق';
                    }
                });
        }
    </script>
@endpush
