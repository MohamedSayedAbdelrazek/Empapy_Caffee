@extends('layouts.app')

@section('title', 'ادعُ صديق')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/loyalty.css') }}">
@endpush

@section('content')
    <div class="container py-5">
        <!-- Header -->
        <div class="text-center mb-5" data-aos="fade-up">
            <h1 class="display-5 fw-bold mb-3">👥 برنامج الإحالة</h1>
            <p class="lead text-muted">ادعُ أصدقاءك واكسبوا النقاط معاً!</p>
        </div>

        <!-- Referral Benefits -->
        <div class="row g-4 mb-5">
            <div class="col-md-6" data-aos="fade-left">
                <div class="glass-card p-4 h-100 text-center border-start border-5 border-success">
                    <div class="fs-1 mb-3">🎁</div>
                    <h4 class="text-success">أنت تحصل على</h4>
                    <div class="display-5 fw-bold text-success mb-2">
                        {{ number_format($referrerRule?->value ?? 200) }}
                    </div>
                    <p class="text-muted mb-0">نقطة عند أول طلب لصديقك</p>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-right">
                <div class="glass-card p-4 h-100 text-center border-start border-5 border-info">
                    <div class="fs-1 mb-3">🎉</div>
                    <h4 class="text-info">صديقك يحصل على</h4>
                    <div class="display-5 fw-bold text-info mb-2">
                        {{ number_format($referredRule?->value ?? 50) }}
                    </div>
                    <p class="text-muted mb-0">نقطة ترحيبية فور التسجيل</p>
                </div>
            </div>
        </div>

        <!-- Referral Code Box -->
        <div class="glass-card mb-5" data-aos="zoom-in">
            <div class="referral-code-box">
                <h5 class="mb-3">
                    <i class="bi bi-link-45deg me-2"></i>
                    رابط الإحالة الخاص بك
                </h5>

                <div class="referral-code" id="referralCode">{{ $referralCode }}</div>

                <div class="input-group mb-3 mx-auto" style="max-width: 500px;">
                    <input type="text" class="form-control text-center" id="referralLink" value="{{ $referralLink }}"
                        readonly>
                    <button class="btn btn-primary" type="button" onclick="copyReferralLink()">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>

                <!-- Share Buttons -->
                <div class="referral-share-buttons">
                    <button class="referral-share-btn whatsapp" onclick="shareWhatsApp()" title="واتساب">
                        <i class="bi bi-whatsapp"></i>
                    </button>
                    <button class="referral-share-btn facebook" onclick="shareFacebook()" title="فيسبوك">
                        <i class="bi bi-facebook"></i>
                    </button>
                    <button class="referral-share-btn twitter" onclick="shareTwitter()" title="تويتر">
                        <i class="bi bi-twitter-x"></i>
                    </button>
                    <button class="referral-share-btn copy" onclick="copyReferralLink()" title="نسخ">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-3" data-aos="fade-up">
                <div class="loyalty-stat-card">
                    <div class="loyalty-stat-icon referrals">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="loyalty-stat-value">{{ $totalReferrals }}</div>
                    <div class="loyalty-stat-label">إجمالي الإحالات</div>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="50">
                <div class="loyalty-stat-card">
                    <div class="loyalty-stat-icon" style="background: rgba(46, 204, 113, 0.15); color: #2ecc71;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="loyalty-stat-value">{{ $completedReferrals }}</div>
                    <div class="loyalty-stat-label">إحالات ناجحة</div>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="loyalty-stat-card">
                    <div class="loyalty-stat-icon" style="background: rgba(241, 196, 15, 0.15); color: #f1c40f;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="loyalty-stat-value">{{ $pendingReferrals }}</div>
                    <div class="loyalty-stat-label">بانتظار أول طلب</div>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="150">
                <div class="loyalty-stat-card">
                    <div class="loyalty-stat-icon points">
                        <i class="bi bi-coin"></i>
                    </div>
                    <div class="loyalty-stat-value">{{ number_format($totalPointsEarned) }}</div>
                    <div class="loyalty-stat-label">نقاط من الإحالات</div>
                </div>
            </div>
        </div>

        <!-- Referral History -->
        @if ($referrals->count() > 0)
            <div class="glass-card p-4" data-aos="fade-up">
                <h5 class="mb-4">
                    <i class="bi bi-clock-history me-2"></i>
                    سجل الإحالات
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>الصديق</th>
                                <th>الحالة</th>
                                <th>النقاط المكتسبة</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($referrals as $referral)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info-subtle text-info rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                style="width: 35px; height: 35px;">
                                                {{ mb_substr($referral->referred->name ?? 'U', 0, 1) }}
                                            </div>
                                            <span>{{ $referral->referred->name ?? 'مستخدم محذوف' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $referral->status_color }}">
                                            {{ $referral->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($referral->status === 'rewarded')
                                            <span
                                                class="text-success fw-bold">+{{ number_format($referral->referrer_points_awarded) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $referral->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- How it Works -->
        <div class="glass-card p-4 mt-5" data-aos="fade-up">
            <h5 class="text-center mb-4">
                <i class="bi bi-question-circle me-2"></i>
                كيف يعمل برنامج الإحالة؟
            </h5>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <span class="fs-2 fw-bold text-primary">1</span>
                    </div>
                    <h6>شارك رابطك</h6>
                    <p class="text-muted small mb-0">أرسل رابط الإحالة الخاص بك لأصدقائك</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <span class="fs-2 fw-bold text-primary">2</span>
                    </div>
                    <h6>صديقك يسجل ويشتري</h6>
                    <p class="text-muted small mb-0">عندما يسجل صديقك ويكمل أول طلب</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <span class="fs-2 fw-bold text-primary">3</span>
                    </div>
                    <h6>احصلوا على النقاط!</h6>
                    <p class="text-muted small mb-0">كلاكما يحصل على نقاط مكافأة</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyReferralLink() {
            const link = document.getElementById('referralLink').value;
            navigator.clipboard.writeText(link).then(() => {
                if (window.Toast) {
                    window.Toast.success('تم النسخ!', 'تم نسخ رابط الإحالة');
                } else {
                    alert('تم نسخ الرابط!');
                }
            });
        }

        function shareWhatsApp() {
            const text =
                `انضم إلى إمبابي كافيه واحصل على نقاط ترحيبية! سجّل من هذا الرابط: ${document.getElementById('referralLink').value}`;
            window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
        }

        function shareFacebook() {
            const url = document.getElementById('referralLink').value;
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
        }

        function shareTwitter() {
            const text = `انضم إلى إمبابي كافيه واحصل على نقاط ترحيبية!`;
            const url = document.getElementById('referralLink').value;
            window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`,
                '_blank');
        }
    </script>
@endsection
