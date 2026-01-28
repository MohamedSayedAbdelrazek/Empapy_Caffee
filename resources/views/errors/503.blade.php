<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الموقع تحت الصيانة - إمبابي كافيه</title>

    <!-- Google Fonts - Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2C1810 0%, #3D2317 50%, #2C1810 100%);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23C9A227' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .maintenance-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 600px;
        }

        .maintenance-content {
            text-align: center;
            padding: 60px 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 30px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
        }

        .maintenance-icon {
            margin-bottom: 30px;
        }

        .tools-icon {
            display: inline-block;
            font-size: 5rem;
            color: #C9A227;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(15deg);
            }

            50% {
                transform: rotate(0deg);
            }

            75% {
                transform: rotate(-15deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }

        .maintenance-code {
            font-size: 5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #17a2b8, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            line-height: 1;
        }

        .maintenance-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2C1810;
            margin: 20px 0 15px;
        }

        .maintenance-description {
            color: #666;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .coffee-brewing {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            padding: 20px;
            background: rgba(201, 162, 39, 0.1);
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .coffee-brewing i {
            font-size: 2rem;
            color: #C9A227;
            animation: steam 1.5s ease-in-out infinite;
        }

        @keyframes steam {

            0%,
            100% {
                transform: translateY(0);
                opacity: 1;
            }

            50% {
                transform: translateY(-10px);
                opacity: 0.5;
            }
        }

        .coffee-brewing span {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2C1810;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #C9A227, #E8C547);
            color: #2C1810;
            border-radius: 50%;
            font-size: 1.3rem;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-link:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(201, 162, 39, 0.4);
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            height: 60px;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: #C9A227;
        }

        @media (max-width: 576px) {
            .maintenance-content {
                padding: 40px 25px;
            }

            .maintenance-code {
                font-size: 3.5rem;
            }

            .maintenance-title {
                font-size: 1.4rem;
            }

            .tools-icon {
                font-size: 4rem;
            }
        }
    </style>
</head>

<body>
    <div class="maintenance-container">
        <div class="maintenance-content">
            <!-- Logo -->
            <div class="logo">
                <span class="logo-text">☕ إمبابي كافيه</span>
            </div>

            <!-- Icon -->
            <div class="maintenance-icon">
                <div class="tools-icon">
                    <i class="bi bi-tools"></i>
                </div>
            </div>

            <!-- Code -->
            <h1 class="maintenance-code">503</h1>

            <!-- Message -->
            <h2 class="maintenance-title">الموقع تحت الصيانة</h2>
            <p class="maintenance-description">
                نقوم بتحضير شيء مميز لك!
                <br>سنعود قريباً بتجربة أفضل.
            </p>

            <!-- Brewing Message -->
            <div class="coffee-brewing">
                <i class="bi bi-cup-hot-fill"></i>
                <span>جاري تحضير القهوة... يرجى الانتظار</span>
            </div>

            <!-- Social Links -->
            <div class="social-links">
                <a href="#" class="social-link" title="Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="#" class="social-link" title="Instagram">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="#" class="social-link" title="WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>
</body>

</html>
