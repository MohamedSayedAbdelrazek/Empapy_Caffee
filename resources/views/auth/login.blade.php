@extends('layouts.app')

@section('title', 'تسجيل الدخول - إمبابي كافيه')

@section('content')
    <section class="py-5" style="min-height: 100vh; display: flex; align-items: center; background: var(--gradient-dark);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5" data-aos="fade-up">
                    <div class="text-center mb-5">
                        <a href="{{ route('home') }}"
                            class="d-inline-flex align-items-center gap-3 text-white text-decoration-none">

                            <span style="font-size: 1.8rem; font-weight: 800;">إمبابي كافيه</span>
                        </a>
                    </div>

                    <div class="glass-card p-5">
                        <h3 class="text-center mb-4">تسجيل الدخول</h3>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">البريد الإلكتروني</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        required autofocus placeholder="example@email.com">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">كلمة المرور</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control" required
                                        placeholder="••••••••">
                                </div>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">تذكرني</label>
                            </div>

                            <button type="submit" class="btn btn-golden w-100 btn-lg">
                                <i class="bi bi-box-arrow-in-left me-2"></i>تسجيل الدخول
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <span class="text-muted">ليس لديك حساب؟</span>
                            <a href="{{ route('register') }}" class="fw-bold" style="color: var(--gold);">إنشاء حساب
                                جديد</a>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('home') }}" class="text-muted small">
                                <i class="bi bi-arrow-right me-1"></i>العودة للرئيسية
                            </a>
                        </div>
                    </div>

                    <div class="text-center mt-4 text-white-50 small">
                        <p class="mb-1">بيانات الدخول للتجربة:</p>
                        <p class="mb-0">admin@empapy.com / password</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
