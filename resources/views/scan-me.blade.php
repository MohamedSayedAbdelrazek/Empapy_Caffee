<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>امسح واطلب - إمبابي كافي</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&family=Reem+Kufi:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Cairo', 'sans-serif'],
                        kufi: ['"Reem Kufi"', 'sans-serif'],
                    },
                    colors: {
                        coffee: {
                            900: '#1a0f0a',
                            800: '#2c1810',
                            600: '#5c3a2a',
                            400: '#8b5e3c',
                        },
                        gold: {
                            300: '#d4af37',
                            400: '#c5a028',
                            500: '#b8860b',
                        }
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                    }
                }
            }
        }
    </script>

    <style>
        @layer utilities {
            .text-glow {
                text-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
            }

            .card-shadow {
                box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.7);
            }
        }

        .decorative-pattern {
            background-color: #1a0f0a;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%232c1810' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            html,
            body {
                height: 100%;
                width: 100%;
                margin: 0;
                padding: 0;
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                overflow: hidden;
                /* Prevent 2nd page */
            }

            .no-print {
                display: none !important;
            }

            .decorative-pattern {
                background: none !important;
            }

            .print-container {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) scale(1) !important;
                /* Center perfectly */
                box-shadow: none !important;
                margin: 0 !important;
                width: 90% !important;
                /* Leave some margin */
                height: 95% !important;
                max-width: none !important;
                border: 4px solid #b8860b !important;
                /* Add gold border for print */
                border-radius: 0 !important;
            }
        }
    </style>
</head>

<body class="min-h-screen decorative-pattern text-coffee-900 font-sans flex items-center justify-center p-4">

    <!-- Poster Card Container -->
    <div id="poster"
        class="print-container relative bg-white w-full max-w-md aspect-[1/1.414] overflow-hidden rounded-xl card-shadow flex flex-col items-center justify-between p-8 md:p-12 transition-transform duration-700 hover:scale-[1.01]">

        <!-- Decorative Border -->
        <div class="absolute inset-3 border-2 border-coffee-800/20 pointer-events-none rounded-lg z-0">
            <div class="absolute top-0 right-0 w-12 h-12 border-t-4 border-r-4 border-gold-500"></div>
            <div class="absolute top-0 left-0 w-12 h-12 border-t-4 border-l-4 border-gold-500"></div>
            <div class="absolute bottom-0 right-0 w-12 h-12 border-b-4 border-r-4 border-gold-500"></div>
            <div class="absolute bottom-0 left-0 w-12 h-12 border-b-4 border-l-4 border-gold-500"></div>
        </div>

        <!-- Top Section: Brand -->
        <div class="text-center relative z-10 w-full pt-6 flex-shrink-0">
            <div class="mx-auto w-28 h-28 mb-4 relative">
                <div class="absolute inset-0 bg-gold-300 rounded-full opacity-20 animate-pulse"></div>
                <img src="{{ asset('logo.webp') }}" alt="Empapy Caffe"
                    class="relative w-full h-full object-cover rounded-full border-2 border-gold-500 shadow-lg p-1 bg-white">
            </div>

            <h1 class="font-sans text-6xl font-black text-coffee-900 mb-6 tracking-tight drop-shadow-sm">
                إمبابي كافي
            </h1>

            <!-- Enhanced Slogan Section -->
            <div class="flex items-center justify-center gap-6 opacity-100 my-6">
                <span class="h-[2px] w-12 bg-gradient-to-l from-gold-500 to-transparent"></span>
                <p class="text-3xl text-gold-500 font-black font-kufi tracking-wide relative px-4 py-2">
                    <span class="absolute inset-0 bg-coffee-900 opacity-5 -skew-x-12 rounded-lg"></span>
                    مزاجك .. عندنا
                </p>
                <span class="h-[2px] w-12 bg-gradient-to-r from-gold-500 to-transparent"></span>
            </div>
        </div>

        <!-- Middle Section: QR Code -->
        <div class="flex-1 flex flex-col items-center justify-center w-full py-4 relative z-10">
            <div class="relative group transform scale-110">
                <!-- QR Frame -->
                <div
                    class="absolute -inset-5 bg-gradient-to-tr from-gold-300 via-gold-500 to-coffee-400 rounded-3xl opacity-100 shadow-2xl">
                </div>
                <div class="absolute -inset-5 bg-coffee-800 rounded-3xl opacity-10 blur-sm"></div>

                <!-- QR Wrapper -->
                <div class="relative bg-white p-5 rounded-2xl shadow-inner border-2 border-white">
                    <img src="{{ asset('qr-empapy.png') }}" alt="Scan Menu"
                        class="w-64 h-64 object-contain mix-blend-multiply">

                    <!-- Middle Logo Overlay for QR -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-10">
                        <img src="{{ asset('logo.webp') }}" class="w-20 h-20 grayscale">
                    </div>
                </div>

                <!-- Scan Me Badge -->
                <div
                    class="absolute -bottom-7 left-1/2 transform -translate-x-1/2 bg-coffee-900 text-gold-300 px-8 py-3 rounded-full font-black text-xl shadow-xl whitespace-nowrap border-4 border-white ring-4 ring-gold-500/30">
                    امسح الكود
                </div>
            </div>

            <p
                class="mt-14 text-center text-coffee-800 font-bold text-xl max-w-[90%] leading-relaxed font-kufi drop-shadow-sm">
                "عايز تطلب؟ امسح الكود وشوف المنيو كامل <br> واطلب اللي نفسك فيه!"
            </p>
        </div>

        <!-- Footer Section -->
        <div class="w-full text-center pb-6 relative z-10 flex-shrink-0">
            <div class="flex justify-center space-x-8 space-x-reverse text-3xl text-coffee-800 mb-4 opacity-80">
                <!-- Decorative Icons -->
                <span class="transform hover:scale-110 transition-transform cursor-default filter drop-shadow">☕</span>
                <span class="transform hover:scale-110 transition-transform cursor-default filter drop-shadow">🍰</span>
                <span class="transform hover:scale-110 transition-transform cursor-default filter drop-shadow">✨</span>
            </div>
            <p class="text-sm text-coffee-600 font-bold tracking-[0.2em] dir-ltr font-mono" dir="ltr">
                www.empapy.com
            </p>
        </div>

        <!-- Bottom Pattern -->
        <div class="absolute bottom-0 left-0 w-full h-4 bg-gradient-to-r from-gold-300 via-coffee-600 to-gold-300">
        </div>
    </div>

    <!-- Interactive Controls (Hidden in Print) -->
    <div class="fixed bottom-6 flex gap-4 no-print z-50 flex-col md:flex-row">
        <button onclick="window.print()"
            class="bg-coffee-900 text-white px-8 py-3 rounded-full font-bold shadow-2xl hover:bg-coffee-800 hover:-translate-y-1 transition-all flex items-center justify-center gap-2 border border-gold-500/50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            اطبع البوستر
        </button>
        <a href="{{ route('home') }}"
            class="bg-white text-coffee-900 px-6 py-3 rounded-full font-bold shadow-xl hover:bg-gray-50 transition-all border border-gray-200 text-center">
            الرئيسية
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        // Trigger elegant confetti on load
        window.addEventListener('load', () => {
            const end = Date.now() + 1000;
            const colors = ['#d4af37', '#2c1810', '#ffffff'];

            (function frame() {
                confetti({
                    particleCount: 3,
                    angle: 60,
                    spread: 55,
                    origin: {
                        x: 0
                    },
                    colors: colors
                });
                confetti({
                    particleCount: 3,
                    angle: 120,
                    spread: 55,
                    origin: {
                        x: 1
                    },
                    colors: colors
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        });
    </script>
</body>

</html>
