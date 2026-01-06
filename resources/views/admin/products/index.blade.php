@extends('admin.layouts.app')

@section('title', 'المنتجات')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header-admin">
            <h1 class="page-title-admin">المنتجات</h1>
            <p class="page-subtitle-admin">إدارة منتجات المتجر</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-plus-lg me-2"></i>إضافة منتج
        </a>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-4">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="بحث..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">كل الأصناف</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">كل الحالات</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-light w-100">
                    <i class="bi bi-filter"></i> تصفية
                </button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;"></th>
                        <th>المنتج</th>
                        <th>الصنف</th>
                        <th>السعر</th>
                        <th>الحالة</th>
                        <th style="width: 120px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="rounded"
                                    style="width: 45px; height: 45px; object-fit: cover;">
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                <br><small class="text-muted">{{ $product->name }}</small>
                            </td>
                            <td>{{ $product->category?->name }}</td>
                            <td>
                                @if ($product->sale_price)
                                    <span
                                        class="text-decoration-line-through text-muted">{{ number_format($product->price) }}</span>
                                    <br><strong class="text-success">{{ number_format($product->sale_price) }} ج.م</strong>
                                @else
                                    <strong>{{ number_format($product->price) }} ج.م</strong>
                                @endif
                            </td>
                            <td>
                                @if ($product->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">غير نشط</span>
                                @endif
                                @if ($product->is_featured)
                                    <span class="badge bg-warning text-dark">مميز</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-box display-4 d-block mb-3"></i>
                                لا توجد منتجات
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
