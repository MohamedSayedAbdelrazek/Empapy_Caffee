@extends('layouts.app')

@section('title', 'تواصل معنا - إمبابي كافيه')

@push('head')
    {{-- Google reCAPTCHA v3 --}}
    @if(config('recaptcha.enabled') && config('recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}"></script>
    @endif
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">تواصل معنا</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">تواصل معنا</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Contact Info -->
                <div class="col-lg-5" data-aos="fade-up">
                    <h2 class="mb-4">نحن هنا لمساعدتك</h2>
                    <p class="text-muted mb-5">
                        لديك سؤال أو استفسار؟ لا تتردد في التواصل معنا. فريقنا جاهز لمساعدتك.
                    </p>

                    <div class="d-flex gap-3 mb-4">
                        <div class="icon-box">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h6>العنوان</h6>
                            <a href="https://www.google.com/maps/place/%D8%A8%D9%86+%D8%A7%D9%85%D8%A8%D8%A7%D8%A8%D9%8A%E2%80%AD/@30.0879583,31.2502435,15.75z/data=!4m6!3m5!1s0x145841006b879969:0x1d2dfd8c57ec5e89!8m2!3d30.0887951!4d31.2528785!16s%2Fg%2F11lz66c2wf"
                                target="_blank" class="text-muted mb-0 text-decoration-none hover-gold"
                                style="display: block;">
                                القاهرة، مصر - بن امبابي
                                <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.8rem;"></i>
                            </a>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="icon-box">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div>
                            <h6>الهاتف</h6>
                            <a href="tel:+201151579225" class="text-muted mb-0 text-decoration-none" style="display: block;"
                                dir="ltr">
                                +20 1151579225
                            </a>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="icon-box">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div>
                            <h6>البريد الإلكتروني</h6>
                            <p class="text-muted mb-0">info@empapy.com</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="icon-box">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                            <h6>ساعات العمل</h6>
                            <p class="text-muted mb-0">طوال أيام الأسبوع: 9 ص - 2 ص</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
                    <div class="glass-card p-5">
                        <h4 class="mb-4">أرسل لنا رسالة</h4>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <i class="bi bi-exclamation-circle me-2"></i>يرجى تصحيح الأخطاء التالية:
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">الاسم</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">الموضوع</label>
                                    <input type="text" name="subject"
                                        class="form-control @error('subject') is-invalid @enderror"
                                        value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">الرسالة</label>
                                    <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- reCAPTCHA Hidden Token --}}
                                @if(config('recaptcha.enabled') && config('recaptcha.site_key'))
                                    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                                    @error('recaptcha_token')
                                        <div class="col-12">
                                            <div class="alert alert-danger py-2">
                                                <i class="bi bi-shield-exclamation me-2"></i>{{ $message }}
                                            </div>
                                        </div>
                                    @enderror
                                @endif

                                <div class="col-12">
                                    <button type="submit" class="btn btn-golden btn-lg" id="submitBtn">
                                        <i class="bi bi-send me-2"></i>إرسال الرسالة
                                    </button>
                                    @if(config('recaptcha.enabled') && config('recaptcha.site_key'))
                                        <small class="d-block text-muted mt-2">
                                            <i class="bi bi-shield-check me-1"></i>محمي بواسطة Google reCAPTCHA
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Google Maps Section -->
            <div class="row mt-5" data-aos="fade-up">
                <div class="col-12">
                    <div class="glass-card overflow-hidden" style="padding: 0;">
                        <div class="map-header"
                            style="padding: 1.5rem; background: linear-gradient(135deg, var(--espresso) 0%, var(--dark-brown) 100%); color: white;">
                            <h4 class="mb-1">
                                <i class="bi bi-geo-alt-fill me-2"></i>
                                موقعنا على الخريطة
                            </h4>
                            <p class="mb-0 opacity-75">بن امبابي - القاهرة، مصر</p>
                        </div>
                        <div class="map-container" style="height: 450px; position: relative;">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3453.238326!2d31.2502435!3d30.0887951!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x145841006b879969%3A0x1d2dfd8c57ec5e89!2z2KjZhiDYp9mF2KjYp9io2Yo!5e0!3m2!1sar!2seg!4v1706624000000!5m2!1sar!2seg"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                            <!-- Overlay button -->
                            <a href="https://www.google.com/maps/place/%D8%A8%D9%86+%D8%A7%D9%85%D8%A8%D8%A7%D8%A8%D9%8A%E2%80%AD/@30.0879583,31.2502435,15.75z/data=!4m6!3m5!1s0x145841006b879969:0x1d2dfd8c57ec5e89!8m2!3d30.0887951!4d31.2528785!16s%2Fg%2F11lz66c2wf"
                                target="_blank" class="btn btn-golden"
                                style="position: absolute; bottom: 20px; left: 20px; z-index: 10; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                                <i class="bi bi-pin-map-fill me-2"></i>
                                افتح في Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .icon-box {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-gold);
            border-radius: 12px;
            color: var(--espresso);
            font-size: 1.2rem;
        }

        /* Hover effects for contact links */
        .hover-gold:hover {
            color: var(--gold) !important;
        }

        .location-link:hover,
        .footer-contact a:hover {
            color: var(--gold) !important;
            text-decoration: underline !important;
        }

        /* Map container styling */
        .map-container {
            filter: grayscale(0.2);
            transition: filter 0.3s ease;
        }

        .map-container:hover {
            filter: grayscale(0);
        }

        /* reCAPTCHA badge positioning */
        .grecaptcha-badge {
            visibility: hidden;
        }
    </style>
@endpush

@push('scripts')
    {{-- reCAPTCHA Token Generation --}}
    @if(config('recaptcha.enabled') && config('recaptcha.site_key'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form[action*="contact"]');
                const submitBtn = document.getElementById('submitBtn');
                const tokenField = document.getElementById('recaptcha_token');
                
                if (form && tokenField && typeof grecaptcha !== 'undefined') {
                    // Get token when page loads
                    grecaptcha.ready(function() {
                        refreshToken();
                    });
                    
                    // Refresh token every 2 minutes (tokens expire after 2 min)
                    setInterval(refreshToken, 110000);
                    
                    // Get fresh token before form submission
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>جاري التحقق...';
                        
                        grecaptcha.ready(function() {
                            grecaptcha.execute('{{ config('recaptcha.site_key') }}', {action: 'contact'})
                                .then(function(token) {
                                    tokenField.value = token;
                                    form.submit();
                                })
                                .catch(function(error) {
                                    console.error('reCAPTCHA error:', error);
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>إرسال الرسالة';
                                    alert('حدث خطأ في التحقق. يرجى تحديث الصفحة والمحاولة مرة أخرى.');
                                });
                        });
                    });
                    
                    function refreshToken() {
                        grecaptcha.execute('{{ config('recaptcha.site_key') }}', {action: 'contact'})
                            .then(function(token) {
                                tokenField.value = token;
                            });
                    }
                }
            });
        </script>
    @endif
@endpush
