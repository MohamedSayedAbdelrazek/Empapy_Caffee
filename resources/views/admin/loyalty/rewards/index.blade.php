@extends('admin.layouts.app')

@section('title', 'المكافآت')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.dashboard') }}">نظام الولاء</a></li>
                        <li class="breadcrumb-item active">المكافآت</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">🎁 إدارة المكافآت</h1>
            </div>
            <a href="{{ route('admin.loyalty.rewards.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> إضافة مكافأة
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Rewards Table -->
        <div class="admin-card">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>المكافأة</th>
                            <th>النوع</th>
                            <th>النقاط المطلوبة</th>
                            <th>المخزون</th>
                            <th>الاستخدامات</th>
                            <th>الحالة</th>
                            <th class="text-end">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rewards as $reward)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fs-4">{{ $reward->icon }}</span>
                                        <div>
                                            <strong>{{ $reward->name }}</strong>
                                            @if ($reward->is_featured)
                                                <span class="badge bg-warning ms-1">مميز</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">{{ Str::limit($reward->description, 40) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @switch($reward->reward_type)
                                        @case('discount_fixed')
                                            <span class="badge bg-success">خصم {{ $reward->reward_value }} ج.م</span>
                                        @break

                                        @case('discount_percent')
                                            <span class="badge bg-info">خصم {{ $reward->reward_value }}%</span>
                                        @break

                                        @case('free_shipping')
                                            <span class="badge bg-primary">شحن مجاني</span>
                                        @break

                                        @case('free_product')
                                            <span class="badge bg-warning">منتج مجاني</span>
                                        @break
                                    @endswitch
                                </td>
                                <td>
                                    <span class="fw-bold text-warning">{{ number_format($reward->points_required) }}</span>
                                    نقطة
                                </td>
                                <td>
                                    @if ($reward->stock === null)
                                        <span class="text-muted">غير محدود</span>
                                    @elseif($reward->stock > 0)
                                        <span class="text-success">{{ $reward->stock }}</span>
                                    @else
                                        <span class="text-danger">نفد</span>
                                    @endif
                                </td>
                                <td>{{ number_format($reward->times_redeemed) }}</td>
                                <td>
                                    @if ($reward->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-secondary">غير نشط</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.loyalty.rewards.edit', $reward) }}"
                                        class="btn btn-sm btn-outline-light">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.loyalty.rewards.destroy', $reward) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-gift fs-1 text-muted"></i>
                                        <p class="mt-3 text-muted">لا توجد مكافآت بعد</p>
                                        <a href="{{ route('admin.loyalty.rewards.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-lg me-1"></i> إضافة أول مكافأة
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
