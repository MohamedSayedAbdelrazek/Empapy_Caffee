@extends('layouts.app')

@section('title', 'نقاطي - برنامج الولاء')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/loyalty.css') }}">
@endpush

@section('content')
    <!-- Page Header for proper navbar visibility -->
    <div class="page-header" style="padding: 120px 0 40px; background: linear-gradient(135deg, #2C1810 0%, #3D2317 100%);">
        <div class="container text-center">
            <h1 class="page-title text-white" data-aos="fade-up">🎖️ برنامج الولاء</h1>
            <p class="lead text-white-50" data-aos="fade-up" data-aos-delay="100">اجمع النقاط واستبدلها بمكافآت حصرية!</p>
        </div>
    </div>

    <div class="container py-5">

        <!-- Main Stats Row -->
        <div class="row g-4 mb-5">
            <!-- Points Card -->
            <div class="col-lg-6" data-aos="fade-up">
                <div class="glass-card p-4 text-center h-100">
                    <div class="mb-3">
                        <i class="bi bi-coin text-warning fs-1"></i>
                    </div>
                    <h6 class="text-muted mb-2">رصيدك الحالي</h6>
                    <div class="points-counter mb-2">{{ number_format($loyalty->available_points) }}</div>
                    <p class="mb-0 text-muted">نقطة متاحة</p>
                </div>
            </div>

            <!-- Referrals Card -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="glass-card p-4 text-center h-100">
                    <div class="mb-3">
                        <i class="bi bi-people text-info fs-1"></i>
                    </div>
                    <h6 class="text-muted mb-2">إحالات ناجحة</h6>
                    <h2 class="fw-bold text-info mb-2">{{ $stats['referrals'] }}</h2>
                    <a href="{{ route('loyalty.referral') }}" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-share me-1"></i> ادعُ أصدقاءك
                    </a>
                </div>
            </div>
        </div>

        <!-- Featured Rewards -->
        @if ($featuredRewards->count() > 0)
            <div class="mb-5" data-aos="fade-up">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-gift me-2"></i>
                        مكافآت مميزة
                    </h4>
                    <a href="{{ route('loyalty.rewards') }}" class="btn btn-outline-primary">
                        عرض الكل <i class="bi bi-arrow-left ms-1"></i>
                    </a>
                </div>
                <div class="row g-4">
                    @foreach ($featuredRewards as $reward)
                        <div class="col-md-4">
                            <div
                                class="reward-card {{ $reward->is_featured ? 'featured' : '' }} {{ $loyalty->available_points < $reward->points_required ? 'locked' : '' }}">
                                <div class="reward-card-image">
                                    @if ($reward->image)
                                        <x-optimized-image :src="asset('storage/' . $reward->image)" :alt="$reward->name"
                                            class="img-fluid" />
                                    @else
                                        <span>{{ $reward->icon }}</span>
                                    @endif
                                </div>
                                <div class="reward-card-body">
                                    <h6 class="reward-card-title">{{ $reward->name }}</h6>
                                    <p class="reward-card-description">{{ $reward->description }}</p>
                                    <div class="reward-card-points">
                                        <i class="bi bi-coin"></i>
                                        <span>{{ number_format($reward->points_required) }} نقطة</span>
                                    </div>
                                </div>
                                <div class="reward-card-footer">
                                    @if ($loyalty->available_points >= $reward->points_required)
                                        <form action="{{ route('loyalty.redeem', $reward) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="reward-redeem-btn available">
                                                <i class="bi bi-gift"></i>
                                                استبدال الآن
                                            </button>
                                        </form>
                                    @else
                                        <button class="reward-redeem-btn unavailable" disabled>
                                            <i class="bi bi-lock"></i>
                                            تحتاج
                                            {{ number_format($reward->points_required - $loyalty->available_points) }} نقطة
                                            إضافية
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Pending Redemptions & Recent Transactions -->
        <div class="row g-4">
            <!-- Pending Redemptions -->
            @if ($pendingRedemptions->count() > 0)
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="glass-card p-4">
                        <h5 class="mb-4">
                            <i class="bi bi-ticket-perforated me-2 text-success"></i>
                            مكافآتك الجاهزة للاستخدام
                        </h5>
                        @foreach ($pendingRedemptions as $redemption)
                            <div
                                class="d-flex align-items-center justify-content-between p-3 mb-2 bg-success bg-opacity-10 rounded-3">
                                <div class="d-flex align-items-center">
                                    <span class="fs-4 me-3">{{ $redemption->reward->icon ?? '🎁' }}</span>
                                    <div>
                                        <h6 class="mb-0">{{ $redemption->reward->name }}</h6>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <code class="bg-dark text-warning px-2 py-1 rounded"
                                                style="font-size: 0.85rem;">{{ $redemption->redemption_code }}</code>
                                            <button type="button" class="btn btn-sm btn-outline-success copy-code-btn"
                                                data-code="{{ $redemption->redemption_code }}" title="نسخ الكود">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if ($redemption->expires_at)
                                    <small class="text-danger">
                                        ينتهي {{ $redemption->expires_at->diffForHumans() }}
                                    </small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recent Transactions -->
            <div class="{{ $pendingRedemptions->count() > 0 ? 'col-lg-6' : 'col-12' }}" data-aos="fade-up">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            آخر العمليات
                        </h5>
                        <a href="{{ route('loyalty.history') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                    </div>

                    @if ($transactions->count() > 0)
                        <div class="transaction-timeline">
                            @foreach ($transactions as $transaction)
                                <div class="transaction-item {{ $transaction->type }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span
                                                class="transaction-points {{ $transaction->is_positive ? 'positive' : 'negative' }}">
                                                {{ $transaction->formatted_points }}
                                            </span>
                                            <p class="mb-0">{{ $transaction->description }}</p>
                                        </div>
                                        <small class="transaction-meta">{{ $transaction->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            <p class="mb-0">لا توجد عمليات بعد</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="glass-card p-4 mt-5" data-aos="fade-up">
            <h5 class="mb-4 text-center">
                <i class="bi bi-lightning me-2"></i>
                طرق سريعة لكسب النقاط
            </h5>
            <div class="row g-3 text-center">
                <div class="col-md-4">
                    <div class="p-4 bg-warning bg-opacity-10 rounded-3">
                        <i class="bi bi-cart-check fs-1 text-warning d-block mb-2"></i>
                        <h6>اشترِ أكثر</h6>
                        <p class="small text-muted mb-0">
                            @if (isset($pointRules['order']) && $pointRules['order'])
                                احصل على {{ $pointRules['order']->value }} نقطة لكل
                                {{ $pointRules['order']->threshold ?? 1 }} ج.م
                            @else
                                احصل على نقاط مع كل طلب
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-info bg-opacity-10 rounded-3">
                        <i class="bi bi-star fs-1 text-info d-block mb-2"></i>
                        <h6>قيّم المنتجات</h6>
                        <p class="small text-muted mb-0">
                            @if (isset($pointRules['review']) && $pointRules['review'])
                                {{ $pointRules['review']->value }} نقطة لكل تقييم
                            @else
                                احصل على نقاط مقابل التقييمات
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-success bg-opacity-10 rounded-3">
                        <i class="bi bi-people fs-1 text-success d-block mb-2"></i>
                        <h6>ادعُ أصدقاءك</h6>
                        <p class="small text-muted mb-0">
                            @if (isset($pointRules['referral']) && $pointRules['referral'])
                                {{ $pointRules['referral']->value }} نقطة لكل إحالة ناجحة
                            @else
                                احصل على نقاط مقابل الإحالات
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <!-- Success Toast with Confetti -->
        <div class="confetti-container" id="confettiContainer"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Show confetti
                const colors = ['#FFD700', '#C9A227', '#8B4513', '#2ECC71', '#3498DB'];
                const container = document.getElementById('confettiContainer');

                for (let i = 0; i < 50; i++) {
                    setTimeout(() => {
                        const confetti = document.createElement('div');
                        confetti.className = 'confetti';
                        confetti.style.left = Math.random() * 100 + 'vw';
                        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                        confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                        confetti.style.transform = 'rotate(' + Math.random() * 360 + 'deg)';
                        container.appendChild(confetti);

                        setTimeout(() => confetti.remove(), 4000);
                    }, i * 50);
                }
            });
        </script>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.copy-code-btn').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const code = this.dataset.code;
                    const icon = this.querySelector('i');

                    try {
                        await navigator.clipboard.writeText(code);

                        // Visual feedback
                        icon.className = 'bi bi-clipboard-check';
                        this.classList.remove('btn-outline-success');
                        this.classList.add('btn-success');

                        // Show toast
                        if (window.Toast) {
                            window.Toast.success('تم النسخ!', 'تم نسخ الكود بنجاح');
                        }

                        // Reset after 2 seconds
                        setTimeout(() => {
                            icon.className = 'bi bi-clipboard';
                            this.classList.remove('btn-success');
                            this.classList.add('btn-outline-success');
                        }, 2000);

                    } catch (err) {
                        // Fallback for older browsers
                        const textArea = document.createElement('textarea');
                        textArea.value = code;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);

                        if (window.Toast) {
                            window.Toast.success('تم النسخ!', 'تم نسخ الكود بنجاح');
                        }
                    }
                });
            });
        });
    </script>
@endpush