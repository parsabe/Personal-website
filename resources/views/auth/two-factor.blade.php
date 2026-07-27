<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Verification - Parsa Besharat</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="{{ asset('js/tailwind-config.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
</head>

<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md bg-gray-900/90 border border-white/20 p-8 rounded-3xl backdrop-blur-xl shadow-2xl">
        <div class="text-center mb-6">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-2xl shadow-lg">
                🔐
            </div>
            <h1 class="text-xl font-bold tracking-tight text-white">Two-Factor Authentication</h1>
            <p class="text-xs text-gray-400 mt-1">Securing account access for <span class="text-emerald-400 font-semibold">{{ $user->email }}</span></p>
        </div>

        @if ($isSetup)
            <div class="mb-6 p-4 bg-blue-950/60 border border-blue-500/30 rounded-2xl text-xs space-y-2">
                <p class="font-bold text-blue-300">📱 2FA Setup Required:</p>
                <p class="text-gray-300">Add the secret key below to your Authenticator app (e.g. Google Authenticator, 1Password, Authy):</p>
                <div class="p-2.5 bg-black/60 border border-white/10 rounded-xl font-mono text-center text-sm tracking-widest text-amber-300 select-all">
                    {{ $secret }}
                </div>
            </div>
        @endif

        @error('code')
            <div class="mb-4 p-3 bg-rose-950/80 border border-rose-500/40 rounded-xl text-xs text-rose-300">
                {{ $message }}
            </div>
        @enderror

        <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-4 text-xs">
            @csrf
            <div>
                <label for="code" class="block font-medium text-gray-300 mb-1.5 text-center">
                    Enter 6-Digit Authenticator Code
                </label>
                <input type="text" id="code" name="code" required maxlength="6" autofocus placeholder="123456"
                    class="w-full bg-black/40 border border-white/20 rounded-xl px-4 py-3 text-center text-2xl font-mono tracking-widest text-white focus:outline-none focus:border-blue-500">
            </div>

            <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl shadow-lg transition active:scale-95">
                Verify & Continue
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-gray-400">
            <a href="{{ route('login') }}" class="hover:underline text-gray-400">← Back to Login</a>
        </div>
    </div>
</body>

</html>
