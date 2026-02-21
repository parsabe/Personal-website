<style>
    @media (max-width: 767px) {
        /* * Target whatever container comes AFTER your sidebar in the DOM.
         * The !important flag ensures it overrides any existing Tailwind utility classes 
         * that might be stubbornly holding the content at the top.
         */
        aside#sidebar ~ * {
            padding-top: 6rem !important; 
            justify-content: flex-start !important; /* NEW: Forces content to start at the top */
        }
    }
</style>
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

<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 transform -translate-x-full md:translate-x-0 md:static md:block flex flex-col border-r border-white/20 dark:border-white/10 bg-white/95 dark:bg-gray-900/95 md:bg-transparent md:dark:bg-transparent backdrop-blur-xl md:backdrop-blur-none p-6 lg:p-8 transition-transform duration-300 ease-in-out shadow-2xl md:shadow-none h-full">

    <!-- Mobile Close Button -->
    <button onclick="toggleSidebar()" class="md:hidden absolute top-4 right-4 p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div class="flex flex-col items-center mb-8 mt-8 md:mt-0">
        <img src="{{ asset('images/profile.jpg') }}" alt="Profile Picture"
            class="w-24 h-24 rounded-full border-[3px] border-white/40 shadow-lg mb-3 object-cover object-[50%_25%] aspect-square">
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
                ['name' => 'Favorite Books', 'route' => 'books', 'icon' => '📕'],
                ['name' => 'VPN Server', 'route' => 'vpn-server', 'icon' => '☁️'],
                ['name' => 'Club', 'route' => 'fun', 'icon' => '🎮'],
                ['name' => 'Blog', 'route' => 'blog', 'icon' => '☕'],
                
                // External Subdomains using 'url' instead of 'route'
                ['name' => 'Chat', 'url' => 'https://blue-pearl.parsabe.com', 'icon' => '💬'],
                ['name' => 'Sandika', 'url' => 'https://sandika.parsabe.com', 'icon' => '🤖'],
                ['name' => 'Nigma', 'url' => 'https://nigma.parsabe.com', 'icon' => '🧩'],
            ];
        @endphp

        @foreach($menuItems as $item)
            @php
                // Determine if this is an external URL or a local route
                $isExternal = isset($item['url']);
                $href = $isExternal ? $item['url'] : route($item['route']);
                
                // Active state only applies to local routes
                $isActive = !$isExternal && request()->routeIs($item['route']);
            @endphp
            
            <a href="{{ $href }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 font-semibold text-gray-800 dark:text-gray-200 hover:shadow-sm hover:bg-white/40 dark:hover:bg-black/40 
                    {{ $isActive ? 'bg-white/50 dark:bg-black/50 shadow-md border border-white/20 dark:border-white/10' : 'border border-transparent' }}">

                <span class="text-lg">{{ $item['icon'] }}</span>
                {{ $item['name'] }}
            </a>
        @endforeach
    </nav>
    <div class="mt-6 text-xs text-center text-gray-500 dark:text-gray-400 font-medium">
        &copy; 2026 Parsa Besharat
    </div>
</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }
</script>