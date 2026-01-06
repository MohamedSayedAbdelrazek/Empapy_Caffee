@extends('admin.layouts.app')

@section('title', 'مستويات الولاء')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.dashboard') }}">نظام الولاء</a></li>
                        <li class="breadcrumb-item active">المستويات</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">🏆 مستويات العضوية VIP</h1>
            </div>
            <a href="{{ route('admin.loyalty.tiers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> إضافة مستوى
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Tiers Grid -->
        <div class="row g-4">
            @forelse($tiers as $tier)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100"
                        style="border-top: 4px solid {{ $tier->color }} !important;">
                        <div class="card-body text-center">
                            <!-- Icon -->
                            <div class="fs-1 mb-3">{{ $tier->icon }}</div>

                            <!-- Name -->
                            <h5 class="fw-bold mb-1" style="color: {{ $tier->color }};">{{ $tier->name }}</h5>
                            <p class="text-muted small mb-3">{{ $tier->slug }}</p>

                            <!-- Points Range -->
                            <div class="mb-3">
                                <span class="badge bg-secondary">
                                    {{ number_format($tier->min_points) }} -
                                    {{ $tier->max_points ? number_format($tier->max_points) : '∞' }} نقطة
                                </span>
                            </div>

                            <!-- Benefits -->
                            <div class="text-start small mb-3">
                                @if ($tier->discount_percent > 0)
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <span>خصم {{ $tier->discount_percent }}%</span>
                                    </div>
                                @endif
                                @if ($tier->free_shipping)
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <span>شحن مجاني</span>
                                    </div>
                                @endif
                                @if ($tier->points_multiplier > 1)
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <span>مضاعف نقاط x{{ $tier->points_multiplier }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Status -->
                            <span class="badge bg-{{ $tier->is_active ? 'success' : 'secondary' }}">
                                {{ $tier->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>

                        <div class="card-footer bg-transparent border-0 pt-0">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.loyalty.tiers.edit', $tier) }}"
                                    class="btn btn-sm btn-outline-primary flex-grow-1">
                                    <i class="bi bi-pencil me-1"></i> تعديل
                                </a>
                                <form action="{{ route('admin.loyalty.tiers.destroy', $tier) }}" method="POST"
                                    onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-trophy fs-1 text-muted"></i>
                        <p class="mt-3 text-muted">لا توجد مستويات بعد</p>
                        <a href="{{ route('admin.loyalty.tiers.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> إضافة أول مستوى
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
