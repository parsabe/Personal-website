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
                    
                    <!-- FunRoot -->
                    <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/projects/funroot.png') }}" alt="FunRoot" class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">FunRoot</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            A brief one-sentence explanation about the FunRoot project.
                        </p>
                        <a href="https://github.com/parsabe/FunRoot" target="_blank" class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- MLMatrix -->
                    <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/projects/mlmatrix.png') }}" alt="MLMatrix" class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">MLMatrix</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            A brief one-sentence explanation about the MLMatrix project.
                        </p>
                        <a href="https://github.com/parsabe/MLMatrix" target="_blank" class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- NetNexus -->
                    <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/projects/netnexus.png') }}" alt="NetNexus" class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">NetNexus</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            A brief one-sentence explanation about the NetNexus project.
                        </p>
                        <a href="https://github.com/parsabe/NetNexus" target="_blank" class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- SCP -->
                    <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/projects/scp.png') }}" alt="SCP" class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">SCP</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            A brief one-sentence explanation about the SCP project.
                        </p>
                        <a href="https://github.com/parsabe/SCP" target="_blank" class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- Ceasar Toolkit -->
                    <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/projects/ceasar-toolkit.png') }}" alt="Ceasar Toolkit" class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">Ceasar Toolkit</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            A brief one-sentence explanation about the Ceasar Toolkit project.
                        </p>
                        <a href="https://github.com/parsabe/Ceasar-Toolkit" target="_blank" class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                    <!-- Parsai -->
                    <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 rounded-xl mb-4 overflow-hidden">
                            <img src="{{ asset('images/projects/parsai.png') }}" alt="Parsai" class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">Parsai</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-grow">
                            A brief one-sentence explanation about the Parsai project.
                        </p>
                        <a href="https://github.com/parsabe/Parsai" target="_blank" class="inline-block text-center w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                            Read more
                        </a>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>
