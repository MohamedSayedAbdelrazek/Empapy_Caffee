@extends('layouts.app')

@section('title', 'خطأ في الخادم - إمبابي كافيه')

@section('content')
    <div class="error-page">
        <div class="error-container">
            <div class="error-content glass-card" data-aos="zoom-in">
                <!-- Animated Icon -->
                <div class="error-icon">
                    <div class="broken-cup">
                        <i class="bi bi-emoji-dizzy"></i>
                    </div>
                </div>

                <!-- Error Code -->
                <h1 class="error-code">500</h1>

                <!-- Error Message -->
                <h2 class="error-title">عذراً، حدث خطأ!</h2>
                <p class="error-description">
                    يبدو أن آلة القهوة لدينا تحتاج صيانة!
                    <br>نعتذر عن هذا الخطأ، فريقنا يعمل على إصلاحه.
                </p>

                <!-- Actions -->
                <div class="error-actions">
                    <a href="{{ url('/') }}" class="btn btn-golden btn-lg">
                        <i class="bi bi-house me-2"></i>العودة للرئيسية
                    </a>
                    <a href="{{ url('/contact') }}" class="btn btn-outline-golden btn-lg">
                        <i class="bi bi-envelope me-2"></i>تواصل معنا
                    </a>
                </div>

                <!-- Steam Animation -->
                <div class="error-steam">
                    <span></span>
                    <span></span>
                    <span></span>
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

        .broken-cup {
            display: inline-block;
            font-size: 5rem;
            color: #dc3545;
            animation: shake 0.5s ease-in-out infinite;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px) rotate(-5deg);
            }

            75% {
                transform: translateX(5px) rotate(5deg);
            }
        }

        .error-code {
            font-size: 6rem;
            font-weight: 900;
            background: linear-gradient(135deg, #dc3545, #ff6b6b);
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

        .error-steam {
            position: absolute;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .error-steam span {
            width: 8px;
            height: 30px;
            background: rgba(201, 162, 39, 0.3);
            border-radius: 50%;
            animation: steam 1.5s ease-out infinite;
        }

        .error-steam span:nth-child(2) {
            animation-delay: 0.3s;
        }

        .error-steam span:nth-child(3) {
            animation-delay: 0.6s;
        }

        @keyframes steam {
            0% {
                transform: translateY(0) scaleX(1);
                opacity: 0;
            }

            15% {
                opacity: 1;
            }

            50% {
                transform: translateY(-30px) scaleX(1.5);
            }

            100% {
                transform: translateY(-60px) scaleX(2);
                opacity: 0;
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

            .broken-cup {
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
