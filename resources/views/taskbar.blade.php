<!-- MACOS / WINDOWS TASKBAR DOCK (PURE USER CHATS DOCK) -->
<div id="mac-taskbar-dock" class="fixed bottom-3 left-1/2 -translate-x-1/2 z-50 flex items-center gap-2 px-4 py-2 bg-black/60 backdrop-blur-2xl border border-white/15 rounded-full shadow-[0_10px_35px_rgba(0,0,0,0.7)] transition-all duration-300 hover:scale-105 hover:border-white/30">
    
    <div class="flex items-center space-x-2 border-r border-white/15 pr-2">
        <span class="text-base font-bold text-blue-400">💬</span>
        <span class="text-xs font-mono font-bold text-white hidden sm:inline">CHATS</span>
    </div>

    <!-- Dynamic macOS Chat Contacts Dock Container -->
    <div id="mac-chat-users-dock" class="flex items-center gap-2">
        <!-- JS renders chat member avatars & red unread badges -->
    </div>

    <!-- CENTRAL ACTION BUTTONS (CREATE POST & WRITE JOURNAL) -->
    @if(Auth::check())
        <div class="flex items-center gap-2 border-x border-white/15 px-2">
            <button onclick="window.openGlobalCreatePostModal ? window.openGlobalCreatePostModal() : (window.location.href='/#create-post')" 
                class="px-3.5 py-1.5 bg-gradient-to-r from-orange-500 via-rose-600 to-pink-600 hover:from-orange-400 hover:to-pink-500 text-white font-bold rounded-full text-xs shadow-lg transition transform hover:scale-105 active:scale-95 flex items-center gap-1.5 border border-white/20">
                <span>➕</span>
                <span>{{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Beitrag Erstellen' : 'Create Post' }}</span>
            </button>

            <a href="/blog?action=write" 
                class="px-3.5 py-1.5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-full text-xs shadow-lg transition transform hover:scale-105 active:scale-95 flex items-center gap-1.5 border border-white/20">
                <span>✍️</span>
                <span>{{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Journal Schreiben' : 'Write Journal' }}</span>
            </a>
        </div>
    @endif

    <!-- Restore Window Trigger -->
    <button onclick="window.restoreMacWindow && window.restoreMacWindow()" title="Restore / Focus Active Window"
            class="p-2.5 rounded-2xl hover:bg-white/10 text-white transition-all group relative flex items-center justify-center">
        <span class="text-xl group-hover:scale-125 transition-transform">🗔</span>
        <span class="absolute -top-9 px-2.5 py-1 bg-black/80 text-[10px] font-bold text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg border border-white/10">
            {{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Fenster-Fokus' : 'Window Focus' }}
        </span>
    </button>
</div>
