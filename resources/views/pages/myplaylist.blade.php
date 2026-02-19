<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parsa Besharat - Researcher & AI Engineer</title>

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

        <main class="flex-1 p-8 lg:p-14 relative overflow-y-auto">
            <div class="relative z-10 mt-12 lg:mt-0">
                
                <div class="mb-16">
                    <span
                        class="inline-flex items-center gap-2 px-4 py-1.5 ios-glass text-gray-900 dark:text-white rounded-full text-sm font-bold mb-6">
                         🎵 MY PLAYLIST
                    </span>

                    <h1
                        class="text-4xl lg:text-5xl font-extrabold mb-8 tracking-tight text-gray-900 dark:text-white drop-shadow-sm">
                        Favorite <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500 dark:from-green-400 dark:to-blue-400">Tracks & Mixes.</span>
                    </h1>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-10">
                        
                        <div class="ios-glass p-3 rounded-3xl border border-white/20 dark:border-white/10 shadow-lg">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 ml-2 flex items-center gap-2">
                                <span class="text-[#1DB954]">🎧</span> Spotify
                            </h3>
                            <iframe data-testid="embed-iframe" style="border-radius:12px" src="https://open.spotify.com/embed/playlist/1wbC8swsFWpJalHbfq3yF6?utm_source=generator" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                        </div>

                        <div class="ios-glass p-3 rounded-3xl border border-white/20 dark:border-white/10 shadow-lg">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 ml-2 flex items-center gap-2">
                                <span class="text-[#FF0000]">▶️</span> YouTube
                            </h3>
                            <iframe width="100%" height="352" style="border-radius:12px" src="https://www.youtube.com/embed/videoseries?list=PLDeaK_8P01I8Riyis-oppzFdIgbC7Wu79" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>

                    </div>
                    
                    <div class="flex">
                        <a href="https://youtube.com/playlist?list=PLDeaK_8P01I8Riyis-oppzFdIgbC7Wu79&si=VUTi55ohf40qvmSY" target="_blank" class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition transform flex items-center gap-2">
                            Open in YouTube App ↗
                        </a>
                    </div>
                </div>

                
            </div>

        </main>

    </div>

</body>

</html>


