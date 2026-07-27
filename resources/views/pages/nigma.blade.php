<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edward Nigma Cypher Portal - Parsa Besharat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>window.tailwind = { config: { darkMode: 'class' } };</script>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/nigma.css') }}">
</head>

<body class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-3 lg:p-8 min-h-screen relative overflow-x-hidden">

    <!-- MAIN FLOATING WINDOW CONTAINER (MATCHES HOMEPAGE & CHAT EXACTLY) -->
    <div id="main-container" class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden h-[88vh] z-10 transition-all duration-700 shadow-2xl border border-emerald-500/20">

        <!-- Top Right Window Controls -->
        <div class="absolute top-5 right-6 flex items-center gap-4 z-40">
            <div class="flex gap-2">
                <div class="w-3.5 h-3.5 rounded-full bg-[#ff5f56] shadow-sm border border-[#e0443e]"></div>
                <div class="w-3.5 h-3.5 rounded-full bg-[#ffbd2e] shadow-sm border border-[#dea123]"></div>
                <div class="w-3.5 h-3.5 rounded-full bg-[#27c93f] shadow-sm border border-[#1aab29]"></div>
            </div>
        </div>

        <!-- SIDEBAR INTEGRATED INSIDE CONTAINER -->
        @include('sidebar')

        <!-- MAIN NIGMA PORTAL CONTENT -->
        <main class="flex-1 flex flex-col overflow-y-auto relative p-6 lg:p-8 matrix-bg gap-6">
            
            <!-- Header Title -->
            <div class="flex items-center justify-between border-b border-emerald-500/30 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-3xl shadow-lg shadow-emerald-500/20">
                        ❓
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-emerald-400 nigma-glow-text font-mono flex items-center gap-2">
                            EDWARD NIGMA CYPHER PORTAL
                            <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">RIDDLER LOG ACTIVE</span>
                        </h1>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400/70 font-mono">Encrypted Transmissions, Cryptographic Puzzles & Riddles</p>
                    </div>
                </div>
            </div>

            <!-- RIDDLER DECODER CARD -->
            <div class="nigma-emerald-card p-6 rounded-3xl backdrop-blur-xl">
                <h2 class="text-base font-bold text-emerald-400 font-mono mb-2 flex items-center gap-2">
                    🔓 INCOMING TRANSMISSION DECODER
                </h2>
                <p class="text-xs text-gray-300 mb-4 font-mono">Solve the cryptic transmission below by decoding the cipher phrase.</p>

                @if(isset($puzzles[0]))
                    <div class="p-4 bg-black/60 border border-emerald-500/40 rounded-2xl mb-4 font-mono">
                        <div class="text-xs text-emerald-500 font-bold mb-1">[ {{ $puzzles[0]->title }} ]</div>
                        <div class="text-sm text-emerald-200 italic mb-2">"{{ $puzzles[0]->riddle }}"</div>
                        <div class="text-[11px] text-gray-400">Cipher Method: <span class="text-emerald-400 font-bold">{{ $puzzles[0]->cipher_type }}</span></div>
                    </div>
                @endif

                <div class="space-y-3">
                    <input type="text" id="nigma-answer-input" placeholder="Type solution text..."
                        class="w-full bg-black/50 border border-emerald-500/40 rounded-xl px-4 py-3 text-sm text-emerald-300 placeholder-emerald-700 font-mono focus:outline-none focus:border-emerald-400 shadow-inner">
                    
                    <button id="btn-solve-riddle" class="w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-black font-mono font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
                        SUBMIT CYPHER SOLUTION
                    </button>

                    <div id="nigma-result" class="text-xs min-h-[20px]"></div>
                </div>
            </div>

            <!-- RECENT PUZZLE TRANSMISSIONS LIST -->
            <div class="nigma-emerald-card p-6 rounded-3xl">
                <h3 class="text-sm font-bold text-emerald-400 font-mono mb-4 flex items-center gap-2">
                    📜 TRANSMISSION ARCHIVE
                </h3>
                <div class="space-y-3">
                    @foreach($puzzles as $p)
                        <div class="p-3 bg-black/40 border border-emerald-500/20 rounded-xl flex items-center justify-between font-mono text-xs">
                            <div>
                                <span class="text-emerald-300 font-bold">{{ $p->title }}</span>
                                <span class="text-[10px] text-emerald-600 block">{{ $p->cipher_type }}</span>
                            </div>
                            <div>
                                @if($p->is_solved)
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 text-[10px]">SOLVED</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/40 text-[10px]">LOCKED</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </main>

    </div>

    <!-- External Nigma ESM Script -->
    <script type="module" src="{{ asset('js/nigma.js') }}"></script>
</body>
</html>
