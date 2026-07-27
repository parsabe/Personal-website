<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandika Operational Hub - Parsa Besharat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>window.tailwind = { config: { darkMode: 'class' } };</script>
    <script src="https://cdn.tailwindcss.com"></script>
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
                            𝐒 𝐀 𝐍 𝐃 𝐈 𝐊 𝐀
                            <span class="text-[10px] uppercase font-mono px-2.5 py-0.5 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">OPERATIONAL HUB</span>
                        </h1>
                        <p class="text-xs text-gray-400">Contribution Points (CP), Agent Ranks, Cipher Vault & Lexicon Network</p>
                    </div>
                </div>
            </div>

            <!-- USER RANK & CP BADGE -->
            @php
                $userLevel = is_object($rank) ? $rank->level : 3;
                $userTitle = is_object($rank) ? $rank->rank_title : 'Captain ⚔️ (Verified)';
                $userCp = is_object($rank) ? $rank->xp : 50;
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
                        <p class="text-xs text-indigo-300">Contribution Points (CP): <span id="user-xp-val" class="font-bold text-white">{{ $userCp }} CP</span></p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-full text-xs font-semibold">
                        {{ $userCp >= 50 ? '✅ Verified Agent' : '⏳ Verification Threshold (50 CP)' }}
                    </span>
                </div>
            </div>

            <!-- CONCEPT TAB MENU BAR -->
            <div class="flex flex-wrap gap-2 p-1.5 bg-black/40 border border-white/10 rounded-2xl">
                <button class="sandika-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-indigo-600/40 border border-indigo-400 text-white" data-target="tab-rules">
                    📜 Rules & Ranks
                </button>
                <button class="sandika-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-black/30 border border-white/10 text-gray-400" data-target="tab-stories">
                    📖 Stories Hub
                </button>
                <button class="sandika-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-black/30 border border-white/10 text-gray-400" data-target="tab-dictionary">
                    📚 Lexicon Dictionary
                </button>
                <button class="sandika-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-black/30 border border-white/10 text-gray-400" data-target="tab-git">
                    💻 Git Insights
                </button>
                <button class="sandika-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-black/30 border border-white/10 text-gray-400" data-target="tab-tools">
                    ⚙️ Tactical Tools & ROT13
                </button>
            </div>

            <!-- TAB 1: RULES & RANKS -->
            <div id="tab-rules" class="sandika-tab-content space-y-4">
                <div class="arkham-terminal p-6 rounded-3xl space-y-4">
                    <h2 class="text-sm font-bold text-indigo-400 font-mono">🔰 SANDIKA OPERATIONAL RULES & CP SYSTEM</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-300 font-mono">
                        <div class="p-4 bg-black/50 border border-white/10 rounded-2xl space-y-2">
                            <h3 class="font-bold text-white text-sm">How to Earn CP (Contribution Points):</h3>
                            <ul class="space-y-1.5 text-gray-400">
                                <li>• <strong class="text-indigo-300">Nigma Riddles:</strong> +15 CP per riddle solved</li>
                                <li>• <strong class="text-indigo-300">Dictionary Words (EN/DE):</strong> +10 CP per entry</li>
                                <li>• <strong class="text-indigo-300">Stories (>1000 chars):</strong> +10-15 CP</li>
                                <li>• <strong class="text-indigo-300">Git Insights:</strong> +15 CP (GitHub verified) / +5 CP</li>
                                <li>• <strong class="text-indigo-300">Voice Audio Logs:</strong> +45 CP</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-black/50 border border-white/10 rounded-2xl space-y-2">
                            <h3 class="font-bold text-white text-sm">Agent Rank Hierarchy:</h3>
                            <ul class="space-y-1 text-gray-400">
                                <li>• Rookie: 0 - 19 CP</li>
                                <li>• Soldier: 20 - 49 CP</li>
                                <li>• <strong class="text-emerald-400">Captain (Verified Threshold): 50 - 99 CP</strong></li>
                                <li>• Sergeant: 100 - 149 CP</li>
                                <li>• Lieutenant: 150 - 399 CP</li>
                                <li>• Admiral: 400 - 1999 CP</li>
                                <li>• Bossman: 2000+ CP</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: STORIES HUB -->
            <div id="tab-stories" class="sandika-tab-content hidden space-y-4">
                <div class="arkham-terminal p-6 rounded-3xl space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-bold text-purple-400 font-mono">📖 STORIES & INTELLIGENCE REPORTS</h2>
                        <button onclick="document.getElementById('modal-story').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 text-white rounded-xl text-xs font-bold shadow-lg hover:bg-purple-500">
                            + POST STORY (+10 CP)
                        </button>
                    </div>

                    <div class="space-y-3">
                        @forelse($stories as $s)
                            <div class="p-4 bg-black/40 border border-white/10 rounded-2xl text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-bold text-white text-sm">{{ $s->title }}</h3>
                                    <span class="text-[10px] font-mono text-purple-400">+{{ $s->cp_awarded }} CP</span>
                                </div>
                                <p class="text-gray-300 leading-relaxed">{{ $s->content }}</p>
                            </div>
                        @empty
                            <div class="text-center p-6 text-xs text-gray-500 font-mono">No stories posted yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 3: DICTIONARY -->
            <div id="tab-dictionary" class="sandika-tab-content hidden space-y-4">
                <div class="arkham-terminal p-6 rounded-3xl space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-bold text-indigo-400 font-mono">📚 LEXICON DICTIONARY (ENGLISH & GERMAN)</h2>
                        <button onclick="document.getElementById('modal-dict').classList.remove('hidden')" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-lg hover:bg-indigo-500">
                            + ADD WORD (+10 CP)
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @forelse($dictionary as $d)
                            <div class="p-3 bg-black/40 border border-white/10 rounded-xl text-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-indigo-300">{{ $d->word }}</span>
                                    <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-400">{{ $d->language }}</span>
                                </div>
                                <p class="text-gray-400 text-[11px]">{{ $d->definition }}</p>
                            </div>
                        @empty
                            <div class="col-span-2 text-center p-6 text-xs text-gray-500 font-mono">Lexicon empty. Submit vocabulary to earn CP!</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 4: GIT INSIGHTS -->
            <div id="tab-git" class="sandika-tab-content hidden space-y-4">
                <div class="arkham-terminal p-6 rounded-3xl space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-bold text-emerald-400 font-mono">💻 REPOSITORY GIT INSIGHTS</h2>
                        <button onclick="document.getElementById('modal-git').classList.remove('hidden')" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-lg hover:bg-emerald-500">
                            + POST GIT INSIGHT (+15 CP)
                        </button>
                    </div>

                    <div class="space-y-3">
                        @forelse($gitInsights as $g)
                            <div class="p-4 bg-black/40 border border-white/10 rounded-2xl text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <a href="{{ $g->repo_url }}" target="_blank" class="font-bold text-emerald-400 hover:underline">{{ $g->repo_url }}</a>
                                    <span class="text-[10px] font-mono text-emerald-400">+{{ $g->cp_awarded }} CP</span>
                                </div>
                                <p class="text-gray-300">{{ $g->description }}</p>
                            </div>
                        @empty
                            <div class="text-center p-6 text-xs text-gray-500 font-mono">No git insights logged.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 5: TACTICAL TOOLS & ROT13 -->
            <div id="tab-tools" class="sandika-tab-content hidden space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- ROT13 Encoder/Decoder -->
                    <div class="arkham-terminal p-6 rounded-3xl space-y-3">
                        <h3 class="text-xs font-bold text-indigo-400 font-mono">🔒 ROT13 ENCODER / DECODER</h3>
                        <textarea id="rot13-input" rows="3" placeholder="Enter text to encrypt/decrypt..." class="w-full bg-black/50 border border-white/10 rounded-xl p-3 text-xs text-white placeholder-gray-600 focus:outline-none"></textarea>
                        <button id="btn-rot13-convert" class="w-full py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold">CONVERT TEXT</button>
                        <textarea id="rot13-output" rows="3" readonly placeholder="Output ciphertext..." class="w-full bg-black/60 border border-white/10 rounded-xl p-3 text-xs text-emerald-400 focus:outline-none font-mono"></textarea>
                    </div>

                    <!-- Voice Log & File Storage -->
                    <div class="arkham-terminal p-6 rounded-3xl space-y-3">
                        <h3 class="text-xs font-bold text-purple-400 font-mono">🎙️ VOICE LOG & FILE VAULT</h3>
                        <div id="voice-status" class="text-xs text-gray-400 min-h-[20px]"></div>
                        <button id="btn-analyze-voice" class="w-full py-2.5 bg-purple-600 text-white rounded-xl text-xs font-bold">ANALYZE VOICE LOG (+45 CP)</button>
                    </div>
                </div>
            </div>

        </main>

    </div>

    <!-- POST STORY MODAL -->
    <div id="modal-story" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="w-full max-w-lg bg-gray-900 border border-white/20 p-6 rounded-3xl space-y-4">
            <h3 class="text-base font-bold text-white font-mono">Post Sandika Story</h3>
            <form id="form-post-story" class="space-y-3">
                <input type="text" name="title" required placeholder="Story Title..." class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white">
                <textarea name="content" required rows="5" placeholder="Write story (Over 1000 chars earns +15 CP)..." class="w-full bg-black/50 border border-white/10 rounded-xl p-4 text-xs text-white"></textarea>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-story').classList.add('hidden')" class="px-4 py-2 bg-gray-800 text-gray-300 rounded-xl text-xs">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-xl text-xs font-bold">Post Story</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ADD DICTIONARY MODAL -->
    <div id="modal-dict" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="w-full max-w-lg bg-gray-900 border border-white/20 p-6 rounded-3xl space-y-4">
            <h3 class="text-base font-bold text-white font-mono">Add Word to Lexicon</h3>
            <form id="form-add-dict" class="space-y-3">
                <select name="language" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white">
                    <option value="en">English (EN)</option>
                    <option value="de">German (DE)</option>
                </select>
                <input type="text" name="word" required placeholder="Vocabulary Word..." class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white">
                <textarea name="definition" required rows="3" placeholder="Definition..." class="w-full bg-black/50 border border-white/10 rounded-xl p-4 text-xs text-white"></textarea>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-dict').classList.add('hidden')" class="px-4 py-2 bg-gray-800 text-gray-300 rounded-xl text-xs">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold">Add Word (+10 CP)</button>
                </div>
            </form>
        </div>
    </div>

    <!-- POST GIT MODAL -->
    <div id="modal-git" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="w-full max-w-lg bg-gray-900 border border-white/20 p-6 rounded-3xl space-y-4">
            <h3 class="text-base font-bold text-white font-mono">Post Git Repository Insight</h3>
            <form id="form-post-git" class="space-y-3">
                <input type="url" name="repo_url" required placeholder="https://github.com/username/repo..." class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white">
                <textarea name="description" required rows="3" placeholder="Description of repository insight..." class="w-full bg-black/50 border border-white/10 rounded-xl p-4 text-xs text-white"></textarea>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-git').classList.add('hidden')" class="px-4 py-2 bg-gray-800 text-gray-300 rounded-xl text-xs">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold">Post Insight (+15 CP)</button>
                </div>
            </form>
        </div>
    </div>

    <!-- External Sandika ESM Script -->
    <script type="module" src="{{ asset('js/sandika.js') }}"></script>
</body>
</html>
