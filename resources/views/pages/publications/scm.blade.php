<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Forecasting Equations with Scientific Machine Learning - Parsa Besharat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>window.tailwind = { config: { darkMode: 'class' } };</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
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
        <main class="flex-1 p-6 lg:p-10 overflow-y-auto scrollbar-hide">
            <div class="max-w-4xl mx-auto space-y-12 pb-20">
                
                <header class="text-center space-y-6 pt-4">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 ios-glass text-blue-600 dark:text-blue-400 rounded-full text-sm font-bold shadow-sm">
                        📄 RESEARCH PAPER
                    </span>
                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white drop-shadow-sm leading-tight">
                        Financial Forecasting Equations with Scientific Machine Learning
                    </h1>
                    
                    <div class="flex flex-col items-center justify-center gap-2 text-gray-600 dark:text-gray-400 font-medium">
                        <p class="text-lg text-gray-800 dark:text-gray-200 font-semibold">Parsa Besharat</p>
                        <p class="text-sm">June 2025</p>
                    </div>
                </header>

                <section class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md rounded-3xl p-8 border border-white/20 shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-gradient-to-b from-blue-500 to-purple-600"></div>
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Abstract</h2>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-justify mb-4">
                        Financial markets are inherently nonlinear, dynamic, and noisy, posing significant challenges for traditional time series forecasting methods. This document explores a novel approach to financial forecasting by integrating Scientific Machine Learning (SciML) techniques, specifically the Sparse Identification of Nonlinear Dynamics (SINDy) algorithm, with domain knowledge from financial time series analysis and advanced feature engineering. We aim to discover interpretable differential equations that govern market behavior.
                    </p>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-justify">
                        Financial concepts such as returns and volatility are incorporated as features, guided by methodologies. The resulting hybrid framework enables both data-driven discovery of governing equations and quantitative forecasting, with an emphasis on sparse, explainable models. Our experiments on cryptocurrency and equity datasets demonstrate the potential of SINDy-based models to extract meaningful dynamics and offer competitive forecasting performance, especially when enhanced with carefully engineered financial features. This work contributes to the growing field of explainable SciML applications in finance, bridging theoretical dynamics and real-world market signals.
                    </p>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 uppercase tracking-wider">Keywords</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold rounded-full">Financial Forecasting</span>
                            <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold rounded-full">Scientific Machine Learning (SciML)</span>
                            <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold rounded-full">SINDy Algorithm</span>
                            <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold rounded-full">Time Series Analysis</span>
                            <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold rounded-full">Explainable AI</span>
                        </div>
                    </div>
                </section>

                <div class="flex justify-center pt-4">
                    <a href="https://www.researchgate.net/publication/393173017_Financial_Forecasting_Equations_with_Scientific_Machine_Learning" target="_blank" 
                       class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-full font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Read Full Research Paper
                    </a>
                </div>

            </div>
        </main>
    </div>
</body>
</html>