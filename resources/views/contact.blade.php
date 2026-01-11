@extends('layouts.app')

@section('title', 'تواصل معنا - إمبابي كافيه')

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
                            <p class="text-muted mb-0">القاهرة، مصر</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="icon-box">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div>
                            <h6>الهاتف</h6>
                            <p class="text-muted mb-0" dir="ltr">+20 100 123 4567</p>
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
                            <p class="text-muted mb-0">السبت - الخميس: 9 ص - 11 م</p>
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
                                <div class="col-12">
                                    <button type="submit" class="btn btn-golden btn-lg">
                                        <i class="bi bi-send me-2"></i>إرسال الرسالة
                                    </button>
                                </div>
                            </div>
                        </form>
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
    </style>
@endpush
