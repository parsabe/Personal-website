<x-guest-layout>
    <div class="mb-6">
        <span class="inline-flex items-center gap-2 px-3 py-1 ios-glass text-emerald-600 dark:text-emerald-400 rounded-full text-xs font-bold mb-3">
            🎉 AUTHENTICATED SESSION
        </span>
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
            User Dashboard
        </h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
            Welcome back, <span class="font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>! You are securely logged in.
        </p>
    </div>

    <!-- Status Alert -->
    @if (session('status'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-sm font-medium flex items-center gap-3">
            <span class="text-base">✅</span>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    <div class="space-y-6">
        <!-- Account Info Card -->
        <div class="p-6 rounded-2xl bg-white/40 dark:bg-black/30 border border-white/30 dark:border-white/10 space-y-3">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                Account Summary
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 block">Name</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 block">Email Address</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ Auth::user()->email }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Navigation Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @if (Auth::user()->email === 'parsabe99@gmail.com')
                <a href="{{ route('parsa.dashboard') }}" 
                   class="p-4 rounded-2xl bg-gradient-to-r from-orange-500/20 to-pink-500/20 border border-orange-500/30 hover:border-orange-500/60 transition flex items-center gap-3 group">
                    <span class="text-2xl">🔒</span>
                    <div>
                        <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-orange-500 transition-colors">Parsa Admin Portal</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Access messages, feedback & 2FA</div>
                    </div>
                </a>
            @endif

            <a href="{{ route('profile.edit') }}" 
               class="p-4 rounded-2xl bg-white/40 dark:bg-black/30 border border-white/20 hover:border-white/40 transition flex items-center gap-3 group">
                <span class="text-2xl">👤</span>
                <div>
                    <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-orange-500 transition-colors">Edit Profile</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Update account settings & password</div>
                </div>
            </a>

            <a href="{{ route('home') }}" 
               class="p-4 rounded-2xl bg-white/40 dark:bg-black/30 border border-white/20 hover:border-white/40 transition flex items-center gap-3 group">
                <span class="text-2xl">🏠</span>
                <div>
                    <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-orange-500 transition-colors">Website Home</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Return to the main portal</div>
                </div>
            </a>

            <a href="{{ route('projects') }}" 
               class="p-4 rounded-2xl bg-white/40 dark:bg-black/30 border border-white/20 hover:border-white/40 transition flex items-center gap-3 group">
                <span class="text-2xl">💼</span>
                <div>
                    <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-orange-500 transition-colors">Projects</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Explore research & AI tools</div>
                </div>
            </a>
        </div>

        <!-- Logout Action -->
        <div class="pt-4 border-t border-white/10 flex justify-end">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="px-5 py-2.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30 rounded-xl text-xs font-bold transition flex items-center gap-2">
                    <span>🚪</span> Log Out
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
