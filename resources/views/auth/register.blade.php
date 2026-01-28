@extends('layouts.app')

@section('title', 'إنشاء حساب - إمبابي كافيه')

@section('content')
    <section class="py-5" style="min-height: 100vh; display: flex; align-items: center; background: var(--gradient-dark);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5" data-aos="fade-up">
                    <div class="text-center mb-5">
                        <a href="{{ route('home') }}"
                            class="d-inline-flex align-items-center gap-3 text-white text-decoration-none">
                            <img src="{{ asset('logo.jpg') }}" alt="إمبابي كافيه"
                                style="height: 70px; width: auto; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.3);">
                            <span style="font-size: 1.8rem; font-weight: 800;">إمبابي كافيه</span>
                        </a>
                    </div>

                    <div class="glass-card p-5">
                        <h3 class="text-center mb-4">إنشاء حساب جديد</h3>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            @if (isset($referralCode) && $referralCode)
                                <input type="hidden" name="referral_code" value="{{ $referralCode }}">
                                <div class="alert alert-success mb-3">
                                    <i class="bi bi-gift me-2"></i>
                                    🎁 لديك رابط إحالة! سجّل الآن واحصل على نقاط مكافأة مجانية!
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">الاسم الكامل *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        required autofocus placeholder="محمد أحمد">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">البريد الإلكتروني *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        required placeholder="example@email.com">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">رقم الهاتف</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}"
                                        placeholder="01xxxxxxxxx">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">كلمة المرور *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control" required
                                        placeholder="••••••••" minlength="8">
                                </div>
                                <small class="text-muted">8 أحرف على الأقل</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">تأكيد كلمة المرور *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control" required
                                        placeholder="••••••••">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-golden w-100 btn-lg">
                                <i class="bi bi-person-plus me-2"></i>إنشاء الحساب
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <span class="text-muted">لديك حساب بالفعل؟</span>
                            <a href="{{ route('login') }}" class="text-gold fw-bold">تسجيل الدخول</a>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="text-white-50">
                            <i class="bi bi-arrow-right me-1"></i>العودة للرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .text-gold {
            color: var(--gold) !important;
        }
    </style>
@endpush
