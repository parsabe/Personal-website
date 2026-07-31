<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

<!-- Mobile Toggle Button -->
<button id="sidebar-toggle" onclick="toggleSidebar()"
    class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-white/10 backdrop-blur-md border border-white/20 text-gray-800 dark:text-white shadow-lg transition-transform hover:scale-105 active:scale-95">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<!-- Mobile Overlay -->
<div id="sidebar-overlay" onclick="toggleSidebar()"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden md:hidden transition-opacity duration-300">
</div>

<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 transform -translate-x-full md:translate-x-0 md:static flex flex-col border-r border-white/20 dark:border-white/10 bg-gray-950/95 dark:bg-gray-950/95 md:bg-transparent md:dark:bg-transparent backdrop-blur-2xl md:backdrop-blur-none p-6 lg:p-8 transition-transform duration-300 ease-in-out shadow-2xl md:shadow-none h-full text-white">

    <!-- Mobile Close Button -->
    <button onclick="toggleSidebar()"
        class="md:hidden absolute top-4 right-4 p-2 text-white hover:text-gray-300 bg-white/10 hover:bg-white/20 rounded-full transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div class="flex flex-col items-center mb-8 mt-8 md:mt-0">
        <img src="{{ asset('images/profile.jpg') }}" alt="Parsa Besharat"
            class="w-24 h-24 rounded-full border-[3px] border-white/40 shadow-lg mb-3 object-cover object-[50%_25%] aspect-square">
        <h2 class="text-2xl font-bold tracking-tight text-white drop-shadow-md">Parsa
            Besharat</h2>
        <p class="text-sm font-medium text-gray-300">Researcher - AI Engineer</p>
    </div>

    <nav class="flex-1 overflow-y-auto min-h-0 space-y-4 pr-2 sidebar-scroll">
        @php
            $sections = [
                'MAIN & PERSONAL' => [
                    ['name' => 'Home', 'route' => 'home', 'icon' => '🏠'],
                    ['name' => 'About', 'route' => 'about', 'icon' => '👤'],
                    ['name' => 'Contact', 'route' => 'contact', 'icon' => '✉️'],
                    ['name' => 'Projects', 'route' => 'projects', 'icon' => '💼'],
                    ['name' => 'Publications', 'route' => 'publications', 'icon' => '📚'],
                    ['name' => 'My Playlist', 'route' => 'myplaylist', 'icon' => '🎵'],
                    ['name' => 'Favorite Books', 'route' => 'books', 'icon' => '📕'],
                    ['name' => 'Search', 'route' => 'search', 'icon' => '🔍'],
                ],
            ];

            if (auth()->check()) {
                $sections['SERVICES & PORTALS'] = [
                    ['name' => 'VECTRA (New)', 'url' => 'https://vectra.parsabe.com', 'icon' => '👀'],
                    ['name' => 'BlackWall AI', 'route' => 'projects.blackwall', 'icon' => '🛡️'],
                    ['name' => 'Chat Portal', 'route' => 'chat', 'icon' => '💬'],
                    ['name' => 'Sandika', 'route' => 'sandika', 'icon' => '⚔️'],
                    ['name' => 'Nigma', 'route' => 'nigma', 'icon' => '🧩'],
                    ['name' => 'CS Certificates', 'route' => 'cs.certificates.index', 'icon' => '🎓'],
                    ['name' => 'CS Feedback Form', 'route' => 'cs.feedback.create', 'icon' => '📝'],
                    ['name' => 'VPN Server', 'route' => 'vpn-server', 'icon' => '☁️'],
                    ['name' => 'Club', 'route' => 'fun', 'icon' => '🎮'],
                    ['name' => 'Blog', 'route' => 'blog', 'icon' => '☕'],
                ];

                $accountItems = [];
                $accountItems[] = ['name' => 'My Profile', 'route' => 'user.profile.show', 'icon' => '👤'];
                if (auth()->user()->email === 'parsabe99@gmail.com') {
                    $accountItems[] = ['name' => 'Parsa Dashboard', 'route' => 'parsa.dashboard', 'icon' => '🔒'];
                }
                $sections['ACCOUNT & CONTROL'] = $accountItems;
            }
        @endphp

        @php
            $isDe = (session('app_locale') === 'de' || app()->getLocale() === 'de');
            $deMap = [
                'Home' => 'Startseite',
                'About' => 'Über mich',
                'Contact' => 'Kontakt',
                'Projects' => 'Projekte',
                'Publications' => 'Publikationen',
                'My Playlist' => 'Meine Wiedergabeliste',
                'Favorite Books' => 'Lieblingsbücher',
                'Search' => 'Suchen',
                'MAIN & PERSONAL' => 'HAUPTMENÜ & PERSÖNLICHES',
                'SERVICES & PORTALS' => 'DIENSTE & PORTALE',
                'ACCOUNT & CONTROL' => 'KONTO & STEUERUNG',
                'My Profile' => 'Mein Profil',
                'Parsa Dashboard' => 'Parsa Dashboard',
                'Services Locked' => 'Dienste Gesperrt',
                'Login / Sign Up to see services' => 'Anmelden um Dienste zu sehen',
            ];
        @endphp

        @foreach($sections as $category => $items)
            <div class="space-y-1">
                <div class="px-3 pt-2 pb-1 text-[10px] uppercase tracking-wider font-mono font-bold text-gray-400">
                    {{ $isDe ? ($deMap[$category] ?? $category) : $category }}
                </div>
                @foreach($items as $item)
                    @php
                        $isExternal = isset($item['url']);
                        $href = $isExternal ? $item['url'] : (isset($item['query']) ? route($item['route']) . '?' . $item['query'] : route($item['route']));
                        $isActive = !$isExternal && request()->routeIs($item['route']) && (!isset($item['query']) || request()->getQueryString() === $item['query']);
                        $displayName = $isDe ? ($deMap[$item['name']] ?? $item['name']) : $item['name'];
                    @endphp

                    <a href="{{ $href }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl transition-all duration-300 font-semibold text-xs text-gray-200 hover:text-white hover:shadow-sm hover:bg-white/20 
                                    {{ $isActive ? 'bg-indigo-600/80 text-white font-bold shadow-md border border-indigo-400/40' : 'border border-transparent' }}">

                        <span class="text-base">{{ $item['icon'] }}</span>
                        {{ $displayName }}
                    </a>
                @endforeach
            </div>
        @endforeach

        @guest
            <div class="space-y-1 pt-1">
                <div class="px-3 pt-2 pb-1 text-[10px] uppercase tracking-wider font-mono font-bold text-gray-400">
                    {{ $isDe ? 'DIENSTE & PORTALE' : 'SERVICES & PORTALS' }}
                </div>
                <a href="{{ route('login') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-300 hover:bg-amber-500/20 transition group">
                    <div class="flex items-center gap-2.5">
                        <span class="text-base">🔒</span>
                        <div>
                            <p class="text-xs font-bold">{{ $isDe ? 'Dienste Gesperrt' : 'Services Locked' }}</p>
                            <p class="text-[10px] text-amber-200/70">{{ $isDe ? 'Anmelden um Dienste zu sehen' : 'Login / Sign Up to see services' }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-amber-400 group-hover:translate-x-1 transition-transform">➔</span>
                </a>
            </div>
        @endguest
    </nav>
    <div class="mt-4 text-[11px] text-center text-gray-500 dark:text-gray-400 font-medium">
        &copy; 2026 Parsa Besharat
    </div>
</aside>

<!-- Separate ESM JavaScript Module -->
<script type="module" src="{{ asset('js/sidebar.js') }}"></script>