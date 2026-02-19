<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parsa Besharat</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class', // This tells Tailwind to look for a 'dark' class on the HTML tag
        }
    </script>

    <style>
        /* Smooth transition for the background swap */
        body {
            transition: background-image 0.5s ease-in-out, background-color 0.5s ease-in-out;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        /* Light Theme Background */
        body.light {
            background-image: url('/images/light.png');
            background-color: #f3f4f6; /* Fallback if image fails to load */
        }

        /* Dark Theme Background */
        body.dark {
            background-image: url('/images/dark.png');
            background-color: #111827; /* Fallback if image fails to load */
        }
        
        /* Subtly style the scrollbar inside the glass menu */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.4); border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-4 lg:p-10">

    <button id="theme-toggle" class="fixed top-6 right-6 z-50 p-3 rounded-full bg-white/30 dark:bg-black/30 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg transition hover:scale-110">
        <span id="theme-icon-light" class="hidden text-xl">☀️</span>
        <span id="theme-icon-dark" class="hidden text-xl">🌙</span>
    </button>

    <div class="relative w-full max-w-6xl flex flex-col md:flex-row bg-white/40 dark:bg-gray-900/40 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-2xl rounded-[2.5rem] overflow-hidden min-h-[80vh]">
        
        <aside class="w-full md:w-64 flex flex-col border-b md:border-b-0 md:border-r border-white/50 dark:border-white/10 p-6 lg:p-8">
            
            <div class="flex flex-col items-center mb-8">
                <img src="https://ui-avatars.com/api/?name=Your+Name&background=random" alt="Profile Picture" class="w-24 h-24 rounded-full border-4 border-white/50 dark:border-gray-700/50 shadow-md mb-3">
                <h2 class="text-2xl font-bold tracking-tight">Your Name</h2>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Web Developer</p>
            </div>

            <nav class="flex-1 overflow-y-auto space-y-1.5 pr-2">
                @php
                    // Your exact requested menu list
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

            <div class="mt-6 pt-6 border-t border-white/50 dark:border-white/10 text-center">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">&copy; 2026 Your Name.</p>
            </div>
        </aside>

        <main class="flex-1 p-8 lg:p-14 relative overflow-hidden flex flex-col justify-center">
            
            <div class="absolute top-0 left-10 w-48 h-48 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 dark:opacity-10 animate-pulse"></div>
            <div class="absolute bottom-10 right-20 w-64 h-64 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 dark:opacity-10 animate-pulse delay-1000"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <div>
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-full text-sm font-bold mb-6 backdrop-blur-md shadow-sm">
                        👋 HELLO!
                    </span>
                    
                    <h1 class="text-5xl lg:text-6xl font-extrabold mb-6 tracking-tight text-gray-900 dark:text-white">
                        I'm <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-pink-500">Your Name.</span>
                    </h1>
                    
                    <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed mb-10">
                        I am a Web Developer based in Your City. I have rich experience in website design, building, and customization. I love to talk with you about your unique projects.
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-5">
                        <a href="/contact" class="px-8 py-3.5 bg-gradient-to-r from-orange-400 to-pink-500 text-white font-bold rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition transform">
                            Hire Me
                        </a>
                        <a href="/projects" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2 hover:text-orange-500 dark:hover:text-orange-400 transition group">
                            View Works <span class="text-xl group-hover:translate-x-1 transition">→</span>
                        </a>
                    </div>
                </div>

                <div class="relative flex justify-center mt-10 lg:mt-0">
                    <div class="absolute inset-0 bg-orange-100 dark:bg-orange-900/30 rounded-full transform scale-90 -z-10 blur-md"></div>
                    <img src="https://ui-avatars.com/api/?name=User&size=512&background=random" alt="Hero Portrait" class="w-full max-w-sm rounded-full object-cover border-[6px] border-white/60 dark:border-gray-800/60 shadow-2xl">
                </div>
            </div>
        </main>

    </div>

    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const iconLight = document.getElementById('theme-icon-light');
        const iconDark = document.getElementById('theme-icon-dark');
        const html = document.documentElement;
        const body = document.body;

        // 1. Determine the starting theme (Local Storage overrides OS preference)
        function getInitialTheme() {
            if (localStorage.getItem('theme')) {
                return localStorage.getItem('theme');
            }
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                return 'dark';
            }
            return 'light';
        }

        // 2. Apply the theme safely to the DOM
        function applyTheme(theme) {
            if (theme === 'dark') {
                html.classList.add('dark');
                // Ensure body has the 'dark' class for the background image CSS
                body.classList.remove('light');
                body.classList.add('dark');
                // Toggle icons
                iconLight.classList.remove('hidden');
                iconDark.classList.add('hidden');
            } else {
                html.classList.remove('dark');
                // Ensure body has the 'light' class for the background image CSS
                body.classList.remove('dark');
                body.classList.add('light');
                // Toggle icons
                iconDark.classList.remove('hidden');
                iconLight.classList.add('hidden');
            }
            // Save preference
            localStorage.setItem('theme', theme);
        }

        // 3. Initialize on page load
        let currentTheme = getInitialTheme();
        applyTheme(currentTheme);

        // 4. Handle manual toggle button clicks
        themeToggleBtn.addEventListener('click', () => {
            currentTheme = currentTheme === 'light' ? 'dark' : 'light';
            applyTheme(currentTheme);
        });

        // 5. Listen for OS theme changes in real-time
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            // Only auto-switch if the user hasn't explicitly set a preference manually
            if (!localStorage.getItem('theme')) {
                applyTheme(event.matches ? 'dark' : 'light');
            }
        });
    </script>
</body>
</html>