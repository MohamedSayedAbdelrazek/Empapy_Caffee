<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2C1810">
    <title>أنت غير متصل - إمبابي كافيه</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="manifest" href="/manifest.json">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(145deg, #1a1a2e 0%, #16213e 50%, #0f0f1a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            overflow: hidden;
        }

        .offline-container {
            text-align: center;
            padding: 40px 24px;
            max-width: 400px;
            position: relative;
            z-index: 1;
        }

        /* Animated coffee cup */
        .coffee-cup {
            width: 120px;
            height: 120px;
            margin: 0 auto 32px;
            position: relative;
        }

        .cup-body {
            width: 80px;
            height: 70px;
            background: linear-gradient(145deg, #c9a227, #8b6914);
            border-radius: 0 0 35px 35px;
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 
                0 10px 30px rgba(201, 162, 39, 0.3),
                inset 0 -10px 20px rgba(0, 0, 0, 0.2);
        }

        .cup-handle {
            width: 25px;
            height: 35px;
            border: 6px solid #c9a227;
            border-left: none;
            border-radius: 0 15px 15px 0;
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
        }

        .cup-top {
            width: 90px;
            height: 15px;
            background: linear-gradient(145deg, #d4a84b, #c9a227);
            border-radius: 8px 8px 0 0;
            position: absolute;
            bottom: 70px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Steam animation */
        .steam-container {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 60px;
        }

        .steam {
            position: absolute;
            width: 8px;
            height: 30px;
            background: linear-gradient(to top, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.5));
            border-radius: 10px;
            animation: steam 2s ease-in-out infinite;
        }

        .steam:nth-child(1) {
            left: 15px;
            animation-delay: 0s;
        }

        .steam:nth-child(2) {
            left: 26px;
            animation-delay: 0.3s;
            height: 35px;
        }

        .steam:nth-child(3) {
            left: 37px;
            animation-delay: 0.6s;
        }

        @keyframes steam {
            0%, 100% {
                transform: translateY(0) scaleX(1);
                opacity: 0;
            }
            15% {
                opacity: 1;
            }
            50% {
                transform: translateY(-20px) scaleX(1.2);
                opacity: 0.5;
            }
            100% {
                transform: translateY(-40px) scaleX(0.8);
                opacity: 0;
            }
        }

        /* WiFi off icon */
        .wifi-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 28px;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        /* Text content */
        .offline-title {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 12px;
            color: #c9a227;
            text-shadow: 0 2px 20px rgba(201, 162, 39, 0.3);
        }

        .offline-message {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.8;
            margin-bottom: 32px;
        }

        /* Retry button */
        .retry-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            background: linear-gradient(135deg, #c9a227 0%, #a78419 100%);
            color: #1a1a2e;
            font-size: 1rem;
            font-weight: 700;
            font-family: inherit;
            border: none;
            border-radius: 100px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(201, 162, 39, 0.3);
        }

        .retry-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(201, 162, 39, 0.4);
        }

        .retry-btn:active {
            transform: translateY(0);
        }

        .retry-btn svg {
            width: 20px;
            height: 20px;
            animation: spin 2s linear infinite paused;
        }

        .retry-btn.loading svg {
            animation-play-state: running;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Background decorations */
        .bg-circle {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
        }

        .bg-circle-1 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(201, 162, 39, 0.1) 0%, transparent 70%);
            top: -100px;
            right: -100px;
        }

        .bg-circle-2 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
            bottom: -50px;
            left: -50px;
        }

        /* Floating coffee beans */
        .bean {
            position: fixed;
            width: 20px;
            height: 30px;
            background: linear-gradient(145deg, #3D2317, #2C1810);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            opacity: 0.3;
            animation: floatBean 20s linear infinite;
        }

        .bean::before {
            content: '';
            position: absolute;
            width: 2px;
            height: 60%;
            background: rgba(0, 0, 0, 0.3);
            left: 50%;
            top: 20%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .bean:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 18s; }
        .bean:nth-child(2) { left: 30%; animation-delay: -5s; animation-duration: 22s; }
        .bean:nth-child(3) { left: 50%; animation-delay: -10s; animation-duration: 20s; }
        .bean:nth-child(4) { left: 70%; animation-delay: -15s; animation-duration: 25s; }
        .bean:nth-child(5) { left: 90%; animation-delay: -8s; animation-duration: 19s; }

        @keyframes floatBean {
            0% {
                transform: translateY(100vh) rotate(0deg);
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
            }
        }

        /* Tip text */
        .offline-tip {
            margin-top: 32px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .offline-tip-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #c9a227;
            margin-bottom: 8px;
        }

        .offline-tip-text {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <!-- Background decorations -->
    <div class="bg-circle bg-circle-1"></div>
    <div class="bg-circle bg-circle-2"></div>
    
    <!-- Floating beans -->
    <div class="bean"></div>
    <div class="bean"></div>
    <div class="bean"></div>
    <div class="bean"></div>
    <div class="bean"></div>

    <div class="offline-container">
        <!-- Animated coffee cup -->
        <div class="coffee-cup">
            <div class="steam-container">
                <div class="steam"></div>
                <div class="steam"></div>
                <div class="steam"></div>
            </div>
            <div class="cup-top"></div>
            <div class="cup-body">
                <span class="wifi-icon">📵</span>
            </div>
            <div class="cup-handle"></div>
        </div>

        <h1 class="offline-title">أنت غير متصل بالإنترنت</h1>
        
        <p class="offline-message">
            لا تقلق! يمكنك الاستمتاع بقهوتك بينما تنتظر عودة الاتصال.
            <br>
            سنعود للعمل فور اتصالك بالشبكة.
        </p>

        <button class="retry-btn" onclick="retryConnection()">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
            </svg>
            إعادة المحاولة
        </button>

        <div class="offline-tip">
            <div class="offline-tip-title">💡 نصيحة</div>
            <div class="offline-tip-text">
                تحقق من اتصالك بالإنترنت أو حاول الاتصال بشبكة Wi-Fi أخرى
            </div>
        </div>
    </div>

    <script>
        function retryConnection() {
            const btn = document.querySelector('.retry-btn');
            btn.classList.add('loading');
            
            // Try to fetch the homepage
            fetch('/')
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        throw new Error('Still offline');
                    }
                })
                .catch(() => {
                    btn.classList.remove('loading');
                    // Show feedback
                    const tip = document.querySelector('.offline-tip-text');
                    tip.textContent = 'لا يزال الاتصال غير متاح. حاول مرة أخرى بعد قليل.';
                });
        }

        // Auto-retry when online
        window.addEventListener('online', () => {
            window.location.reload();
        });
    </script>
</body>
</html>
