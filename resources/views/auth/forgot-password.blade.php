<x-guest-layout>
    <div class="mb-6">
        <span class="inline-flex items-center gap-2 px-3 py-1 ios-glass text-orange-600 dark:text-orange-400 rounded-full text-xs font-bold mb-3">
            🔑 PASSWORD RECOVERY
        </span>
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
            Forgot Password?
        </h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
            No problem. Enter your registered email address below, and we will send you a secure link to reset your password.
        </p>
    </div>

    <!-- Session Status Alert -->
    @if (session('status'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-sm font-medium flex items-center gap-3">
            <span class="text-base">✅</span>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-300 text-sm font-medium">
            <div class="flex items-center gap-2 mb-1 font-bold">
                <span>⚠️</span> Please fix the following errors:
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                Email Address
            </label>
            <div class="relative">
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       placeholder="yourname@example.com"
                       class="w-full px-4 py-3 rounded-2xl bg-white/50 dark:bg-black/40 border border-white/30 dark:border-white/10 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/50 transition-all font-medium">
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
            <a href="{{ route('login') }}" 
               class="text-xs font-bold text-gray-600 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-400 transition flex items-center gap-1">
                ← Back to Login
            </a>

            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-orange-500 to-pink-600 hover:from-orange-600 hover:to-pink-700 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition transform border border-white/20 text-sm">
                Send Reset Link
            </button>
        </div>
    </form>
</x-guest-layout>
