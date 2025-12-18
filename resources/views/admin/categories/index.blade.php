@extends('admin.layouts.app')

@section('title', 'الأصناف')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header-admin">
            <h1 class="page-title-admin">الأصناف</h1>
            <p class="page-subtitle-admin">إدارة أصناف المنتجات</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-plus-lg me-2"></i>إضافة صنف
        </a>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;"></th>
                        <th>الصنف</th>
                        <th>عدد المنتجات</th>
                        <th>الحالة</th>
                        <th style="width: 120px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <img src="{{ $category->image }}" alt="{{ $category->name_ar }}" class="rounded"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <strong>{{ $category->name_ar }}</strong>
                                <br><small class="text-muted">{{ $category->name }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $category->products_count }} منتج</span>
                            </td>
                            <td>
                                @if ($category->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد؟ لا يمكن حذف صنف يحتوي على منتجات.')">
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
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-grid display-4 d-block mb-3"></i>
                                لا توجد أصناف
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
