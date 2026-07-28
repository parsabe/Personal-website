<!-- MACOS / WINDOWS TASKBAR DOCK (FIXED BOTTOM) -->
@php
    $currentRoute = Request::path();
@endphp
<div id="mac-taskbar-dock" class="fixed bottom-3 left-1/2 -translate-x-1/2 z-50 flex items-center gap-1.5 px-4 py-2 bg-black/50 backdrop-blur-2xl border border-white/15 rounded-full shadow-[0_10px_30px_rgba(0,0,0,0.6)] transition-all duration-300 hover:scale-105 hover:border-white/30">
    
    <!-- Home Item -->
    <a href="/" title="Website Home" 
       class="taskbar-item p-2.5 rounded-2xl hover:bg-white/10 transition-all group relative flex items-center justify-center {{ $currentRoute == '/' ? 'bg-white/15 ring-2 ring-indigo-400' : '' }}">
        <span class="text-xl group-hover:scale-125 transition-transform">🏠</span>
        <span class="absolute -top-9 px-2.5 py-1 bg-black/80 text-[10px] font-bold text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg border border-white/10">
            Home
        </span>
        @if($currentRoute == '/')
            <span class="absolute -bottom-1 w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
        @endif
    </a>

    <!-- Online Chat Portal Item -->
    <a href="/chat" title="Online Chat Portal" 
       class="taskbar-item p-2.5 rounded-2xl hover:bg-white/10 transition-all group relative flex items-center justify-center {{ Str::startsWith($currentRoute, 'chat') ? 'bg-white/15 ring-2 ring-blue-400' : '' }}">
        <span class="text-xl group-hover:scale-125 transition-transform">💬</span>
        <span class="absolute -top-9 px-2.5 py-1 bg-black/80 text-[10px] font-bold text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg border border-white/10">
            Chat Portal
        </span>
        @if(Str::startsWith($currentRoute, 'chat'))
            <span class="absolute -bottom-1 w-1.5 h-1.5 rounded-full bg-blue-400"></span>
        @endif
    </a>

    <!-- Sandika Concept Item -->
    <a href="/sandika" title="Sandika Hub & Arkham Spirits" 
       class="taskbar-item p-2.5 rounded-2xl hover:bg-white/10 transition-all group relative flex items-center justify-center {{ Str::startsWith($currentRoute, 'sandika') ? 'bg-white/15 ring-2 ring-amber-400' : '' }}">
        <span class="text-xl group-hover:scale-125 transition-transform">⚔️</span>
        <span class="absolute -top-9 px-2.5 py-1 bg-black/80 text-[10px] font-bold text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg border border-white/10">
            Sandika & Arkham
        </span>
        @if(Str::startsWith($currentRoute, 'sandika'))
            <span class="absolute -bottom-1 w-1.5 h-1.5 rounded-full bg-amber-400"></span>
        @endif
    </a>

    <!-- Nigma Riddler Item -->
    <a href="/nigma" title="Nigma Riddler Portal" 
       class="taskbar-item p-2.5 rounded-2xl hover:bg-white/10 transition-all group relative flex items-center justify-center {{ Str::startsWith($currentRoute, 'nigma') ? 'bg-white/15 ring-2 ring-emerald-400' : '' }}">
        <span class="text-xl group-hover:scale-125 transition-transform">🧩</span>
        <span class="absolute -top-9 px-2.5 py-1 bg-black/80 text-[10px] font-bold text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg border border-white/10">
            Nigma Riddler
        </span>
        @if(Str::startsWith($currentRoute, 'nigma'))
            <span class="absolute -bottom-1 w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
        @endif
    </a>

    <!-- Projects Vault Item -->
    <a href="/projects" title="Projects & AI Tools" 
       class="taskbar-item p-2.5 rounded-2xl hover:bg-white/10 transition-all group relative flex items-center justify-center {{ Str::startsWith($currentRoute, 'projects') ? 'bg-white/15 ring-2 ring-purple-400' : '' }}">
        <span class="text-xl group-hover:scale-125 transition-transform">💼</span>
        <span class="absolute -top-9 px-2.5 py-1 bg-black/80 text-[10px] font-bold text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg border border-white/10">
            Projects Vault
        </span>
        @if(Str::startsWith($currentRoute, 'projects'))
            <span class="absolute -bottom-1 w-1.5 h-1.5 rounded-full bg-purple-400"></span>
        @endif
    </a>

    <!-- CS Portal Item -->
    <a href="/cs-portal" title="CS Certificates Portal" 
       class="taskbar-item p-2.5 rounded-2xl hover:bg-white/10 transition-all group relative flex items-center justify-center {{ Str::startsWith($currentRoute, 'cs-portal') ? 'bg-white/15 ring-2 ring-cyan-400' : '' }}">
        <span class="text-xl group-hover:scale-125 transition-transform">🎓</span>
        <span class="absolute -top-9 px-2.5 py-1 bg-black/80 text-[10px] font-bold text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg border border-white/10">
            CS Certificates
        </span>
        @if(Str::startsWith($currentRoute, 'cs-portal'))
            <span class="absolute -bottom-1 w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
        @endif
    </a>

    <!-- Admin Portal Item (Visible for Admin) -->
    @auth
        @if(Auth::user()->email === 'parsabe99@gmail.com')
            <a href="/parsa" title="Parsa Executive Admin" 
               class="taskbar-item p-2.5 rounded-2xl hover:bg-white/10 transition-all group relative flex items-center justify-center {{ Str::startsWith($currentRoute, 'parsa') ? 'bg-white/15 ring-2 ring-rose-400' : '' }}">
                <span class="text-xl group-hover:scale-125 transition-transform">🔒</span>
                <span class="absolute -top-9 px-2.5 py-1 bg-black/80 text-[10px] font-bold text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg border border-white/10">
                    Parsa Core Admin
                </span>
                @if(Str::startsWith($currentRoute, 'parsa'))
                    <span class="absolute -bottom-1 w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                @endif
            </a>
        @endif
    @endauth

    <!-- Separator line -->
    <div class="h-6 w-[1px] bg-white/20 mx-1"></div>

    <!-- Restore Window Trigger -->
    <button onclick="window.restoreMacWindow && window.restoreMacWindow()" title="Restore / Focus Active Window"
            class="p-2.5 rounded-2xl hover:bg-white/10 text-white transition-all group relative flex items-center justify-center">
        <span class="text-xl group-hover:scale-125 transition-transform">🗔</span>
        <span class="absolute -top-9 px-2.5 py-1 bg-black/80 text-[10px] font-bold text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg border border-white/10">
            Window Focus
        </span>
    </button>
</div>
