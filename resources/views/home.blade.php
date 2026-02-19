<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parsa Besharat - Researcher & AI Engineer</title>
    
    <meta name="description" content="Parsa Besharat is a Researcher and AI Engineer with expertise in web development, machine learning, and unique project customization.">
    <meta name="keywords" content="Parsa Besharat, AI Engineer, Researcher, Web Developer, Portfolio, Machine Learning, Artificial Intelligence">
    <meta name="author" content="Parsa Besharat">
    <meta name="theme-color" content="#ffffff">

    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.parsabesharat.com/">
    <meta property="og:title" content="Parsa Besharat - Researcher & AI Engineer">
    <meta property="og:description" content="Parsa Besharat is a Researcher and AI Engineer with expertise in web development, machine learning, and unique project customization.">
    <meta property="og:image" content="{{ asset('images/profile.jpg') }}">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://www.parsabesharat.com/">
    <meta property="twitter:title" content="Parsa Besharat - Researcher & AI Engineer">
    <meta property="twitter:description" content="Parsa Besharat is a Researcher and AI Engineer with expertise in web development, machine learning, and unique project customization.">
    <meta property="twitter:image" content="{{ asset('images/profile.jpg') }}">

    <link rel="icon" type="image/jpeg" href="{{ asset('images/profile.jpg') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.tailwind = { config: { darkMode: 'class' } };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 dark:bg-[#0b1120] text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-4 lg:p-10 min-h-screen">

    <div id="main-container" class="relative w-full max-w-6xl flex flex-col md:flex-row bg-white/30 dark:bg-[#1e293b]/40 backdrop-blur-2xl border border-white/50 dark:border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.1)] dark:shadow-[0_8px_32px_rgba(0,0,0,0.4)] rounded-[2.5rem] overflow-hidden min-h-[80vh]">
        
        <div class="absolute top-6 right-8 flex items-center gap-5 z-50">
            <button id="theme-toggle" class="p-2.5 rounded-full bg-white/40 dark:bg-black/40 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-sm transition hover:scale-110">
                <span id="theme-icon-light" class="hidden text-sm">☀️</span>
                <span id="theme-icon-dark" class="hidden text-sm">🌙</span>
            </button>

            <div class="flex gap-2">
                <div class="w-3.5 h-3.5 rounded-full bg-[#ff5f56] shadow-sm border border-[#e0443e]"></div>
                <div class="w-3.5 h-3.5 rounded-full bg-[#ffbd2e] shadow-sm border border-[#dea123]"></div>
                <div class="w-3.5 h-3.5 rounded-full bg-[#27c93f] shadow-sm border border-[#1aab29]"></div>
            </div>
        </div>

        <aside class="w-full md:w-64 flex flex-col border-b md:border-b-0 md:border-r border-white/30 dark:border-white/10 p-6 lg:p-8 relative z-20">
            
            <div class="flex flex-col items-center mb-8 mt-4 md:mt-0">
                <img src="{{ asset('images/profile.jpg') }}" alt="Profile Picture" class="w-24 h-24 rounded-full border-4 border-white/50 dark:border-gray-700/50 shadow-md mb-3 object-cover object-center aspect-square">
                <h2 class="text-2xl font-bold tracking-tight">Parsa Besharat</h2>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Researcher - AI Engineer</p>
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
                    <a href="/{{ $item['route'] }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 hover:bg-white/60 dark:hover:bg-black/40 font-medium text-gray-700 dark:text-gray-300 hover:shadow-sm">
                        <span class="text-lg">{{ $item['icon'] }}</span>
                        {{ $item['name'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-6 pt-6 border-t border-white/30 dark:border-white/10 text-center">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">&copy; 2026 Parsa Besharat.</p>
            </div>
        </aside>

        <main class="flex-1 p-8 lg:p-14 relative overflow-hidden flex flex-col justify-center">
            
            <div class="absolute top-0 left-10 w-48 h-48 bg-yellow-300 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 dark:opacity-20 animate-pulse"></div>
            <div class="absolute bottom-10 right-20 w-64 h-64 bg-pink-400 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 dark:opacity-20 animate-pulse delay-1000"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mt-12 lg:mt-0">
                
                <div>
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-full text-sm font-bold mb-6 backdrop-blur-md shadow-sm">
                        👋 HELLO!
                    </span>
                    
                    <h1 class="text-5xl lg:text-6xl font-extrabold mb-6 tracking-tight text-gray-900 dark:text-white">
                        I'm <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-pink-500">Parsa Besharat.</span>
                    </h1>
                    
                    <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed mb-10">
                        I am a Web Developer based in Your City. I have rich experience in website design, building, and customization. I love to talk with you about your unique projects.
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-5">
                        <a href="/contact" class="px-8 py-3.5 bg-gradient-to-r from-orange-400 to-pink-500 text-white font-bold rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition transform">
                            Contact me
                        </a>
                        <a href="/projects" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2 hover:text-orange-500 dark:hover:text-orange-400 transition group">
                            View Works <span class="text-xl group-hover:translate-x-1 transition">→</span>
                        </a>
                    </div>
                </div>

                <div class="relative flex justify-center mt-10 lg:mt-0">
                    <div class="absolute inset-0 bg-orange-100 dark:bg-orange-900/30 rounded-full transform scale-90 -z-10 blur-xl"></div>
                    <img src="{{ asset('images/profile.jpg') }}" alt="Hero Portrait" class="w-full max-w-sm rounded-full object-cover object-center aspect-square border-[6px] border-white/50 dark:border-white/10 shadow-2xl">
                </div>
            </div>
        </main>

    </div>

</body>
</html>