<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARSABE // SECURE_AUTH</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Outfit:wght@300;400;600&display=swap"
        rel="stylesheet">

    <script>window.tailwind = { config: { darkMode: 'class' } };</script>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Cyberpunk Styles -->
    <style>
        body {
            background-color: #010206;
            background-image:
                linear-gradient(rgba(1, 2, 6, 0.85), rgba(1, 2, 6, 0.95)),
                url('/images/cyberpunk_bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 50%, rgba(255, 0, 119, 0.05) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .cyber-card {
            background: rgba(6, 6, 14, 0.9);
            border: 1px solid #00f0ff;
            box-shadow:
                0 0 20px rgba(0, 240, 255, 0.15),
                inset 0 0 20px rgba(0, 240, 255, 0.05);
            position: relative;
            clip-path: polygon(0 0, 93% 0, 100% 7%, 100% 100%, 7% 100%, 0 93%);
        }

        .cyber-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #00f0ff, #ff0077);
        }

        .cyber-card::after {
            content: 'SYS.AUTH_v2.0';
            position: absolute;
            bottom: 4px;
            right: 12px;
            font-family: 'Orbitron', monospace;
            font-size: 8px;
            color: #ff0077;
            letter-spacing: 1px;
            opacity: 0.7;
        }

        .cyber-btn {
            background: transparent;
            border: 2px solid #ff0077;
            color: #ff0077;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 8px rgba(255, 0, 119, 0.6);
            box-shadow: 0 0 10px rgba(255, 0, 119, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cyber-btn:hover {
            background: #ff0077;
            color: #000;
            box-shadow: 0 0 25px #ff0077;
            text-shadow: none;
            transform: scale(1.01);
        }

        .cyber-input {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid rgba(0, 240, 255, 0.4);
            color: #00f0ff;
            text-shadow: 0 0 5px rgba(0, 240, 255, 0.5);
            transition: all 0.3s ease;
        }

        .cyber-input:focus {
            border-color: #00f0ff;
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.3);
            outline: none;
        }

        .scanline {
            width: 100%;
            height: 100px;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(0, 240, 255, 0.08), rgba(255, 255, 255, 0));
            position: absolute;
            z-index: 10;
            top: -100px;
            animation: scan 6s linear infinite;
            pointer-events: none;
        }

        @keyframes scan {
            0% {
                top: -100px;
            }

            100% {
                top: 100%;
            }
        }

        .text-glow-cyan {
            text-shadow: 0 0 10px rgba(0, 240, 255, 0.6);
        }

        .text-glow-magenta {
            text-shadow: 0 0 10px rgba(255, 0, 119, 0.6);
        }
    </style>
</head>

<body class="flex flex-col min-h-screen text-slate-300 font-sans antialiased relative overflow-hidden">

    <!-- Scanline effect overlay -->
    <div class="scanline"></div>

    <!-- Header -->
    <header class="w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center relative z-10">
        <a href="/"
            class="flex items-center space-x-2 text-xl font-bold tracking-widest font-mono text-white hover:opacity-90 transition-opacity">
            <span class="text-glow-magenta text-[#ff0077]">PARSABE</span>
            <span class="text-slate-500 font-light text-sm">// SECURE_NODE</span>
        </a>
    </header>

    <!-- Main Container -->
    <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <div class="w-full max-w-md">

            <!-- Tech Header -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center p-3 bg-red-500/5 rounded-2xl border border-[#ff0077]/30 mb-4 shadow-[0_0_15px_rgba(255,0,119,0.1)]">
                    <svg class="w-9 h-9 text-[#ff0077] animate-pulse" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-3xl font-black font-mono tracking-widest text-white uppercase text-glow-cyan">
                    {{ $isSetup ? 'INITIALIZE_2FA' : 'CHALLENGE_REQUIRED' }}
                </h1>
                <p class="mt-2 text-xs font-mono text-slate-300 uppercase tracking-widest">
                    {{ $isSetup ? 'ACCESSING ADMIN NODE SECURITY SETUP' : 'SYSTEM LOCKDOWN ACTIVE // DECRYPT CREDENTIALS' }}
                </p>
            </div>

            <!-- Error Alerts -->
            @if (count($errors) > 0)
                <div class="mb-6 p-4 rounded-xl bg-red-950/20 border border-red-500/40 text-red-200 text-xs font-mono">
                    <div class="flex items-start">
                        <span class="mr-2">⚠️</span>
                        <div>
                            @foreach (is_array($errors) ? $errors : $errors->all() as $error)
                                <span class="block text-glow-magenta">{{ $error }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Cyberpunk Card -->
            <div class="cyber-card rounded-3xl p-8 sm:p-10">

                @if($isSetup)
                    <!-- Setup State -->
                    <div class="mb-6 space-y-5">
                        <div
                            class="p-4 bg-[#ff0077]/5 border border-[#ff0077]/30 rounded-xl text-xs font-mono text-slate-300 leading-relaxed">
                            <strong class="font-bold text-[#ff0077] block mb-1">ENROLLMENT INSTRUCTIONS:</strong>
                            1. Scan the QR code below using Google Authenticator, Authy, or Microsoft Authenticator.<br>
                            2. Alternatively, manually type the secret key shown below.<br>
                            3. Enter the generated 6-digit verification code below to authorize.
                        </div>

                        <!-- QR Code Container -->
                        <div
                            class="flex justify-center p-4 bg-white rounded-2xl max-w-[200px] mx-auto border border-[#00f0ff]/30 shadow-[0_0_15px_rgba(0,240,255,0.1)]">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode('otpauth://totp/Parsa%20Admin:parsabe99@gmail.com?secret=' . $secret . '&issuer=Parsa') }}"
                                alt="TOTP Setup QR Code" class="w-[180px] h-[180px]">
                        </div>

                        <div class="text-center p-3.5 bg-black/80 rounded-xl border border-slate-800">
                            <span
                                class="block text-[9px] font-bold font-mono tracking-widest text-slate-500 uppercase mb-1">Backup
                                Key</span>
                            <span
                                class="font-mono text-base font-bold text-[#00f0ff] tracking-widest text-glow-cyan">{{ $secret }}</span>
                        </div>
                    </div>
                @endif

                <!-- Verification Form -->
                <form action="{{ route('parsa.2fa.verify') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="code"
                            class="block text-xs font-mono font-bold uppercase tracking-widest text-slate-300 mb-2">
                            Enter 6-Digit Authenticator Token
                        </label>
                        <input id="code" name="code" type="text" required autofocus maxlength="6" pattern="[0-9]*"
                            inputmode="numeric"
                            class="cyber-input block w-full text-center py-4 rounded-xl text-3xl font-mono tracking-widest text-glow-cyan"
                            placeholder="000000">
                    </div>

                    <div>
                        <button type="submit"
                            class="cyber-btn w-full flex justify-center py-4 px-4 rounded-xl text-xs font-bold uppercase tracking-widest">
                            Authorize Session
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer
        class="w-full max-w-7xl mx-auto px-6 py-6 text-center font-mono text-[9px] text-slate-600 tracking-wider relative z-10">
        SECURE GATEWAY // PORT_443 // AUTH_REQUIRED // &copy; 2026 PARSABE
    </footer>
</body>

</html>