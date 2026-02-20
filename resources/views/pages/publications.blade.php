<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publications - Parsa Besharat</title>

    <meta name="description" content="Parsa Besharat is an Iranian Researcher and AI Engineer.">
    <meta name="author" content="Parsa Besharat">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.tailwind = { config: { darkMode: 'class' } };
    </script>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
    
    <style>
        .swiper-slide {
            height: auto;
            display: flex;
            justify-content: center;
        }
        .expo-container {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease;
        }
        .expo-container:hover {
            transform: translateY(-5px);
        }
        .pub-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: inherit;
        }
        .pub-meta {
            font-size: 0.875rem;
            opacity: 0.8;
            margin-bottom: 1.5rem;
        }
        .glass-btn {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
            font-weight: 600;
            text-decoration: none;
            color: inherit;
            transition: background 0.3s ease;
        }
        .glass-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-4 lg:p-10 min-h-screen relative overflow-x-hidden">

    <div id="main-container" class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden h-[85vh] z-10 transition-colors duration-700">

        <div class="absolute top-6 right-8 flex items-center gap-5 z-50">
            <button id="theme-toggle" class="p-2.5 rounded-full ios-glass transition hover:scale-110">
                <span id="theme-icon-light" class="hidden text-sm">☀️</span>
                <span id="theme-icon-dark" class="hidden text-sm">🌙</span>
            </button>
        </div>

        @include('sidebar')

        <main class="flex-1 p-8 lg:p-14 relative overflow-y-auto">
            <div class="relative z-10 mt-12 lg:mt-0">
                
                <div id="publications">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 ios-glass text-gray-900 dark:text-white rounded-full text-sm font-bold mb-6">
                        📚 PUBLICATIONS
                    </span>
                    
                    <h1 class="text-4xl lg:text-5xl font-extrabold mb-8 tracking-tight text-gray-900 dark:text-white drop-shadow-sm">
                        Research <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-purple-600">Papers.</span>
                    </h1>
                    <hr class="border-gray-200 dark:border-gray-700 mb-8 opacity-50">

                    <div class="swiper myPublications pb-12">
                        <div class="swiper-wrapper">

                            <!-- CARD 1 -->
                            <div class="swiper-slide">
                                <div class="expo-container pub-slide text-gray-900 dark:text-white">
                                    <div class="expo-content">
                                        <h2 class="pub-title">Financial Forecasting Equations with Scientific Machine Learning</h2>
                                        <p class="pub-meta">TU Freiberg • June 23, 2025</p>
                                        <a href="https://www.researchgate.net/publication/392626868_CAPTCHA_Unmasked_The_Math_That_Outsmarts_Bots" class="glass-btn pub-btn" target="_blank">Read Publication</a>
                                    </div>
                                </div>
                            </div>

                            <!-- CARD 2 -->
                            <div class="swiper-slide">
                                <div class="expo-container pub-slide text-gray-900 dark:text-white">
                                    <div class="expo-content">
                                        <h2 class="pub-title">CAPTCHA Unmasked: The Math That Outsmarts Bots</h2>
                                        <p class="pub-meta">TU Freiberg • Jan 20, 2025</p>
                                        <a href="https://www.researchgate.net/publication/392626868_CAPTCHA_Unmasked_The_Math_That_Outsmarts_Bots" class="glass-btn pub-btn" target="_blank">Read Publication</a>
                                    </div>
                                </div>
                            </div>

                            <!-- CARD 3 -->
                            <div class="swiper-slide">
                                <div class="expo-container pub-slide text-gray-900 dark:text-white">
                                    <div class="expo-content">
                                        <h2 class="pub-title">AI and Blockchain — Enhancing Security & Transparency</h2>
                                        <p class="pub-meta">TU Freiberg • Jun 25, 2024</p>
                                        <a href="https://www.researchgate.net/publication/392626580_AI_and_Blockchain_Enhancing_Security_Transparency_and_Integrity" class="glass-btn pub-btn" target="_blank">Read Publication</a>
                                    </div>
                                </div>
                            </div>

                            <!-- CARD 4 -->
                            <div class="swiper-slide">
                                <div class="expo-container pub-slide text-gray-900 dark:text-white">
                                    <div class="expo-content">
                                        <h2 class="pub-title">Synergy of Blockchain</h2>
                                        <p class="pub-meta">Sept 20, 2023</p>
                                        <a href="https://www.researchgate.net/publication/392626581_Exploring_the_Synergy_of_Blockchain_and_Artificial_Intelligence" class="glass-btn pub-btn" target="_blank">Read Publication</a>
                                    </div>
                                </div>
                            </div>

                            <!-- CARD 5 -->
                            <div class="swiper-slide">
                                <div class="expo-container pub-slide text-gray-900 dark:text-white">
                                    <div class="expo-content">
                                        <h2 class="pub-title">An Analysis on Vulnerabilities (PHP)</h2>
                                        <p class="pub-meta">SAPCO • Dec 7, 2022</p>
                                        <a href="https://www.researchgate.net/publication/392627125_Vulnerability_appraisals_in_web_PHP" class="glass-btn pub-btn" target="_blank">Read Publication</a>
                                    </div>
                                </div>
                            </div>

                            <!-- CARD 6 -->
                            <div class="swiper-slide">
                                <div class="expo-container pub-slide text-gray-900 dark:text-white">
                                    <div class="expo-content">
                                        <h2 class="pub-title">Data Mining Usage in CRM</h2>
                                        <p class="pub-meta">SAPCO • Oct 10, 2022</p>
                                        <a href="https://www.researchgate.net/publication/392626582_Data_mining_usage_in_Customer_Relation_management_CRM" class="glass-btn pub-btn" target="_blank">Read Publication</a>
                                    </div>
                                </div>
                            </div>

                            <!-- CARD 7 -->
                            <div class="swiper-slide">
                                <div class="expo-container pub-slide text-gray-900 dark:text-white">
                                    <div class="expo-content">
                                        <h2 class="pub-title">Quantum-dot Cellular Automata (QCA)</h2>
                                        <p class="pub-meta">Islamic Azad University • Apr 15, 2019</p>
                                        <a href="https://www.researchgate.net/publication/392637941_Quantum-dot_Cellular_Automata_QCA" class="glass-btn pub-btn" target="_blank">Read Publication</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="swiper-pagination pub-pagination"></div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".myPublications", {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 2, // Adjust based on sidebar width
                }
            }
        });
    </script>
</body>
</html>