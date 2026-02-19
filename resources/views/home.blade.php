<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parsa Besharat - Researcher & AI Engineer</title>

    <meta name="description" content="Parsa Besharat is an Iranian Researcher and AI Engineer. He is currently persuing his
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

    <link rel="icon" href="/public/images/profile.jpg">
</head>

<body
    class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-4 lg:p-10 min-h-screen relative overflow-x-hidden">

    <div id="main-container"
        class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden min-h-[80vh] z-10 transition-colors duration-700">

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

        <aside
            class="w-full md:w-64 flex flex-col border-b md:border-b-0 md:border-r border-white/20 dark:border-white/10 p-6 lg:p-8 relative z-20">

            <div class="flex flex-col items-center mb-8 mt-4 md:mt-0">
                <img src="{{ asset('images/profile.jpg') }}" alt="Profile Picture"
                    class="w-24 h-24 rounded-full border-[3px] border-white/40 shadow-lg mb-3 object-cover object-center aspect-square">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white drop-shadow-md">Parsa
                    Besharat</h2>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Researcher - AI Engineer</p>
            </div>

            <nav class="flex-1 overflow-y-auto space-y-1.5 pr-2">
              @php
                    $menuItems = [
                        ['name' => 'Home', 'route' => 'home', 'icon' => '🏠'],
                        ['name' => 'About', 'route' => 'about', 'icon' => '👤'],
                        ['name' => 'Contact', 'route' => 'contact', 'icon' => '✉️'],
                        ['name' => 'Projects', 'route' => 'projects', 'icon' => '💼'],
                        ['name' => 'Publications', 'route' => 'publications', 'icon' => '📚'],
                        ['name' => 'My Playlist', 'route' => 'myplaylist', 'icon' => '🎵'],
                        ['name' => 'Search', 'route' => 'search', 'icon' => '🔍'],
                        ['name' => 'VPS Server', 'route' => 'VPS_server', 'icon' => '☁️'],
                        ['name' => 'Fun', 'route' => 'fun', 'icon' => '🎮'],
                        ['name' => 'Support', 'route' => 'support', 'icon' => '☕'],
                    ];
                @endphp
                @foreach($menuItems as $item)
                    <a href="/{{ $item['route'] }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 hover:bg-white/40 dark:hover:bg-black/40 font-semibold text-gray-800 dark:text-gray-200 hover:shadow-sm">
                        <span class="text-lg">{{ $item['icon'] }}</span>
                        {{ $item['name'] }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <main class="flex-1 p-8 lg:p-14 relative flex flex-col justify-center">
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mt-12 lg:mt-0">

                <div>
                    <span
                        class="inline-flex items-center gap-2 px-4 py-1.5 ios-glass text-gray-900 dark:text-white rounded-full text-sm font-bold mb-6">
                        👋 HELLO!
                    </span>

                    <h1
                        class="text-5xl lg:text-6xl font-extrabold mb-6 tracking-tight text-gray-900 dark:text-white drop-shadow-sm">
                        I'm <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-pink-600 dark:from-orange-400 dark:to-pink-500">Parsa
                            Besharat.</span>
                    </h1>

                    <p
                        class="text-lg text-gray-800 dark:text-gray-200 leading-relaxed mb-10 font-medium drop-shadow-sm">
                        I am a Web Developer based in Your City. I have rich experience in website design, building, and
                        customization. I love to talk with you about your unique projects.
                    </p>

                    <div class="flex flex-wrap items-center gap-5">
                        <a href="/contact"
                            class="px-8 py-3.5 bg-gradient-to-r from-orange-500 to-pink-600 text-white font-bold rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition transform border border-white/20">
                            Contact me
                        </a>
                    </div>
                </div>

                <div class="relative flex justify-center mt-10 lg:mt-0">
                    <img src="{{ asset('images/profile.jpg') }}" alt="Hero Portrait"
                        class="w-full max-w-sm rounded-full object-cover object-center aspect-square border-4 border-white/40 shadow-[0_10px_40px_rgba(0,0,0,0.2)]">
                </div>
            </div>
        </main>

    </div>

</body>

</html>