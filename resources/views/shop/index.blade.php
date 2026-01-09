@extends('layouts.app')

@section('title', 'المتجر - إمبابي كافيه')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">المتجر</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">المتجر</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3 mb-4" data-aos="fade-up">
                    <div class="glass-card p-4 position-sticky" style="top: 100px;">
                        <h5 class="mb-4"><i class="bi bi-filter me-2"></i>تصفية المنتجات</h5>

                        <form action="{{ route('shop.index') }}" method="GET">
                            <!-- Categories -->
                            <div class="mb-4">
                                <h6 class="mb-3">الأصناف</h6>
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('shop.index') }}"
                                        class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                                        الكل
                                    </a>
                                    @foreach ($categories as $category)
                                        <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                                            class="list-group-item list-group-item-action d-flex justify-content-between {{ request('category') === $category->slug ? 'active' : '' }}">
                                            {{ $category->name }}
                                            <span
                                                class="badge bg-secondary rounded-pill">{{ $category->products_count }}</span>
                                        </a>
                                    @endforeach
                                    </div>
                            </div>

                         

                            @if (request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-lg-9">
                    <!-- Sort Bar -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3" data-aos="fade-up">
                        <p class="mb-0 text-muted">
                            عرض {{ $products->count() }} من {{ $products->total() }} منتج
                        </p>
                        <form action="{{ route('shop.index') }}" method="GET" class="d-flex gap-2">
                            @if (request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                          
                            <select name="sort" class="form-select" style="width: auto;" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>الأحدث</option>
                                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>السعر: من
                                    الأقل للأعلى</option>
                                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>السعر:
                                    من الأعلى للأقل</option>
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>الاسم</option>
                            </select>
                        </form>
                    </div>

                    <!-- Products -->
                    <div class="row g-4">
                        @forelse($products as $product)
                            <div class="col-6 col-md-4">
                                @include('components.product-card', [
                                    'product' => $product,
                                    'wishlistIds' => $wishlistIds ?? [],
                                ])
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="glass-card text-center py-5">
                                    <i class="bi bi-cup-straw display-1 text-muted"></i>
                                    <h4 class="mt-3">لا توجد منتجات</h4>
                                    <p class="text-muted">جرب تغيير معايير البحث</p>
                                    <a href="{{ route('shop.index') }}" class="btn btn-golden mt-3">عرض جميع المنتجات</a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if ($products->hasPages())
                        <div class="d-flex justify-content-center mt-5">
                            {{ $products->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .list-group-item {
            color: #000000 !important;
        }

        .list-group-item.active {
            background-color: var(--gold);
            border-color: var(--gold);
            color: var(--espresso) !important;
        }

        .list-group-item:hover:not(.active) {
            background-color: var(--cream);
            color: var(--gold) !important;
        }

        .page-link {
            color: var(--espresso);
            border-color: var(--gray-200);
        }

        .page-link:hover {
            background-color: var(--gold);
            border-color: var(--gold);
            color: var(--espresso);
        }

        .page-item.active .page-link {
            background-color: var(--espresso);
            border-color: var(--espresso);
        }

        /* Fix oversized pagination arrows */
        .pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }

        .pagination svg {
            width: 16px !important;
            height: 16px !important;
            display: inline-block;
            vertical-align: middle;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .pagination .page-item {
            margin: 0;
        }

        /* Hide the large arrow containers if using Tailwind pagination */
        nav[role="navigation"]>div:first-child,
        nav[role="navigation"]>div:last-child {
            display: none;
        }

        nav[role="navigation"]>div:nth-child(2) {
            width: 100%;
        }
    </style>
@endpush
