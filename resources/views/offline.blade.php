<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>غير متصل - إمبابي كافيه</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2C1810">
    <link rel="icon" type="image/png" href="/icons/ios/32.png">
    <link rel="apple-touch-icon" href="/icons/ios/180.png">

    <style>
        :root {
            --espresso: #2C1810;
            --dark-roast: #3D2317;
            --gold: #C9A227;
            --cream: #FFF8E7;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(135deg, var(--espresso) 0%, var(--dark-roast) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--cream);
        }

        .container {
            text-align: center;
            max-width: 500px;
        }

        /* Coffee cup animation */
        .coffee-cup {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            position: relative;
        }

        .cup-body {
            width: 80px;
            height: 70px;
            background: var(--gold);
            border-radius: 0 0 40px 40px;
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .cup-handle {
            width: 20px;
            height: 30px;
            border: 5px solid var(--gold);
            border-left: none;
            border-radius: 0 20px 20px 0;
            position: absolute;
            right: 5px;
            top: 50px;
        }

        /* Steam animation */
        .steam-container {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .steam {
            width: 10px;
            height: 40px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: steam 2s ease-in-out infinite;
        }

        .steam:nth-child(1) {
            animation-delay: 0s;
        }

        .steam:nth-child(2) {
            animation-delay: 0.3s;
        }

        .steam:nth-child(3) {
            animation-delay: 0.6s;
        }

        @keyframes steam {

            0%,
            100% {
                opacity: 0;
                transform: translateY(0) scaleY(1);
            }

            50% {
                opacity: 0.6;
                transform: translateY(-20px) scaleY(1.5);
            }
        }

        /* Wifi icon with X */
        .wifi-off {
            width: 80px;
            height: 80px;
            margin: 20px auto;
            position: relative;
        }

        .wifi-off svg {
            width: 100%;
            height: 100%;
            fill: none;
            stroke: var(--gold);
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 15px;
            font-weight: 700;
            color: var(--gold);
        }

        p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: linear-gradient(135deg, var(--gold) 0%, #E8C547 100%);
            color: var(--espresso);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(201, 162, 39, 0.4);
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(201, 162, 39, 0.5);
        }

        .tips {
            margin-top: 40px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .tips h3 {
            color: var(--gold);
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .tips ul {
            list-style: none;
            text-align: right;
        }

        .tips li {
            padding: 8px 0;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tips li::before {
            content: '☕';
        }

        /* Floating beans decoration */
        .bean {
            position: fixed;
            width: 20px;
            height: 30px;
            background: var(--gold);
            opacity: 0.2;
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            animation: float 6s ease-in-out infinite;
        }

        .bean:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .bean:nth-child(2) {
            top: 20%;
            right: 15%;
            animation-delay: 1s;
        }

        .bean:nth-child(3) {
            bottom: 30%;
            left: 5%;
            animation-delay: 2s;
        }

        .bean:nth-child(4) {
            bottom: 15%;
            right: 10%;
            animation-delay: 3s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>
    <!-- Decorative beans -->
    <div class="bean"></div>
    <div class="bean"></div>
    <div class="bean"></div>
    <div class="bean"></div>

    <div class="container">
        <!-- Logo -->
        <img src="/icons/android/android-launchericon-192-192.png" alt="إمبابي كافيه" class="logo"
            onerror="this.style.display='none'">

        <!-- Coffee cup with steam -->
        <div class="coffee-cup">
            <div class="steam-container">
                <div class="steam"></div>
                <div class="steam"></div>
                <div class="steam"></div>
            </div>
            <div class="cup-body"></div>
            <div class="cup-handle"></div>
        </div>

        <!-- Wifi off icon -->
        <div class="wifi-off">
            <svg viewBox="0 0 24 24">
                <path d="M1 1l22 22M16.72 11.06A10.94 10.94 0 0 1 19 12.55" />
                <path d="M5 12.55a10.94 10.94 0 0 1 5.17-2.39" />
                <path d="M10.71 5.05A16 16 0 0 1 22.58 9" />
                <path d="M1.42 9a15.91 15.91 0 0 1 4.7-2.88" />
                <path d="M8.53 16.11a6 6 0 0 1 6.95 0" />
                <circle cx="12" cy="20" r="1" />
            </svg>
        </div>

        <h1>أنت غير متصل بالإنترنت</h1>

        <p>
            يبدو أنك فقدت الاتصال بالإنترنت. لا تقلق، يمكنك الاستمتاع بالمحتوى المحفوظ مسبقاً أو المحاولة مرة أخرى.
        </p>

        <button class="btn" onclick="window.location.reload()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <path d="M23 4v6h-6" />
                <path d="M1 20v-6h6" />
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
            </svg>
            حاول مرة أخرى
        </button>

        <div class="tips">
            <h3>نصائح للاتصال:</h3>
            <ul>
                <li>تأكد من تفعيل الواي فاي أو بيانات الهاتف</li>
                <li>حاول الاقتراب من جهاز الراوتر</li>
                <li>أعد تشغيل الاتصال بالإنترنت</li>
            </ul>
        </div>
    </div>

    <script>
        // Auto-refresh when back online
        window.addEventListener('online', () => {
            window.location.reload();
        });
    </script>
</body>

</html>
