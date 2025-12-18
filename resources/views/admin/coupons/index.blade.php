@extends('admin.layouts.app')

@section('title', 'الكوبونات')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header-admin">
            <h1 class="page-title-admin">الكوبونات</h1>
            <p class="page-subtitle-admin">إدارة كوبونات الخصم</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-plus-lg me-2"></i>إضافة كوبون
        </a>
    </div>

    <!-- Coupons Table -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>الاسم</th>
                        <th>النوع</th>
                        <th>القيمة</th>
                        <th>الاستخدام</th>
                        <th>الصلاحية</th>
                        <th>الحالة</th>
                        <th style="width: 120px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td><code class="bg-dark p-2 rounded">{{ $coupon->code }}</code></td>
                            <td>{{ $coupon->name_ar }}</td>
                            <td>
                                @if ($coupon->type === 'percentage')
                                    <span class="badge bg-info">نسبة مئوية</span>
                                @else
                                    <span class="badge bg-primary">مبلغ ثابت</span>
                                @endif
                            </td>
                            <td>
                                @if ($coupon->type === 'percentage')
                                    {{ $coupon->value }}%
                                @else
                                    {{ number_format($coupon->value) }} ج.م
                                @endif
                            </td>
                            <td>
                                {{ $coupon->usage_count }}
                                @if ($coupon->usage_limit)
                                    / {{ $coupon->usage_limit }}
                                @else
                                    / ∞
                                @endif
                            </td>
                            <td>
                                @if ($coupon->expires_at)
                                    @if ($coupon->expires_at->isPast())
                                        <span class="text-danger">منتهي</span>
                                    @else
                                        <small>{{ $coupon->expires_at->format('Y-m-d') }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                @if ($coupon->isValid())
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا الكوبون؟')">
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
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-ticket-perforated display-4 d-block mb-3"></i>
                                لا توجد كوبونات
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($coupons->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
@endsection
