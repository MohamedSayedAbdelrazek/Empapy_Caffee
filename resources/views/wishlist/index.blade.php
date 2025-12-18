@extends('layouts.app')

@section('title', 'المفضلة - إمبابي كافيه')
@section('meta_description', 'قائمة المنتجات المفضلة لديك في إمبابي كافيه')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">المفضلة</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">المفضلة</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            @if ($wishlistItems->count() > 0)
                <div class="row g-4">
                    @foreach ($wishlistItems as $item)
                        <div class="col-6 col-md-4 col-lg-3">
                            @include('components.product-card', ['product' => $item->product])
                        </div>
                    @endforeach
                </div>
            @else
                <div class="glass-card text-center py-5">
                    <i class="bi bi-heart display-1 text-muted"></i>
                    <h4 class="mt-3">قائمة المفضلة فارغة</h4>
                    <p class="text-muted">أضف منتجات للمفضلة لتجدها هنا</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-golden mt-3">تصفح المنتجات</a>
                </div>
            @endif
        </div>
    </section>
@endsection
