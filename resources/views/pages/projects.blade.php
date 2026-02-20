<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Parsa Besharat</title>

    <meta name="description" content="Parsa Besharat is an Iranian Researcher and AI Engineer. He is currently pursuing his
    MS.c degree in Data Science at the TU Freiberg University in Sachsen, Germany.">
    <meta name="author" content="Parsa Besharat">
    <meta name="keywords"
        content="Parsa Besharat, Researcher, AI Engineer, Data Scientist, Machine Learning, Deep Learning, Natural Language Processing, Computer Vision, TU Freiberg University, Germany">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">



    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.tailwind = { config: { darkMode: 'class' } };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
</head>

<body
    class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-4 lg:p-10 min-h-screen relative overflow-x-hidden">

    <div id="main-container"
        class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden h-[85vh] z-10 transition-colors duration-700">

        <div class="absolute top-6 right-8 flex items-center gap-5 z-50">
            <button id="theme-toggle" class="p-2.5 rounded-full ios-glass transition hover:scale-110">
                <span id="theme-icon-light" class="hidden text-sm">☀️</span>
                <span id="theme-icon-dark" class="hidden text-sm">🌙</span>
            </button>

            <div class="flex gap-2">
                <div class="w-3.5 h-3.5 rounded-full bg-[#ff5f56] shadow-sm border border-[#e0443e]"></div>
                <div class="w-3.5 h-3.5 rounded-full bg-[#ffbd2e] shadow-sm border border-[#dea123]"></div>
                <div class="w-3.5 h-3.5 rounded-full bg-[#27c93f] shadow-sm border border-[#1aab29]"></div>
            </div>
        </div>

        @include('sidebar')

        <main class="flex-1 p-6 lg:p-10 overflow-y-auto scrollbar-hide">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">Projects</h1>


                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

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
        </main>
    </div>
</body>

</html>