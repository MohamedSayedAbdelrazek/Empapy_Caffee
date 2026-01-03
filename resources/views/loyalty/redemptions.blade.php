@extends('layouts.app')

@section('title', 'مكافآتي المستبدلة')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/loyalty.css') }}">
@endpush

@section('content')
    <div class="container py-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">🎁 مكافآتي المستبدلة</h1>
                <p class="text-muted mb-0">جميع المكافآت التي استبدلتها بنقاطك</p>
            </div>
            <a href="{{ route('loyalty.rewards') }}" class="btn btn-primary">
                <i class="bi bi-gift me-1"></i> استبدل المزيد
            </a>
        </div>

        <!-- Redemptions List -->
        <div class="row g-4">
            @forelse($redemptions as $redemption)
                <div class="col-md-6">
                    <div class="glass-card p-4">
                        <div class="d-flex gap-3">
                            <div class="fs-1">{{ $redemption->reward->icon ?? '🎁' }}</div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $redemption->reward->name_ar ?? 'مكافأة محذوفة' }}</h5>
                                <p class="text-muted small mb-2">{{ $redemption->reward->description_ar ?? '' }}</p>

                                <div class="d-flex gap-3 text-muted small">
                                    <span>
                                        <i class="bi bi-coin me-1 text-warning"></i>
                                        {{ number_format($redemption->points_spent) }} نقطة
                                    </span>
                                    <span>
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $redemption->created_at->format('Y/m/d') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span
                                    class="badge bg-{{ $redemption->status_color }} mb-1">{{ $redemption->status_label }}</span>
                                <br>
                                <code class="text-primary">{{ $redemption->redemption_code }}</code>
                            </div>
                            @if ($redemption->expires_at && $redemption->status === 'active')
                                <small class="text-danger">
                                    <i class="bi bi-clock me-1"></i>
                                    ينتهي {{ $redemption->expires_at->diffForHumans() }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-gift fs-1 text-muted"></i>
                        <p class="mt-3 mb-4 text-muted">لم تستبدل أي مكافآت بعد</p>
                        <a href="{{ route('loyalty.rewards') }}" class="btn btn-primary">
                            <i class="bi bi-gift me-1"></i> تصفح المكافآت
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($redemptions->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $redemptions->links() }}
            </div>
        @endif
    </div>
@endsection
