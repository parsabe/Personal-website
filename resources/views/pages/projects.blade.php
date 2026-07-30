<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Parsa Besharat</title>

    <meta name="description" content="Explore Parsa Besharat's portfolio of projects in AI, Machine Learning, Data Science, and Software Engineering. Featuring BlackWall, MLMatrix, SCP, and more.">
    <meta name="author" content="Parsa Besharat">
    <meta name="keywords" content="Projects, Portfolio, AI Projects, Machine Learning, Data Science, BlackWall, MLMatrix, SCP, Parsa Besharat, Software Engineering">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Projects - Parsa Besharat">
    <meta property="og:description" content="Explore Parsa Besharat's portfolio of projects in AI, Machine Learning, Data Science, and Software Engineering. Featuring BlackWall, MLMatrix, SCP, and more.">
    <meta property="og:image" content="{{ asset('images/profile.jpg') }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Projects - Parsa Besharat">
    <meta name="twitter:description" content="Explore Parsa Besharat's portfolio of projects in AI, Machine Learning, Data Science, and Software Engineering. Featuring BlackWall, MLMatrix, SCP, and more.">
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
                <div id="projects">
                    <span
                        class="inline-flex items-center gap-2 px-4 py-1.5 ios-glass text-gray-900 dark:text-white rounded-full text-sm font-bold mb-6">
                        🚀 PROJECTS
                    </span>

                    <h1
                        class="text-4xl lg:text-5xl font-extrabold mb-8 tracking-tight text-gray-900 dark:text-white drop-shadow-sm">
                        Featured <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-purple-600">Projects.</span>
                    </h1>
                    <hr class="border-gray-200 dark:border-gray-700 mb-8 opacity-50">


                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <!-- Vectra -->
                    <div
                        class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/vectra.png') }}" alt="Vectra"
                                class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">Vectra</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            An end-to-end spatial computing framework engineered to generate, extract, and simulate high-fidelity 3D objects directly from simple visual and textual data.
                        </p>
                        <a href="{{ route('projects.vectra') }}"
                            class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- BlackWall -->
                    <div
                        class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/blackwall.png') }}" alt="BlackWall"
                                class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">BlackWall</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            A domain-aware and interpretable framework designed to identify, assess, and rank high-risk
                            content across online platforms.
                        </p>
                        <a href="{{ route('projects.blackwall') }}"
                            class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- MLMatrix -->
                    <div
                        class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/mlmatrix.png') }}" alt="MLMatrix"
                                class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">MLMatrix</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            This repository features four in-depth articles covering a range of cutting-edge
                            technologies and their applications.
                        </p>
                        <a href="{{ route('projects.mlmatrix') }}"
                            class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- FunRoot -->
                    <div
                        class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/funroot.png') }}" alt="FunRoot"
                                class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">FunRoot</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            This repository contains my projects that I do just for fun.
                        </p>
                        <a href="{{ route('projects.funroot') }}"
                            class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- Ceasar Toolkit -->
                    <div
                        class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/ceasar.png') }}" alt="Ceasar Toolkit"
                                class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">Ceasar Toolkit</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            Ceasar Cipher Toolkit is a free, open-source CLI framework for encoding and decoding files
                            using the classic Ceasar cipher.
                        </p>
                        <a href="{{ route('projects.ceasartoolkit') }}"
                            class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- SCP -->
                    <div
                        class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/scp.png') }}" alt="SCP" class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">SCP</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            This project provides a modular, extensible, and research-oriented deep learning pipeline
                            for image classification.
                        </p>
                        <a href="{{ route('projects.scp') }}"
                            class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- NetNexus -->
                    <div
                        class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/netnexus.jpg') }}" alt="NetNexus"
                                class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">NetNexus</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            A collection of innovative web projects including a dynamic website, an engaging online
                            riddle game, a social media platform, and a chat portal.
                        </p>
                        <a href="{{ route('projects.netnexus') }}"
                            class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- Parsai -->
                    <div
                        class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/parsai.jpg') }}" alt="Parsai" class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">Parsai</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            Parsai combines a powerful VS Code extension and a Telegram bot to provide versatile coding
                            assistance using OpenAI's GPT-4.
                        </p>
                        <a href="{{ route('projects.parsai') }}"
                            class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- HounaarToolkit -->
                    <div
                        class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/hounaar.png') }}" alt="HounaarToolkit"
                                class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">HounaarToolkit</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            HounaarToolkit is a versatile Python toolkit that provides a set of tools for various tasks,
                            including data analysis, YouTube video downloading, and more.
                        </p>
                        <a href="{{ route('projects.hounaartoolkit') }}"
                            class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- Sandika -->
                    <div
                        class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/sandika.jpg') }}" alt="Sandika"
                                class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">Sandika</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            Sandika is an Online Simple Social media platform where poeple can post texts, images,
                            videos, solve riddles and some more.
                        </p>
                        <a href="{{ route('projects.sandika') }}"
                            class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>




                </div>
                </div>
        </main>
    </div>
</body>

</html>