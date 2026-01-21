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
                            <div class="form-check glass-card p-3 mb-3">
                                <input class="form-check-input" type="radio" name="payment_method"
                                    value="cash_on_delivery" id="cod" checked onchange="togglePaymentMethod()">
                                <label class="form-check-label d-flex align-items-center gap-3" for="cod">
                                    <i class="bi bi-cash-coin fs-4 text-success"></i>
                                    <div>
                                        <strong>الدفع عند الاستلام</strong>
                                        <p class="mb-0 small text-muted">ادفع نقداً عند استلام طلبك</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Card Payment Option -->
                            <div class="form-check glass-card p-3">
                                <input class="form-check-input" type="radio" name="payment_method" value="card"
                                    id="card" onchange="togglePaymentMethod()">
                                <label class="form-check-label d-flex align-items-center gap-3" for="card">
                                    <i class="bi bi-credit-card fs-4 text-primary"></i>
                                    <div>
                                        <strong>الدفع بالبطاقة</strong>
                                        <p class="mb-0 small text-muted">ادفع بأمان باستخدام بطاقتك الائتمانية</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Stripe Card Element Container (Hidden by default) -->
                            <div id="cardElementContainer" class="mt-3" style="display: none;">
                                <div class="glass-card p-3">
                                    <label class="form-label mb-2">
                                        <i class="bi bi-credit-card-2-front me-2"></i>بيانات البطاقة
                                    </label>
                                    <div id="card-element" class="form-control p-3" style="height: auto;"></div>
                                    <div id="card-errors" class="text-danger small mt-2"></div>
                                </div>
                            </div>
                        </div>
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

@push('head')
    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@push('scripts')
    <script>
        const originalTotal = {{ $total }};
        const subtotal = {{ $subtotal }};
        const shipping = {{ $shipping }};
        let currentDiscount = 0;
        let currentTotal = {{ $total }};

        // Initialize Stripe
        const stripe = Stripe('{{ config('stripe.key') }}');
        let elements;
        let cardElement;
        let paymentIntentClientSecret;

        // Initialize Stripe Elements when card payment is selected
        function initializeStripeElements() {
            if (!elements) {
                elements = stripe.elements({
                    locale: 'ar'
                });

                cardElement = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '16px',
                            color: '#424770',
                            '::placeholder': {
                                color: '#aab7c4',
                            },
                        },
                        invalid: {
                            color: '#9e2146',
                        },
                    },
                });

                cardElement.mount('#card-element');

                // Handle card errors
                cardElement.on('change', function(event) {
                    const displayError = document.getElementById('card-errors');
                    if (event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = '';
                    }
                });
            }
        }

        // Toggle payment method visibility
        function togglePaymentMethod() {
            const cardSelected = document.getElementById('card').checked;
            const cardContainer = document.getElementById('cardElementContainer');

            if (cardSelected) {
                cardContainer.style.display = 'block';
                initializeStripeElements();
            } else {
                cardContainer.style.display = 'none';
            }
        }

        // Handle form submission
        document.querySelector('form').addEventListener('submit', async function(e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

            if (paymentMethod === 'card') {
                e.preventDefault();
                await handleCardPayment();
            }
            // If COD, let the form submit normally
        });

        async function handleCardPayment() {
            const submitButton = document.querySelector('button[type="submit"]');
            const originalButtonContent = submitButton.innerHTML;

            // Disable button and show loading
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري المعالجة...';

            try {
                // Step 1: Create the order first (to get order number)
                const formData = new FormData(document.querySelector('form'));
                formData.set('payment_method', 'card');

                // Calculate total (including discount if applied)
                const totalAmount = currentTotal;

                // Generate a temporary order number
                const tempOrderNumber = 'EMP-' + Date.now();

                // Step 2: Create Payment Intent
                const intentResponse = await fetch('{{ route('payment.create-intent') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        amount: totalAmount,
                        order_number: tempOrderNumber
                    })
                });

                const intentData = await intentResponse.json();

                if (intentData.error) {
                    throw new Error(intentData.error);
                }

                paymentIntentClientSecret = intentData.clientSecret;

                // Step 3: Confirm the payment with Stripe
                const {
                    error,
                    paymentIntent
                } = await stripe.confirmCardPayment(paymentIntentClientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: document.querySelector('[name="customer_name"]').value,
                            email: document.querySelector('[name="customer_email"]').value,
                            phone: document.querySelector('[name="customer_phone"]').value,
                        }
                    }
                });

                if (error) {
                    // Show error
                    document.getElementById('card-errors').textContent = error.message;
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonContent;
                } else if (paymentIntent.status === 'succeeded') {
                    // Payment successful! Now submit the actual order
                    // Update the form with transaction ID
                    const transactionInput = document.createElement('input');
                    transactionInput.type = 'hidden';
                    transactionInput.name = 'transaction_id';
                    transactionInput.value = paymentIntent.id;
                    document.querySelector('form').appendChild(transactionInput);

                    // Submit the form to create the order
                    document.querySelector('form').submit();
                }
            } catch (error) {
                console.error('Payment error:', error);
                document.getElementById('card-errors').textContent = error.message || 'حدث خطأ أثناء معالجة الدفع';
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonContent;
            }
        }

        // Coupon validation function
        function applyCoupon() {
            const code = document.getElementById('couponCode').value.trim();
            const messageDiv = document.getElementById('couponMessage');
            const btn = document.getElementById('applyCouponBtn');

            if (!code) {
                messageDiv.innerHTML = '<span class="text-danger">أدخل كود الخصم</span>';
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';

            fetch('/coupon/validate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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

                        // Update UI
                        document.getElementById('discountRow').style.display = 'flex';
                        document.getElementById('discountAmount').textContent = '- ' + currentDiscount
                            .toLocaleString() + ' ج.م';
                        document.getElementById('totalAmount').textContent = newTotal.toLocaleString() + ' ج.م';
                        document.getElementById('appliedCouponCode').value = code;

                        messageDiv.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>' +
                            data.message + '</span>';

                        // Disable input
                        document.getElementById('couponCode').disabled = true;
                        btn.innerHTML = '<i class="bi bi-check-lg"></i>';
                        btn.classList.remove('btn-outline-secondary');
                        btn.classList.add('btn-success');
                    } else {
                        messageDiv.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>' + data
                            .message + '</span>';
                        btn.disabled = false;
                        btn.innerHTML = 'تطبيق';
                    }
                })
                .catch(error => {
                    messageDiv.innerHTML = '<span class="text-danger">حدث خطأ. حاول مرة أخرى</span>';
                    btn.disabled = false;
                    btn.innerHTML = 'تطبيق';
                });
        }
    </script>
@endpush
