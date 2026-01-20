@extends('layouts.app')

@section('title', 'من نحن - إمبابي كافيه')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">من نحن</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">من نحن</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-up">
                    <img src="{{ asset('images/about-coffee.jpg') }}" alt="About Empapy Caffe"
                        class="rounded-4 w-100 shadow-lg">
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="mb-4">قصتنا</h2>
                    <p class="lead mb-4">
                        بدأت إمبابي كافيه برؤية بسيطة: تقديم أفضل تجربة قهوة في مصر.
                    </p>
                    <p class="text-muted mb-4">
                        نختار حبوب البن بعناية فائقة من أفضل المزارع حول العالم - من مرتفعات إثيوبيا إلى سفوح جبال كولومبيا.
                        نحمص كل دفعة يدوياً لضمان أعلى جودة ونكهة مميزة.
                    </p>
                    <p class="text-muted mb-4">
                        نؤمن بأن القهوة ليست مجرد مشروب، بل هي تجربة كاملة. لذلك نسعى لتقديم كل كوب بحب وإتقان.
                    </p>

                    <div class="row g-4 mt-4">
                        <div class="col-6">
                            <div class="glass-card p-4 text-center">
                                <h3 style="color: var(--gold);">25+</h3>
                                <p class="mb-0 small text-muted">نوع قهوة</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="glass-card p-4 text-center">
                                <h3 style="color: var(--gold);">15K+</h3>
                                <p class="mb-0 small text-muted">عميل سعيد</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>قيمنا</h2>
            </div>

            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="glass-card p-4 text-center h-100">
                        <i class="bi bi-award display-4 mb-3" style="color: var(--gold);"></i>
                        <h5>الجودة</h5>
                        <p class="text-muted mb-0">نلتزم بأعلى معايير الجودة في كل خطوة من التحضير</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="glass-card p-4 text-center h-100">
                        <i class="bi bi-heart display-4 mb-3" style="color: var(--gold);"></i>
                        <h5>الشغف</h5>
                        <p class="text-muted mb-0">نحب القهوة ونسعى لمشاركة هذا الحب مع عملائنا</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="glass-card p-4 text-center h-100">
                        <i class="bi bi-globe display-4 mb-3" style="color: var(--gold);"></i>
                        <h5>الاستدامة</h5>
                        <p class="text-muted mb-0">ندعم المزارعين المحليين ونهتم بالبيئة</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
