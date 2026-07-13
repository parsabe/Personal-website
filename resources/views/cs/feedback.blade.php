<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CS Career Fair - Feedback Portal</title>
    <!-- Google Fonts: Rye (Western), Special Elite (Typewriter), Playfair Display (Serif) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rye&family=Special+Elite&family=Playfair+Display:ital,wght@0,600;0,800;1,600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        rye: ['Rye', 'cursive'],
                        typewriter: ['Special Elite', 'monospace'],
                        serif: ['Playfair Display', 'serif'],
                    },
                },
            },
        }
    </script>
    
    <!-- Red Dead Redemption 2 Western Theme Styles -->
    <style>
        body {
            background-color: #120d0a;
            /* Gritty dark wood / leather texture gradient with signature red aura */
            background-image: 
                radial-gradient(circle at 50% 30%, rgba(200, 16, 46, 0.25) 0%, transparent 60%),
                radial-gradient(circle at 0% 100%, rgba(74, 51, 25, 0.3) 0%, transparent 50%),
                repeating-linear-gradient(rgba(0,0,0,0.15) 0px, rgba(0,0,0,0.15) 1px, transparent 1px, transparent 3px);
            min-height: 100vh;
        }

        /* Weathered Parchment Paper Card */
        .parchment-card {
            background-color: #ebdcb9;
            color: #1c1511;
            border: 4px solid #3d2516;
            box-shadow: 
                0 0 0 4px #ebdcb9, 
                0 0 0 8px #3d2516,
                0 20px 40px rgba(0, 0, 0, 0.8),
                inset 0 0 60px rgba(139, 94, 60, 0.4);
            position: relative;
            background-image: radial-gradient(circle, transparent 70%, rgba(139, 94, 60, 0.15) 100%);
        }

        /* Distressed wood board/banner background */
        .wood-banner {
            background-color: #c8102e; /* RDR2 Red */
            color: #ffffff;
            font-family: 'Rye', cursive;
            text-shadow: 2px 2px 0px #000;
            border: 2px solid #000;
            box-shadow: 3px 3px 0px #000;
        }

        /* Input field styled as vintage ledger entries */
        .ledger-input {
            background-color: rgba(255, 255, 255, 0.3);
            border-bottom: 2px solid #3d2516;
            color: #1c1511;
            font-family: 'Special Elite', monospace;
            transition: all 0.2s ease-in-out;
        }

        .ledger-input:focus {
            background-color: rgba(255, 255, 255, 0.6);
            border-bottom-color: #c8102e;
            outline: none;
            box-shadow: none;
        }

        /* Western Style Button */
        .western-btn {
            background-color: #c8102e; /* Signature RDR2 Red */
            color: #fff;
            font-family: 'Rye', cursive;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border: 2px solid #000;
            box-shadow: 3px 3px 0px #000;
            text-shadow: 1px 1px 0px #000;
            transition: all 0.1s ease;
        }

        .western-btn:hover {
            background-color: #e01b3c;
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0px #000;
        }

        .western-btn:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px #000;
        }

        /* Runaway button styled as torn paper tag */
        .peeling-tag {
            background-color: #dcbfa2;
            border: 1px dashed #3d2516;
            font-family: 'Special Elite', monospace;
            color: #5c3b24;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.15);
            transition: all 0.1s ease-out;
        }

        /* Vintage Brass Audio Player */
        .brass-player {
            background: linear-gradient(135deg, #e3c480 0%, #aa7c11 100%);
            border: 4px solid #3d2516;
            box-shadow: 
                0 10px 25px rgba(0,0,0,0.5), 
                inset 0 2px 5px rgba(255,255,255,0.4),
                inset 0 -2px 5px rgba(0,0,0,0.4);
            color: #1c1511;
        }

        /* Circular layout for player */
        .brass-dial {
            border: 2px solid #5c3b24;
            background-color: #ebdcb9;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.2);
        }

        /* Antique scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #120d0a;
        }
        ::-webkit-scrollbar-thumb {
            background: #3d2516;
            border: 1px solid #ebdcb9;
        }

        /* Soundwave animation */
        .sound-wave {
            display: flex;
            align-items: flex-end;
            gap: 2px;
            height: 12px;
        }

        .sound-wave span {
            display: block;
            width: 2px;
            background-color: #c8102e;
            border-radius: 1px;
            animation: wave 1s ease-in-out infinite alternate;
        }

        .sound-wave.playing span {
            animation-play-state: running;
        }

        .sound-wave.paused span {
            animation-play-state: paused;
            height: 2px !important;
        }

        .sound-wave span:nth-child(1) { height: 60%; animation-delay: 0.1s; }
        .sound-wave span:nth-child(2) { height: 100%; animation-delay: 0.2s; }
        .sound-wave span:nth-child(3) { height: 40%; animation-delay: 0.3s; }
        .sound-wave span:nth-child(4) { height: 80%; animation-delay: 0.4s; }
        .sound-wave span:nth-child(5) { height: 50%; animation-delay: 0.5s; }

        @keyframes wave {
            0% { height: 20%; }
            100% { height: 100%; }
        }
    </style>
</head>
<body class="flex flex-col min-h-screen text-slate-200 font-typewriter antialiased relative">

    <!-- Header / Navbar -->
    <header class="w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center z-10 border-b border-stone-800">
        <a href="/" class="flex items-center space-x-2 text-xl font-bold tracking-tight text-white hover:opacity-90 transition-opacity">
            <span class="font-rye text-2xl text-[#c8102e] tracking-wider">PARSABE</span>
            <span class="text-stone-500 font-medium text-xs font-typewriter">/ CS Portal</span>
        </a>
        <div class="flex items-center space-x-4">
            <a href="{{ route('cs.certificates.index') }}" class="text-xs font-semibold text-stone-400 hover:text-[#c8102e] transition-colors uppercase tracking-wider font-rye">
                Certificates
            </a>
            @if(session()->has('cs_feedback_email'))
                <span class="text-stone-700">|</span>
                <form action="{{ route('cs.feedback.reset') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-[#c8102e] hover:text-red-400 transition-colors uppercase tracking-wider font-rye">
                        Reset Entry
                    </button>
                </form>
            @endif
            <span class="text-stone-700">|</span>
            <a href="/" class="text-xs font-semibold text-stone-400 hover:text-white transition-colors uppercase tracking-wider font-rye">
                ← Back
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12 z-10">
        <div class="w-full max-w-xl">
            
            <!-- Wild West Poster Header -->
            <div class="text-center mb-10">
                <div class="inline-block px-6 py-2 wood-banner mb-4">
                    <span class="tracking-widest text-lg md:text-xl">★ WANTED ★</span>
                </div>
                <h1 class="text-4xl font-extrabold tracking-wide text-stone-100 font-rye uppercase drop-shadow-[0_4px_4px_rgba(0,0,0,0.8)]">
                    Feedback Ledger
                </h1>
                <p class="mt-2 text-xs text-[#c8102e] font-semibold tracking-widest uppercase">
                    CS Career Fair Division
                </p>
            </div>

            <!-- Errors & Messages -->
            @if (count($errors) > 0)
                <div class="mb-6 p-4 border-2 border-[#c8102e] bg-black/70 text-red-200 text-xs font-typewriter shadow-lg">
                    <div class="flex items-start">
                        <span class="text-base mr-3">⚠️</span>
                        <div>
                            <span class="font-bold block mb-1 uppercase tracking-wider text-[#c8102e]">ERROR ENCOUNTERED:</span>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach (is_array($errors) ? $errors : $errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-5 border-2 border-stone-800 bg-[#ebdcb9] text-[#1c1511] text-xs shadow-lg relative">
                    <div class="absolute -right-3 -top-3 wood-banner px-2 py-0.5 text-[9px] tracking-widest">OK</div>
                    <div class="flex items-start">
                        <span class="text-base mr-3">✍️</span>
                        <div>
                            <span class="font-bold block text-sm uppercase tracking-wider font-rye mb-1">RECORDED SUCCESSFULLY</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- State 1: Email Verification Form -->
            @if ($state === 'verify')
                <div class="parchment-card rounded-none p-8 sm:p-10">
                    <form action="{{ route('cs.feedback.verify') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#3d2516] mb-2">
                                State Your Registered Email Address
                            </label>
                            <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}"
                                class="block w-full px-4 py-3 ledger-input focus:ring-0 focus:border-stone-800 transition-all text-sm"
                                placeholder="e.g. john.marston@frontier.com">
                            <p class="mt-2 text-[10px] text-stone-600 font-semibold italic">
                                * Must match the ledger records for Campus Specialists.
                            </p>
                        </div>
                        <div>
                            <button type="submit" class="western-btn w-full flex justify-center py-4 px-4 text-sm font-bold">
                                Enter Ledger
                            </button>
                        </div>
                    </form>
                </div>

            <!-- State 2: Test Selector for parsabe99@gmail.com -->
            @elseif ($state === 'test_selector')
                <div class="parchment-card rounded-none p-8 sm:p-10 text-center">
                    <h3 class="text-xl font-bold font-rye text-[#c8102e] mb-2">Howdy, Parsa!</h3>
                    <p class="text-xs text-stone-700 mb-6 font-semibold italic">
                        Logged in as <span class="underline">parsabe99@gmail.com</span>.<br>
                        Select which scenario to examine:
                    </p>
                    
                    <div class="space-y-4">
                        <form action="{{ route('cs.feedback.test-scenario') }}" method="POST">
                            @csrf
                            <input type="hidden" name="scenario" value="1">
                            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border-2 border-[#3d2516] hover:bg-stone-800/10 rounded-none text-xs font-bold text-[#3d2516] transition-all font-rye uppercase">
                                Scenario 1: Did Not Show Up
                            </button>
                        </form>

                        <form action="{{ route('cs.feedback.test-scenario') }}" method="POST">
                            @csrf
                            <input type="hidden" name="scenario" value="2">
                            <button type="submit" class="w-full flex justify-center py-3.5 px-4 western-btn rounded-none text-xs font-bold transition-all">
                                Scenario 2: Showed Up
                            </button>
                        </form>
                    </div>
                </div>

            <!-- State 3: Feedback Submission Form -->
            @elseif ($state === 'form')
                <div class="parchment-card rounded-none p-8 sm:p-10" id="feedback-card">
                    <!-- Fun scenario notification messages -->
                    @if ($scenario == 1)
                        <div class="mb-8 p-5 border-2 border-dashed border-[#c8102e] bg-[#f4efe1]/40 text-stone-800 text-xs leading-relaxed relative">
                            <div class="absolute right-4 bottom-2 opacity-10 pointer-events-none font-rye text-5xl text-[#c8102e]">
                                ★
                            </div>
                            <span class="text-[#c8102e] font-extrabold text-sm block font-rye mb-2 tracking-widest">★ WARNING ★</span>
                            Pal. You signed as campus specialist but you did not show up. and now I will not give you my heart and your gifts.<br><br>
                            i am just messing with you :)))<br>
                            you get to have my kindness. you can simply vote what was you expectations from us and some more.
                        </div>
                    @else
                        <div class="mb-8 p-5 border-2 border-stone-800 bg-[#ebdcb9] text-[#1c1511] text-xs leading-relaxed relative shadow-inner">
                            <div class="absolute right-4 bottom-2 opacity-5 pointer-events-none font-rye text-5xl">
                                📜
                            </div>
                            <span class="text-stone-800 font-extrabold text-sm block font-rye mb-2 tracking-widest">★ WELCOME PARTNER ★</span>
                            Hey pal. Thanks for joining in as Campus Specialist. we are grateful. Could you please give us you feed back about ORTE, the processing and some more please.
                        </div>
                    @endif

                    <form action="{{ route('cs.feedback.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Email display (read-only for security & clarity) -->
                        <div class="pb-2 border-b border-[#3d2516]/20">
                            <span class="block text-[10px] font-bold text-stone-600 uppercase tracking-wider mb-1">Outlaw Identifier:</span>
                            <span class="text-[#3d2516] font-bold text-xs bg-white/30 px-3 py-1 border border-[#3d2516]/30 inline-block">{{ $email }}</span>
                        </div>

                        <!-- Conditional Form Fields -->
                        @if ($scenario == 1)
                            <!-- Expectations / Suggestions only (Scenario 1) -->
                            <div>
                                <label for="ideas" class="block text-xs font-bold uppercase tracking-wider text-[#3d2516] mb-2">
                                    State Your Expectations & Suggestions For Us:
                                </label>
                                <textarea id="ideas" name="ideas" rows="6" required
                                    class="block w-full px-4 py-3 ledger-input focus:ring-0 transition-all text-sm"
                                    placeholder="Write your expectations, complaints, or feedback here..."></textarea>
                            </div>
                        @else
                            <!-- Full form (Scenario 2) -->
                            <!-- Suggestions -->
                            <div>
                                <label for="ideas" class="block text-xs font-bold uppercase tracking-wider text-[#3d2516] mb-2">
                                    Any suggestions to make the career fair route smoother?
                                </label>
                                <textarea id="ideas" name="ideas" rows="3" required
                                    class="block w-full px-4 py-3 ledger-input focus:ring-0 transition-all text-sm"
                                    placeholder="Improving scheduling, organizers, etc...">{{ old('ideas') }}</textarea>
                            </div>

                            <!-- General Feedback -->
                            <div>
                                <label for="feedback" class="block text-xs font-bold uppercase tracking-wider text-[#3d2516] mb-2">
                                    General Feedback about ORTE & operations:
                                </label>
                                <textarea id="feedback" name="feedback" rows="3" required
                                    class="block w-full px-4 py-3 ledger-input focus:ring-0 transition-all text-sm"
                                    placeholder="Write down your experience here...">{{ old('feedback') }}</textarea>
                            </div>

                            <!-- Questions -->
                            <div>
                                <label for="questions" class="block text-xs font-bold uppercase tracking-wider text-[#3d2516] mb-2">
                                    Outstanding Questions:
                                </label>
                                <textarea id="questions" name="questions" rows="3" required
                                    class="block w-full px-4 py-3 ledger-input focus:ring-0 transition-all text-sm"
                                    placeholder="Certificates, badges, payouts, or next events?">{{ old('questions') }}</textarea>
                            </div>

                            <!-- Received All Files -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-[#3d2516] mb-3">
                                    Have you successfully secured all certificates & items?
                                </label>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                    <label class="flex items-center space-x-3 cursor-pointer group">
                                        <input type="radio" name="received_all_files" value="yes" required {{ old('received_all_files') === 'yes' ? 'checked' : '' }}
                                            class="w-4 h-4 text-[#c8102e] border-[#3d2516] focus:ring-0 focus:ring-offset-0">
                                        <span class="text-xs text-stone-800 font-bold group-hover:text-black transition-colors">Yes, secured everything</span>
                                    </label>
                                    <label class="flex items-center space-x-3 cursor-pointer group">
                                        <input type="radio" name="received_all_files" value="no" required {{ old('received_all_files') === 'no' ? 'checked' : '' }}
                                            class="w-4 h-4 text-[#c8102e] border-[#3d2516] focus:ring-0 focus:ring-offset-0">
                                        <span class="text-xs text-stone-800 font-bold group-hover:text-black transition-colors">No, cargo is missing</span>
                                    </label>
                                </div>
                            </div>
                        @endif

                        <!-- Prank Action Buttons Container -->
                        <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 min-h-[90px] relative w-full" id="vote-container">
                            <!-- Left: Submission "I vote" -->
                            <button type="submit" class="western-btn w-full sm:w-auto px-8 py-4 text-xs font-bold z-10">
                                I vote
                            </button>
                            
                            <!-- Right: Prank runaway button "I do not want to vote" -->
                            <button type="button" id="btn-no-vote" tabindex="-1" class="peeling-tag w-full sm:w-auto px-6 py-4 text-xs font-bold z-20 cursor-default select-none whitespace-nowrap">
                                I do not want to vote
                            </button>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </main>

    <!-- Floating Antique Brass Audio Player -->
    <div class="fixed bottom-6 right-6 z-50 brass-player rounded-full p-3 flex items-center space-x-3 shadow-2xl transition-all hover:scale-105" id="audio-player-container">
        <div class="flex items-center space-x-2">
            <!-- Play/Pause Button -->
            <button id="audio-play-pause-btn" class="p-2 bg-[#3d2516] hover:bg-[#c8102e] text-[#ebdcb9] rounded-full transition-all focus:outline-none" title="Play / Pause">
                <!-- Play Icon -->
                <svg id="play-icon" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4.5 3.5v13L16 10z"/></svg>
                <!-- Pause Icon -->
                <svg id="pause-icon" class="w-3.5 h-3.5 hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
            </button>
            
            <!-- Stop Button -->
            <button id="audio-stop-btn" class="p-1.5 text-[#3d2516] hover:text-[#c8102e] rounded-lg transition-all focus:outline-none" title="Stop">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 6h8v8H6z"/></svg>
            </button>
            
            <!-- Repeat/Loop Button -->
            <button id="audio-repeat-btn" class="p-1.5 text-[#3d2516] hover:text-[#c8102e] rounded-lg transition-all focus:outline-none relative" title="Toggle Loop">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.656 48.656 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3M4.5 12a48.374 48.374 0 00-.138 3.662 4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l-3 3m3-3l3 3"/></svg>
                <span id="repeat-badge" class="absolute top-0.5 right-0.5 w-1 h-1 bg-[#c8102e] rounded-full hidden"></span>
            </button>
        </div>

        <div class="flex flex-col pr-2 select-none border-l border-[#3d2516]/30 pl-2">
            <span class="text-[9px] font-bold text-[#3d2516] uppercase tracking-wider">FONOGRAMA</span>
            <div class="flex items-center space-x-1">
                <span class="text-[8px] text-[#5c3b24] font-bold">haha.mp3</span>
                <!-- Sound wave animation -->
                <div id="wave-anim" class="sound-wave paused">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>

        <!-- Hidden Audio Element -->
        <audio id="bg-audio" src="/haha.mp3" preload="auto"></audio>
    </div>

    <!-- Footer -->
    <footer class="w-full max-w-7xl mx-auto px-6 py-6 text-center text-stone-600 text-[10px] mt-auto z-10 border-t border-stone-900">
        &copy; 1899 Parsa Besharat. All rights reserved.
    </footer>

    <!-- Interactive Javascript Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ==========================================
            // Runaway Prank Button Script ("I do not want to vote")
            // ==========================================
            const noBtn = document.getElementById('btn-no-vote');
            const card = document.getElementById('feedback-card');

            if (noBtn && card) {
                const voteContainer = document.getElementById('vote-container');
                noBtn.style.position = 'absolute';
                
                resetNoButtonPosition();

                function resetNoButtonPosition() {
                    noBtn.style.left = '';
                    noBtn.style.right = '0px';
                    noBtn.style.top = '10px';
                }

                function moveNoButton() {
                    const containerWidth = voteContainer.clientWidth;
                    const containerHeight = card.clientHeight;
                    
                    const btnWidth = noBtn.clientWidth;
                    const btnHeight = noBtn.clientHeight;
                    
                    const cardRect = card.getBoundingClientRect();
                    const containerRect = voteContainer.getBoundingClientRect();
                    
                    const minY = cardRect.top - containerRect.top + 20;
                    const maxY = cardRect.bottom - containerRect.top - btnHeight - 20;
                    
                    const minX = cardRect.left - containerRect.left + 20;
                    const maxX = cardRect.right - containerRect.left - btnWidth - 20;

                    let newX = Math.random() * (maxX - minX) + minX;
                    let newY = Math.random() * (maxY - minY) + minY;

                    noBtn.style.right = '';
                    noBtn.style.left = `${newX}px`;
                    noBtn.style.top = `${newY}px`;
                }

                noBtn.addEventListener('mouseenter', moveNoButton);
                noBtn.addEventListener('mouseover', moveNoButton);
                noBtn.addEventListener('touchstart', (e) => {
                    e.preventDefault();
                    moveNoButton();
                });

                noBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });
            }

            // ==========================================
            // Audio Player Controls & Autoplay Logic
            // ==========================================
            const audio = document.getElementById('bg-audio');
            const playPauseBtn = document.getElementById('audio-play-pause-btn');
            const stopBtn = document.getElementById('audio-stop-btn');
            const repeatBtn = document.getElementById('audio-repeat-btn');
            const repeatBadge = document.getElementById('repeat-badge');
            const waveAnim = document.getElementById('wave-anim');
            
            const playIcon = document.getElementById('play-icon');
            const pauseIcon = document.getElementById('pause-icon');

            let isRepeat = false;

            function setPlayingUI() {
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
                waveAnim.classList.remove('paused');
                waveAnim.classList.add('playing');
            }

            function setPausedUI() {
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                waveAnim.classList.add('paused');
                waveAnim.classList.remove('playing');
            }

            playPauseBtn.addEventListener('click', () => {
                if (audio.paused) {
                    audio.play().then(setPlayingUI).catch(console.error);
                } else {
                    audio.pause();
                    setPausedUI();
                }
            });

            stopBtn.addEventListener('click', () => {
                audio.pause();
                audio.currentTime = 0;
                setPausedUI();
            });

            repeatBtn.addEventListener('click', () => {
                isRepeat = !isRepeat;
                audio.loop = isRepeat;
                if (isRepeat) {
                    repeatBadge.classList.remove('hidden');
                    repeatBtn.classList.add('text-[#c8102e]');
                } else {
                    repeatBadge.classList.add('hidden');
                    repeatBtn.classList.remove('text-[#c8102e]');
                }
            });

            window.addEventListener('load', () => {
                setTimeout(() => {
                    const playPromise = audio.play();
                    
                    if (playPromise !== undefined) {
                        playPromise.then(() => {
                            setPlayingUI();
                        }).catch(error => {
                            console.log("Autoplay blocked. Waiting for interaction...");
                            const startOnInteraction = () => {
                                audio.play().then(() => {
                                    setPlayingUI();
                                    document.removeEventListener('click', startOnInteraction);
                                    document.removeEventListener('keydown', startOnInteraction);
                                }).catch(console.error);
                            };
                            document.addEventListener('click', startOnInteraction);
                            document.addEventListener('keydown', startOnInteraction);
                        });
                    }
                }, 1500);
            });

            audio.addEventListener('ended', () => {
                if (!isRepeat) {
                    setPausedUI();
                }
            });
        });
    </script>
</body>
</html>
