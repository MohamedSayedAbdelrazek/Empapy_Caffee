@extends('layouts.app')

@section('title', 'نسيت كلمة المرور - إمبابي كافيه')

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
                        <h3 class="text-center mb-4">إعادة تعيين كلمة المرور</h3>

                        @if (session('success'))
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <p class="text-muted small mb-4">
                            أدخل بريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيين كلمة المرور.
                        </p>

                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">البريد الإلكتروني</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        required autofocus placeholder="example@email.com">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-golden w-100 btn-lg">
                                <i class="bi bi-send me-2"></i>إرسال رابط إعادة التعيين
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="fw-bold" style="color: var(--gold);">
                                <i class="bi bi-arrow-right me-1"></i>العودة لتسجيل الدخول
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
