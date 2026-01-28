@extends('layouts.app')

@section('title', 'الصفحة غير موجودة - إمبابي كافيه')

@section('content')
    <div class="error-page">
        <div class="error-container">
            <div class="error-content glass-card" data-aos="zoom-in">
                <!-- Animated Coffee Icon -->
                <div class="error-icon">
                    <div class="coffee-cup-error">
                        <i class="bi bi-cup-hot"></i>
                        <span class="question-mark">?</span>
                    </div>
                </div>

                <!-- Error Code -->
                <h1 class="error-code">404</h1>

                <!-- Error Message -->
                <h2 class="error-title">الصفحة غير موجودة</h2>
                <p class="error-description">
                    عذراً، يبدو أن هذه الصفحة قد انتهت صلاحيتها مثل القهوة الباردة!
                    <br>الصفحة التي تبحث عنها غير موجودة أو تم نقلها.
                </p>

                <!-- Actions -->
                <div class="error-actions">
                    <a href="{{ url('/') }}" class="btn btn-golden btn-lg">
                        <i class="bi bi-house me-2"></i>العودة للرئيسية
                    </a>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-golden btn-lg">
                        <i class="bi bi-cup-hot me-2"></i>تصفح المنتجات
                    </a>
                </div>

                <!-- Floating Beans Decoration -->
                <div class="error-beans">
                    <span class="bean">☕</span>
                    <span class="bean">☕</span>
                    <span class="bean">☕</span>
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
            position: relative;
            overflow: hidden;
        }

        .error-icon {
            margin-bottom: 30px;
        }

        .coffee-cup-error {
            position: relative;
            display: inline-block;
            font-size: 5rem;
            color: var(--gold, #C9A227);
            animation: float 3s ease-in-out infinite;
        }

        .coffee-cup-error .question-mark {
            position: absolute;
            top: -10px;
            right: -20px;
            font-size: 2rem;
            color: var(--espresso, #2C1810);
            font-weight: bold;
            animation: bounce 1s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }
        }

        .error-code {
            font-size: 6rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--gold, #C9A227), #E8C547);
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

        .error-beans {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 20px;
            opacity: 0.3;
        }

        .bean {
            font-size: 1.5rem;
            animation: spin 4s linear infinite;
        }

        .bean:nth-child(2) {
            animation-delay: 0.5s;
        }

        .bean:nth-child(3) {
            animation-delay: 1s;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
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

            .coffee-cup-error {
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
