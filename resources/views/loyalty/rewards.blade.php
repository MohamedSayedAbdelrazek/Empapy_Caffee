@extends('layouts.app')

@section('title', 'المكافآت المتاحة')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/loyalty.css') }}">
@endpush

@section('content')
    <!-- Page Header for proper navbar visibility -->
    <div class="page-header" style="padding: 120px 0 40px; background: linear-gradient(135deg, #2C1810 0%, #3D2317 100%);">
        <div class="container text-center">
            <h1 class="page-title text-white" data-aos="fade-up">🎁 كتالوج المكافآت</h1>
            <p class="lead text-white-50" data-aos="fade-up" data-aos-delay="100">استبدل نقاطك بمكافآت حصرية!</p>

            <!-- Points Balance -->
            <div class="d-inline-flex align-items-center gap-3 px-4 py-3 bg-warning bg-opacity-25 rounded-pill mt-3"
                data-aos="fade-up" data-aos-delay="200">
                <i class="bi bi-coin text-warning fs-4"></i>
                <span class="fs-5 text-white">رصيدك:</span>
                <span class="points-counter fs-3 text-warning">{{ number_format($userPoints) }}</span>
                <span class="fs-5 text-white">نقطة</span>
            </div>
        </div>
    </div>

    <div class="container py-5">

        <!-- Filters -->
        <div class="glass-card p-3 mb-4" data-aos="fade-up">
            <form action="" method="GET" class="row g-3 align-items-center">
                <div class="col-auto">
                    <label class="form-label mb-0 fw-bold">عرض:</label>
                </div>
                <div class="col-auto">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="affordable" value="1"
                            id="affordableFilter" {{ request('affordable') ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        <label class="form-check-label" for="affordableFilter">المتاحة لي فقط</label>
                    </div>
                </div>
                <div class="col-auto">
                    <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">كل الأنواع</option>
                        <option value="discount_fixed" {{ request('type') === 'discount_fixed' ? 'selected' : '' }}>خصم ثابت
                        </option>
                        <option value="discount_percent" {{ request('type') === 'discount_percent' ? 'selected' : '' }}>خصم
                            نسبة</option>
                        <option value="free_shipping" {{ request('type') === 'free_shipping' ? 'selected' : '' }}>شحن مجاني
                        </option>
                        <option value="free_product" {{ request('type') === 'free_product' ? 'selected' : '' }}>منتج مجاني
                        </option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="points_asc" {{ request('sort', 'points_asc') === 'points_asc' ? 'selected' : '' }}>
                            الأقل نقاطاً أولاً</option>
                        <option value="points_desc" {{ request('sort') === 'points_desc' ? 'selected' : '' }}>الأكثر نقاطاً
                            أولاً</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>الأكثر شعبية</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Rewards Grid -->
        <div class="row g-4">
            @forelse($rewards as $reward)
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    @php
                        $canRedeem = $reward->user_can_redeem;
                        $isLocked = !$canRedeem['can'];
                    @endphp

                    <div class="reward-card {{ $reward->is_featured ? 'featured' : '' }} {{ $isLocked ? 'locked' : '' }}">
                        <!-- Image -->
                        <div class="reward-card-image">
                            @if ($reward->image)
                                <img src="{{ asset('storage/' . $reward->image) }}" alt="{{ $reward->name }}"
                                    class="img-fluid">
                            @else
                                <span>{{ $reward->icon }}</span>
                            @endif
                        </div>

                        <!-- Body -->
                        <div class="reward-card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="reward-card-title mb-0">{{ $reward->name }}</h6>
                                <span
                                    class="badge bg-{{ $reward->reward_type === 'discount_fixed' ? 'success' : ($reward->reward_type === 'discount_percent' ? 'info' : 'warning') }}">
                                    {{ $reward->reward_type_label }}
                                </span>
                            </div>

                            <p class="reward-card-description">{{ $reward->description }}</p>

                            <!-- Value Display -->
                            <div class="h5 fw-bold text-primary mb-3">
                                {{ $reward->value_display }}
                            </div>

                            <!-- Points Required -->
                            <div class="reward-card-points">
                                <i class="bi bi-coin"></i>
                                <span>{{ number_format($reward->points_required) }} نقطة</span>
                            </div>

                            <!-- Stock -->
                            @if ($reward->stock !== null)
                                <div class="mt-2">
                                    <small class="{{ $reward->stock > 0 ? 'text-success' : 'text-danger' }}">
                                        <i class="bi bi-box me-1"></i>
                                        {{ $reward->stock > 0 ? "متبقي {$reward->stock} فقط" : 'نفدت الكمية' }}
                                    </small>
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="reward-card-footer">
                            @if (!$isLocked)
                                <form action="{{ route('loyalty.redeem', $reward) }}" method="POST"
                                    onsubmit="return confirm('هل أنت متأكد من استبدال {{ number_format($reward->points_required) }} نقطة؟')">
                                    @csrf
                                    <button type="submit" class="reward-redeem-btn available">
                                        <i class="bi bi-gift"></i>
                                        استبدال الآن
                                    </button>
                                </form>
                            @else
                                <button class="reward-redeem-btn unavailable" disabled>
                                    <i class="bi bi-lock"></i>
                                    {{ $canRedeem['reason'] }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-gift fs-1 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">لا توجد مكافآت متاحة حالياً</h5>
                        <p class="text-muted">تحقق لاحقاً أو جرّب تغيير الفلتر</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-cart me-1"></i> تسوق الآن لكسب النقاط
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- How to Earn More -->
        <div class="glass-card p-4 mt-5" data-aos="fade-up">
            <h5 class="text-center mb-4">
                <i class="bi bi-question-circle me-2"></i>
                كيف تحصل على المزيد من النقاط؟
            </h5>
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="p-3">
                        <div class="fs-1 mb-2">🛒</div>
                        <h6>تسوق أكثر</h6>
                        <p class="small text-muted mb-0">كل 1 ج.م = 1 نقطة</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <div class="fs-1 mb-2">⭐</div>
                        <h6>قيّم المنتجات</h6>
                        <p class="small text-muted mb-0">10 نقاط لكل تقييم</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <div class="fs-1 mb-2">👥</div>
                        <h6>ادعُ صديق</h6>
                        <p class="small text-muted mb-0">200 نقطة لكل إحالة</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <div class="fs-1 mb-2">🎂</div>
                        <h6>عيد ميلادك</h6>
                        <p class="small text-muted mb-0">مكافأة خاصة!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="confetti-container" id="confettiContainer"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const colors = ['#FFD700', '#C9A227', '#8B4513', '#2ECC71', '#3498DB'];
                const container = document.getElementById('confettiContainer');

                for (let i = 0; i < 50; i++) {
                    setTimeout(() => {
                        const confetti = document.createElement('div');
                        confetti.className = 'confetti';
                        confetti.style.left = Math.random() * 100 + 'vw';
                        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                        confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                        container.appendChild(confetti);
                        setTimeout(() => confetti.remove(), 4000);
                    }, i * 50);
                }
            });
        </script>
    @endif
@endsection
