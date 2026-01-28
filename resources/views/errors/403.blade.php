@extends('layouts.app')

@section('title', 'غير مسموح - إمبابي كافيه')

@section('content')
    <div class="error-page">
        <div class="error-container">
            <div class="error-content glass-card" data-aos="zoom-in">
                <!-- Animated Icon -->
                <div class="error-icon">
                    <div class="lock-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>

                <!-- Error Code -->
                <h1 class="error-code">403</h1>

                <!-- Error Message -->
                <h2 class="error-title">غير مسموح بالوصول</h2>
                <p class="error-description">
                    عذراً، هذه المنطقة خاصة بفريق العمل فقط!
                    <br>ليس لديك الصلاحية للوصول إلى هذه الصفحة.
                </p>

                <!-- Actions -->
                <div class="error-actions">
                    <a href="{{ url('/') }}" class="btn btn-golden btn-lg">
                        <i class="bi bi-house me-2"></i>العودة للرئيسية
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-golden btn-lg">
                            <i class="bi bi-box-arrow-in-left me-2"></i>تسجيل الدخول
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2C1810 0%, #3D2317 50%, #2C1810 100%);
            padding: 40px 20px;
            position: relative;
            overflow: hidden;
        }

        .error-page::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23C9A227' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .error-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 600px;
        }

        .error-content {
            text-align: center;
            padding: 60px 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 30px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
        }

        .error-icon {
            margin-bottom: 30px;
        }

        .lock-icon {
            display: inline-block;
            font-size: 5rem;
            color: #6c757d;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .error-code {
            font-size: 6rem;
            font-weight: 900;
            background: linear-gradient(135deg, #6c757d, #adb5bd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            line-height: 1;
        }

        .error-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--espresso, #2C1810);
            margin: 20px 0 15px;
        }

        .error-description {
            color: #666;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        @media (max-width: 576px) {
            .error-content {
                padding: 40px 25px;
            }

            .error-code {
                font-size: 4rem;
            }

            .error-title {
                font-size: 1.4rem;
            }

            .lock-icon {
                font-size: 4rem;
            }

            .error-actions {
                flex-direction: column;
            }

            .error-actions .btn {
                width: 100%;
            }
        }
    </style>
@endsection
