@extends('layouts.app')

@section('title', 'سجل النقاط')

@push('styles')
    <link rel="stylesheet" href="{{ asset_version('css/loyalty.css') }}">
@endpush

@section('content')
    <!-- Page Header for proper navbar visibility -->
    <div class="page-header" style="padding: 120px 0 40px; background: linear-gradient(135deg, #2C1810 0%, #3D2317 100%);">
        <div class="container">
            <h1 class="page-title text-white" data-aos="fade-up">📜 سجل النقاط</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('loyalty.index') }}">نقاطي</a></li>
                    <li class="breadcrumb-item active text-white-50">سجل النقاط</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <!-- Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <p class="text-muted mb-0">جميع عمليات كسب واستخدام النقاط</p>
            <a href="{{ route('loyalty.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-right me-1"></i> العودة
            </a>
        </div>

        <!-- Filters -->
        <div class="glass-card p-3 mb-4">
            <form action="" method="GET" class="d-flex gap-3 align-items-center">
                <label class="form-label mb-0">تصفية حسب:</label>
                <select name="type" class="form-select form-select-sm" style="width: auto;"
                    onchange="this.form.submit()">
                    <option value="">الكل</option>
                    <option value="earned" {{ request('type') === 'earned' ? 'selected' : '' }}>مكتسبة</option>
                    <option value="redeemed" {{ request('type') === 'redeemed' ? 'selected' : '' }}>مستخدمة</option>
                    <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>تعديل</option>
                    <option value="expired" {{ request('type') === 'expired' ? 'selected' : '' }}>منتهية</option>
                </select>
            </form>
        </div>

        <!-- Transactions List -->
        <div class="glass-card">
            @forelse($transactions as $transaction)
                <div
                    class="d-flex align-items-center justify-content-between p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center {{ $transaction->is_positive ? 'bg-success' : 'bg-danger' }} bg-opacity-10"
                            style="width: 50px; height: 50px;">
                            <i
                                class="bi {{ $transaction->is_positive ? 'bi-plus-lg text-success' : 'bi-dash-lg text-danger' }} fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $transaction->description }}</h6>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ $transaction->created_at->format('Y/m/d - h:i A') }}
                            </small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="h5 mb-1 {{ $transaction->is_positive ? 'text-success' : 'text-danger' }}">
                            {{ $transaction->formatted_points }}
                        </div>
                        <small class="text-muted">الرصيد: {{ number_format($transaction->balance_after) }}</small>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-clock-history fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">لا توجد عمليات بعد</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($transactions->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
