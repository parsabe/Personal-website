<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Parsa Besharat</title>

    <meta name="description" content="Contact Parsa Besharat">
    <meta name="author" content="Parsa Besharat">
    <meta name="robots" content="index, follow">

    <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        window.tailwind = { config: { darkMode: 'class' } };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
</head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-E441FBGYXG"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-E441FBGYXG');
</script>

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

        <main class="flex-1 p-8 lg:p-14 relative flex flex-col justify-center overflow-y-auto">
            <div class="max-w-2xl mx-auto w-full">
                <h2 class="text-4xl font-bold mb-8 text-gray-900 dark:text-white drop-shadow-sm">Contact Me</h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <a href="https://github.com/parsabe" target="_blank" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-white/50 dark:bg-black/20 border border-white/20 dark:border-white/10 hover:bg-white/80 dark:hover:bg-black/40 transition-all group">
                        <i class="fab fa-github text-3xl mb-2 text-gray-900 dark:text-white group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">GitHub</span>
                    </a>
                    
                    <a href="https://www.linkedin.com/in/parsabe" target="_blank" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-white/50 dark:bg-black/20 border border-white/20 dark:border-white/10 hover:bg-white/80 dark:hover:bg-black/40 transition-all group">
                        <i class="fab fa-linkedin text-3xl mb-2 text-[#0077b5] group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">LinkedIn</span>
                    </a>

                    <a href="https://www.researchgate.net/profile/Parsa-Besharat" target="_blank" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-white/50 dark:bg-black/20 border border-white/20 dark:border-white/10 hover:bg-white/80 dark:hover:bg-black/40 transition-all group">
                        <i class="fab fa-researchgate text-3xl mb-2 text-[#00ccbb] group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">ResearchGate</span>
                    </a>

                    <a href="mailto:parsa.besharat@student.tu-freiberg.de" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-white/50 dark:bg-black/20 border border-white/20 dark:border-white/10 hover:bg-white/80 dark:hover:bg-black/40 transition-all group">
                        <i class="fas fa-envelope text-3xl mb-2 text-[#EA4335] group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Email</span>
                    </a>
                </div>
                
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-green-100/80 dark:bg-green-900/50 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 backdrop-blur-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('contact') }}" class="space-y-5">
                    @csrf
                    <div class="group">
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 ml-1">Name</label>
                        <input type="text" id="name" name="name" required 
                            class="w-full px-5 py-3.5 rounded-2xl bg-white/50 dark:bg-black/20 border border-white/20 dark:border-white/10 focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 outline-none transition-all text-gray-900 dark:text-white placeholder-gray-500 backdrop-blur-sm hover:bg-white/60 dark:hover:bg-black/30">
                    </div>
                    
                    <div class="group">
                        <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 ml-1">Email address</label>
                        <input type="email" id="email" name="email" required 
                            class="w-full px-5 py-3.5 rounded-2xl bg-white/50 dark:bg-black/20 border border-white/20 dark:border-white/10 focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 outline-none transition-all text-gray-900 dark:text-white placeholder-gray-500 backdrop-blur-sm hover:bg-white/60 dark:hover:bg-black/30">
                    </div>
                    
                    <div class="group">
                        <label for="message" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 ml-1">Message</label>
                        <textarea id="message" name="message" rows="5" required 
                            class="w-full px-5 py-3.5 rounded-2xl bg-white/50 dark:bg-black/20 border border-white/20 dark:border-white/10 focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 outline-none transition-all text-gray-900 dark:text-white placeholder-gray-500 backdrop-blur-sm hover:bg-white/60 dark:hover:bg-black/30 resize-none"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-orange-500 to-pink-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 border border-white/20 mt-2">
                        Send Message
                    </button>
                </form>
            </div>
        </main>

    </div>

</body>

</html>