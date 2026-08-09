<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publications - Parsa Besharat</title>

    <meta name="description" content="Read research papers and publications by Parsa Besharat on AI, Machine Learning, Blockchain, and Data Science. Featuring BlackWall, Moodium, and more.">
    <meta name="author" content="Parsa Besharat">
    <meta name="keywords" content="Publications, Research Papers, Parsa Besharat, AI Research, Machine Learning, Blockchain, Data Science, BlackWall, Moodium, QCA">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Publications - Parsa Besharat">
    <meta property="og:description" content="Read research papers and publications by Parsa Besharat on AI, Machine Learning, Blockchain, and Data Science. Featuring BlackWall, Moodium, and more.">
    <meta property="og:image" content="{{ asset('images/profile.jpg') }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Publications - Parsa Besharat">
    <meta name="twitter:description" content="Read research papers and publications by Parsa Besharat on AI, Machine Learning, Blockchain, and Data Science. Featuring BlackWall, Moodium, and more.">
    <meta name="twitter:image" content="{{ asset('images/profile.jpg') }}">

    <script>window.tailwind = { config: { darkMode: 'class' } };</script>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
</head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-E441FBGYXG"></script>
<script type="module" src="{{ asset('js/gtag.js') }}"></script>

<body
    class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-4 lg:p-10 min-h-screen relative overflow-x-hidden">

    <div id="main-container"
        class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden h-[85vh] z-10 transition-colors duration-700 animate-page-zoom-in">

        @include('top-header-controls')

        @include('sidebar')

        <main class="flex-1 p-6 pt-12 lg:p-10 lg:pt-14 overflow-y-auto scrollbar-hide">
            <div class="max-w-6xl mx-auto animate-page-slide-up">
                <div id="publications">
                    <span
                        class="inline-flex items-center gap-2 px-4 py-1.5 ios-glass text-gray-900 dark:text-white rounded-full text-sm font-bold mb-6">
                        {{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? '📚 PUBLIKATIONEN' : '📚 PUBLICATIONS' }}
                    </span>

                    <h1
                        class="text-4xl lg:text-5xl font-extrabold mb-8 tracking-tight text-gray-900 dark:text-white drop-shadow-sm">
                        {{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Forschungs' : 'Research' }} <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-purple-600">{{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Arbeiten.' : 'Papers.' }}</span>
                    </h1>
                    <hr class="border-gray-200 dark:border-gray-700 mb-8 opacity-50">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        <!-- Vectra -->
                        <div
                            class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                            <div
                                class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden relative group">
                                <img src="{{ asset('images/vectra.png') }}" alt="Vectra"
                                    class="w-full h-full object-cover">
                            </div>
                            <h2 class="text-xl font-bold mb-1 text-gray-800 dark:text-gray-100">Vectra: The Quarantine Matrix, Constraining Neural Hallucinations in 3D Gaussian Environments</h2>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-3">Jun 26, 2026</p>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow line-clamp-4">
                                As spatial computing and generative artificial intelligence converge, the necessity for robust, secure, and highly optimized integration architectures becomes strictly paramount. The Vectra Spatial Computing Protocol bridges the gap between high-fidelity digital twins and localized generative AI pipelines.
                            </p>
                            <a href="{{ route('publications.vectra_paper') }}"
                                class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                                Show Publication
                            </a>
                        </div>

                        <!-- BlackWall -->
                        <div
                            class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                            <div
                                class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden relative group">
                                <img src="{{ asset('images/blackwall.png') }}" alt="BlackWall"
                                    class="w-full h-full object-cover">

                            </div>
                            <h2 class="text-xl font-bold mb-1 text-gray-800 dark:text-gray-100">BlackWall - Protect an
                                AI from going rogue via an AI</h2>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-3">Jan 21, 2026</p>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow line-clamp-4">
                                The increasing integration of social media and conversational AI into daily life has
                                intensified concerns around the spread of harmful, illegal, and psychologically
                                sensitive content. This paper presents Blackwall, a domain-aware and interpretable
                                framework designed to identify, assess, and rank high-risk content across online
                                platforms.
                            </p>
                            <a href="{{ route('publications.blackwall_paper') }}"
                                class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                                Show Publication
                            </a>
                        </div>

                        <div
                            class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                            <div
                                class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden relative group">
                                <img src="{{ asset('images/moodium.png') }}" alt="Moodium"
                                    class="w-full h-full object-cover">

                            </div>
                            <h2 class="text-xl font-bold mb-1 text-gray-800 dark:text-gray-100">Moodium: From Words to
                                Feelings</h2>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-3">Aug 10, 2025</p>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow line-clamp-4">
                                Emotion recognition is a critical component of affective computing. This paper proposes
                                a culturally aware, LLM-integrated framework that fuses audio, visual, and textual data
                                using a staged attention mechanism with adaptive gating, laying the foundation for
                                culturally sensitive emotion-aware systems.
                            </p>
                            <a href="{{ route('publications.moodium') }}"
                                class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                                Show Publication
                            </a>
                        </div>

                        <div
                            class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                            <div
                                class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden relative group">
                                <img src="{{ asset('images/scm.jpeg') }}"
                                    alt="Financial Forecasting Equations" class="w-full h-full object-cover">

                            </div>
                            <h2 class="text-xl font-bold mb-1 text-gray-800 dark:text-gray-100">Financial Forecasting
                                Equations with Scientific Machine Learning</h2>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-3">TU Freiberg • Jun 23,
                                2025</p>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow line-clamp-4">
                                Financial markets are inherently nonlinear, dynamic, and noisy. This document explores a
                                novel approach to financial forecasting by integrating Scientific Machine Learning
                                (SciML) techniques, specifically the SINDy algorithm, with domain knowledge from
                                financial time series analysis.
                            </p>
                            <a href="{{ route('publications.scm') }}"
                                class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                                Show Publication
                            </a>
                        </div>

                        <div
                            class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                            <div
                                class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden relative group">
                                <img src="{{ asset('images/captcha.png') }}" alt="CAPTCHA Unmasked"
                                    class="w-full h-full object-cover">

                            </div>
                            <h2 class="text-xl font-bold mb-1 text-gray-800 dark:text-gray-100">CAPTCHA Unmasked: The
                                Math That Outsmarts Bots</h2>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-3">TU Freiberg • Jan 20,
                                2025</p>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow line-clamp-4">
                                It breaks down image processing tricks like distortions, warping, and noise, making
                                CAPTCHAs harder to crack. There’s also a focus on machine learning, especially neural
                                networks, to analyze CAPTCHA images and improve security, predicting where CAPTCHA tech
                                is headed.
                            </p>
                            <a href="{{ route('publications.captcha') }}"
                                class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                                Show Publication
                            </a>
                        </div>

                        <div
                            class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                            <div
                                class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden relative group">
                                <img src="{{ asset('images/ai-block.png') }}" alt="AI and Blockchain"
                                    class="w-full h-full object-cover">

                            </div>
                            <h2 class="text-xl font-bold mb-1 text-gray-800 dark:text-gray-100">AI and Blockchain,
                                Enhancing Security, Transparency, and Integrity</h2>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-3">TU Freiberg • Jun 25,
                                2024</p>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow line-clamp-4">
                                In this seminar, I have explained the main role of AI in Blockchain for Enhancing its
                                Security, Transparency, and Integrity. Multiple methods, and vulnerabilities have been
                                explained and analyzed and thus, the ways to prevent them with the help of AI have been
                                discussed.
                            </p>
                            <a href="{{ route('publications.ai_blockchain') }}"
                                class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                                Show Publication
                            </a>
                        </div>

                        <div
                            class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                            <div
                                class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden relative group">
                                <img src="{{ asset('images/blockchain.jpg') }}" alt="Synergy of Blockchain"
                                    class="w-full h-full object-cover">

                            </div>
                            <h2 class="text-xl font-bold mb-1 text-gray-800 dark:text-gray-100">Synergy of Blockchain
                            </h2>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-3">Sep 20, 2023</p>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow line-clamp-4">
                                This essay explores the synergy between blockchain and artificial intelligence (AI),
                                showcasing their transformative potential in various industries.
                            </p>
                            <a href="{{ route('publications.synergy_blockchain') }}"
                                class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                                Show Publication
                            </a>
                        </div>

                        <div
                            class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                            <div
                                class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden relative group">
                                <img src="{{ asset('images/vuls.png') }}" alt="PHP Vulnerabilities"
                                    class="w-full h-full object-cover">

                            </div>
                            <h2 class="text-xl font-bold mb-1 text-gray-800 dark:text-gray-100">An Analysis on
                                Vulnerabilities (PHP)</h2>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-3">Sapco • Dec 7, 2022
                            </p>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow line-clamp-4">
                                The article likely serves as a comprehensive guide on fortifying PHP websites against
                                prevalent security threats. It probably delves into identifying and mitigating common
                                vulnerabilities.
                            </p>
                            <a href="{{ route('publications.php_vuls') }}"
                                class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                                Show Publication
                            </a>
                        </div>

                        <div
                            class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                            <div
                                class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden relative group">
                                <img src="{{ asset('images/crm.png') }}" alt="Data Mining CRM"
                                    class="w-full h-full object-cover">

                            </div>
                            <h2 class="text-xl font-bold mb-1 text-gray-800 dark:text-gray-100">Data Mining Usage in CRM
                            </h2>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-3">Sapco • Oct 10, 2022
                            </p>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow line-clamp-4">
                                This article describes how recent advancements in data technology and the internet have
                                led to a significant shift in communication and advertising strategies.
                            </p>
                            <a href="{{ route('publications.crm') }}"
                                class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                                Show Publication
                            </a>
                        </div>

                        <div
                            class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                            <div
                                class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden relative group">
                                <img src="{{ asset('images/qca.png') }}" alt="QCA" class="w-full h-full object-cover">

                            </div>
                            <h2 class="text-xl font-bold mb-1 text-gray-800 dark:text-gray-100">Quantum-dot Cellular
                                Automata (QCA)</h2>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-3">Islamic Azad
                                University • Apr 15, 2019</p>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow line-clamp-4">
                                QCA, focusing on its innovative approach that harnesses the quantum mechanical
                                properties of electrons within quantum dots for data representation and processing.
                            </p>
                            <a href="{{ route('publications.qca') }}"
                                class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                                Show Publication
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>