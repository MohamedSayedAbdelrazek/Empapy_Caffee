@extends('layouts.app')

@section('title', 'تعيين كلمة مرور جديدة - إمبابي كافيه')

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
                        <h3 class="text-center mb-4">تعيين كلمة مرور جديدة</h3>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-4">
                                <label class="form-label">البريد الإلكتروني</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $email) }}" required autofocus
                                        placeholder="example@email.com">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">كلمة المرور الجديدة</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="password" class="form-control" required
                                        placeholder="كلمة المرور الجديدة">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">تأكيد كلمة المرور</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control" required
                                        placeholder="أعد إدخال كلمة المرور">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-golden w-100 btn-lg">
                                <i class="bi bi-check-circle me-2"></i>تغيير كلمة المرور
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-muted small">
                                <i class="bi bi-arrow-right me-1"></i>العودة لتسجيل الدخول
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    </script>
@endsection
