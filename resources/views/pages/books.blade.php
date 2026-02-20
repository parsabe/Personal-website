<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Books - Parsa Besharat</title>

    <meta name="description" content="Parsa Besharat is an Iranian Researcher and AI Engineer. He is currently pursuing his
    MS.c degree in Data Science at the TU Freiberg University in Sachsen, Germany.">
    <meta name="author" content="Parsa Besharat">
    <meta name="keywords"
        content="Parsa Besharat, Researcher, AI Engineer, Data Scientist, Machine Learning, Deep Learning, Natural Language Processing, Computer Vision, TU Freiberg University, Germany">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">

    <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <img src="{{ asset('images/ai.jpg') }}" alt="AI" class="w-full h-full object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <img src="{{ asset('images/arnold.jpg') }}" alt="Arnold" class="w-full h-full object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <img src="{{ asset('images/become.jpg') }}" alt="Become" class="w-full h-full object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <img src="{{ asset('images/boy.jpg') }}" alt="Boy" class="w-full h-full object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <img src="{{ asset('images/sapiens.jpg') }}" alt="Sapiens" class="w-full h-full object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <img src="{{ asset('images/source.jpg') }}" alt="Source" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </main>

    </div>

</body>

</html>