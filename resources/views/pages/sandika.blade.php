<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandika Arkham Portal - Parsa Besharat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="{{ asset('js/tailwind-config.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/sandika.css') }}">
</head>

<body class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-3 lg:p-8 min-h-screen relative overflow-x-hidden">

    <!-- MAIN FLOATING WINDOW CONTAINER (MATCHES HOMEPAGE & CHAT EXACTLY) -->
    <div id="main-container" class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden h-[88vh] z-10 transition-all duration-700 shadow-2xl border border-white/10">

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

        <!-- MAIN SANDIKA PORTAL CONTENT -->
        <main class="flex-1 flex flex-col overflow-y-auto relative p-6 lg:p-8 bg-black/30 gap-6">
            
            <!-- Header Title -->
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600/30 border border-indigo-500/40 flex items-center justify-center text-2xl shadow-lg">
                        🛡️
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                            SANDIKA ARKHAM SYSTEM
                            <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">v4.8 Active</span>
                        </h1>
                        <p class="text-xs text-gray-400">Tactical Agent Ranks, Audio Spectrum Log Analyzer & File Vault</p>
                    </div>
                </div>
            </div>

            <!-- USER RANK & PROGRESS CARD -->
            @php
                $userLevel = is_object($rank) ? $rank->level : 1;
                $userTitle = is_object($rank) ? $rank->rank_title : 'Novice Operative';
                $userXp = is_object($rank) ? $rank->xp : 50;
                $userFiles = is_object($rank) ? $rank->files_processed : 0;
            @endphp
            <div class="sandika-rank-badge p-6 rounded-3xl backdrop-blur-xl flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 p-0.5 shadow-xl">
                        <div class="w-full h-full bg-gray-900 rounded-full flex items-center justify-center text-2xl font-bold text-indigo-400">
                            L<span id="user-level-val">{{ $userLevel }}</span>
                        </div>
                    </div>
                    <div>
                        <h2 id="user-title-val" class="text-lg font-bold text-white tracking-wide">
                            {{ $userTitle }}
                        </h2>
                        <p class="text-xs text-indigo-300">Current XP: <span id="user-xp-val" class="font-bold text-white">{{ $userXp }}</span> / Next Rank Tier</p>
                    </div>
                </div>

                <!-- XP Bar -->
                <div class="w-full md:w-64 space-y-1.5">
                    <div class="flex justify-between text-[11px] font-mono text-gray-400">
                        <span>XP Progress</span>
                        <span>{{ $userXp % 100 }}%</span>
                    </div>
                    <div class="w-full h-3 bg-black/40 rounded-full overflow-hidden border border-white/10 p-0.5">
                        <div id="xp-progress-bar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-500" style="width: {{ $userXp % 100 }}%;"></div>
                    </div>
                </div>
            </div>

            <!-- TACTICAL TOOLS GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Voice Log Audio Spectrum Analyzer -->
                <div class="arkham-terminal p-6 rounded-3xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                                🎙️ Voice Transmission Analyzer
                            </h3>
                            <span class="text-[10px] font-mono text-emerald-400">SYSTEM READY</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-4">Run real-time audio spectral analysis on incoming voice notes to extract hidden tactical metadata.</p>
                        
                        <!-- Animated Spectrum Bars -->
                        <div class="h-16 bg-black/50 border border-indigo-500/30 rounded-xl p-3 flex items-center justify-center gap-1 mb-4">
                            <div class="w-1.5 bg-indigo-500 rounded-full waveform-bar h-1/2"></div>
                            <div class="w-1.5 bg-purple-500 rounded-full waveform-bar h-3/4"></div>
                            <div class="w-1.5 bg-indigo-400 rounded-full waveform-bar h-full"></div>
                            <div class="w-1.5 bg-pink-500 rounded-full waveform-bar h-2/3"></div>
                            <div class="w-1.5 bg-indigo-500 rounded-full waveform-bar h-5/6"></div>
                            <div class="w-1.5 bg-purple-400 rounded-full waveform-bar h-1/3"></div>
                        </div>

                        <div id="voice-status" class="text-xs mb-3 min-h-[20px]"></div>
                    </div>

                    <button id="btn-analyze-voice" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-xs rounded-xl shadow-lg hover:opacity-90 active:scale-95 transition-all">
                        RUN VOICE ANALYSIS (+45 XP)
                    </button>
                </div>

                <!-- Secure File Storage Vault -->
                <div class="arkham-terminal p-6 rounded-3xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-purple-400 uppercase tracking-wider flex items-center gap-2">
                                📁 Arkham Encrypted File Vault
                            </h3>
                            <span class="text-[10px] font-mono text-indigo-400">UP TO 4GB</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-4">Upload and process tactical files, documents, or media into the Sandika Arkham repository.</p>

                        <!-- File Dropzone -->
                        <label for="sandika-file-input" class="cursor-pointer border-2 border-dashed border-purple-500/30 hover:border-purple-400 bg-black/40 rounded-xl p-6 flex flex-col items-center justify-center transition-all mb-3 group">
                            <span class="text-3xl mb-1 group-hover:scale-110 transition-transform">📤</span>
                            <span class="text-xs font-semibold text-purple-300">Click to Select File</span>
                            <span class="text-[10px] text-gray-500">Supports images, audio, video, docs</span>
                        </label>
                        <input type="file" id="sandika-file-input" class="hidden">

                        <div id="file-status" class="text-xs mb-3 min-h-[20px]"></div>
                    </div>

                    <div class="text-[10px] text-gray-500 font-mono text-center border-t border-white/5 pt-2">
                        Processed Files: <span class="text-white">{{ $userFiles }}</span>
                    </div>
                </div>

            </div>

        </main>

    </div>

    <!-- External Sandika ESM Script -->
    <script type="module" src="{{ asset('js/sandika.js') }}"></script>
</body>
</html>
