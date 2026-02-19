
<aside
    class="w-full md:w-64 flex flex-col border-b md:border-b-0 md:border-r border-white/20 dark:border-white/10 p-6 lg:p-8 relative z-20">

    <div class="flex flex-col items-center mb-8 mt-4 md:mt-0">
        <img src="{{ asset('images/profile.jpg') }}" alt="Profile Picture"
            class="w-24 h-24 rounded-full border-[3px] border-white/40 shadow-lg mb-3 object-cover object-[50%_25%] aspect-square">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white drop-shadow-md">Parsa
            Besharat</h2>
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Researcher - AI Engineer</p>
    </div>

   <nav class="flex-1 overflow-y-auto space-y-1.5 pr-2">
        @php
            // The 'route' keys now perfectly match the ->name('...') definitions in web.php
            $menuItems = [
                ['name' => 'Home', 'route' => 'home', 'icon' => '🏠'],
                ['name' => 'About', 'route' => 'about', 'icon' => '👤'],
                ['name' => 'Contact', 'route' => 'contact', 'icon' => '✉️'],
                ['name' => 'Projects', 'route' => 'projects', 'icon' => '💼'],
                ['name' => 'Publications', 'route' => 'publications', 'icon' => '📚'],
                ['name' => 'My Playlist', 'route' => 'myplaylist', 'icon' => '🎵'],
                ['name' => 'Search', 'route' => 'search', 'icon' => '🔍'],
                ['name' => 'VPN Server', 'route' => 'vpn-server', 'icon' => '☁️'],
                ['name' => 'Club', 'route' => 'fun', 'icon' => '🎮'],
                ['name' => 'Support', 'route' => 'support', 'icon' => '☕'],
            ];
        @endphp

        @foreach($menuItems as $item)
            <a href="{{ route($item['route']) }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 font-semibold text-gray-800 dark:text-gray-200 hover:shadow-sm hover:bg-white/40 dark:hover:bg-black/40 
                {{ request()->routeIs($item['route']) ? 'bg-white/50 dark:bg-black/50 shadow-md border border-white/20 dark:border-white/10' : 'border border-transparent' }}">
                
                <span class="text-lg">{{ $item['icon'] }}</span>
                {{ $item['name'] }}
            </a>
        @endforeach
    </nav>
    <div class="mt-6 text-xs text-center text-gray-500 dark:text-gray-400 font-medium">
        &copy; 2026 Parsa Besharat
    </div>
</aside>