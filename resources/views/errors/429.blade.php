@extends('layouts.app')

@section('title', 'طلبات كثيرة - إمبابي كافيه')

@section('content')
    <div class="error-page">
        <div class="error-container">
            <div class="error-content glass-card" data-aos="zoom-in">
                <!-- Animated Icon -->
                <div class="error-icon">
                    <div class="speed-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                </div>

                <!-- Error Code -->
                <h1 class="error-code">429</h1>

                <!-- Error Message -->
                <h2 class="error-title">طلبات كثيرة جداً!</h2>
                <p class="error-description">
                    لقد أرسلت طلبات كثيرة في وقت قصير.
                    <br>يرجى الانتظار قليلاً ثم المحاولة مرة أخرى.
                </p>

                <!-- Countdown Timer -->
                <div class="wait-timer">
                    <i class="bi bi-hourglass-split"></i>
                    <span>انتظر <strong id="countdown">60</strong> ثانية</span>
                </div>

                <!-- Actions -->
                <div class="error-actions">
                    <a href="{{ url('/') }}" class="btn btn-golden btn-lg">
                        <i class="bi bi-house me-2"></i>العودة للرئيسية
                    </a>
                    <button onclick="location.reload()" class="btn btn-outline-golden btn-lg">
                        <i class="bi bi-arrow-clockwise me-2"></i>إعادة المحاولة
                    </button>
                </div>

                <!-- Tip -->
                <div class="error-tip">
                    <i class="bi bi-info-circle"></i>
                    <span>هذا إجراء حماية لضمان أمان حسابك والموقع</span>
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

        .speed-icon {
            display: inline-block;
            font-size: 5rem;
            color: #fd7e14;
            animation: speedPulse 1s ease-in-out infinite;
        }

        @keyframes speedPulse {

            0%,
            100% {
                transform: scale(1);
                color: #fd7e14;
            }

            50% {
                transform: scale(1.1);
                color: #dc3545;
            }
        }

        .error-code {
            font-size: 6rem;
            font-weight: 900;
            background: linear-gradient(135deg, #fd7e14, #dc3545);
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
            margin-bottom: 25px;
        }

        .wait-timer {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, rgba(253, 126, 20, 0.1), rgba(220, 53, 69, 0.1));
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 1.1rem;
            color: #dc3545;
            margin-bottom: 30px;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .wait-timer i {
            font-size: 1.5rem;
            animation: flip 1s linear infinite;
        }

        @keyframes flip {

            0%,
            100% {
                transform: rotate(0deg);
            }

            50% {
                transform: rotate(180deg);
            }
        }

        .wait-timer strong {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .error-tip {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(23, 162, 184, 0.1);
            padding: 12px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            color: #0c5460;
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

            .speed-icon {
                font-size: 4rem;
            }

            .error-actions {
                flex-direction: column;
            }

            .error-actions .btn {
                width: 100%;
            }

            .error-tip {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <script>
        // Optional countdown timer
        let seconds = 60;
        const countdownEl = document.getElementById('countdown');

        if (countdownEl) {
            const timer = setInterval(() => {
                seconds--;
                countdownEl.textContent = seconds;

                if (seconds <= 0) {
                    clearInterval(timer);
                    countdownEl.parentElement.innerHTML =
                        '<strong style="color: #28a745;">يمكنك المحاولة الآن!</strong>';
                }
            }, 1000);
        }
    </script>
@endsection
