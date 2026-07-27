<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Chat Portal - Parsa Besharat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind & App CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.tailwind = { config: { darkMode: 'class' } };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">

    <style>
        /* Chat Portal Custom Styling */
        .chat-glass {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .chat-glass.transparent-mode {
            background: rgba(0, 0, 0, 0.15) !important;
            backdrop-filter: blur(4px) !important;
            -webkit-backdrop-filter: blur(4px) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Themes */
        .theme-cyberpunk {
            background: linear-gradient(135deg, rgba(24, 9, 39, 0.9), rgba(12, 38, 59, 0.9)) !important;
            border-color: #ff007f !important;
        }

        .theme-emerald {
            background: linear-gradient(135deg, rgba(6, 78, 59, 0.9), rgba(4, 47, 46, 0.9)) !important;
            border-color: #10b981 !important;
        }

        .theme-sapphire {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.9), rgba(17, 24, 39, 0.9)) !important;
            border-color: #3b82f6 !important;
        }

        .theme-light {
            background: rgba(255, 255, 255, 0.88) !important;
            color: #1e293b !important;
            border-color: rgba(0, 0, 0, 0.1) !important;
        }

        .theme-light .text-white {
            color: #0f172a !important;
        }

        .theme-light .text-gray-300, .theme-light .text-gray-400 {
            color: #475569 !important;
        }

        /* Custom Scrollbar for Chat */
        .chat-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
        }

        /* Waveform Animation */
        @keyframes wave {
            0%, 100% { height: 4px; }
            50% { height: 16px; }
        }

        .wave-bar {
            animation: wave 1s infinite ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col md:flex-row antialiased selection:bg-blue-500 selection:text-white">

    <!-- Sidebar Navigation -->
    @include('sidebar')

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden p-3 md:p-6">
        
        <!-- Header Bar -->
        <header class="flex flex-wrap items-center justify-between gap-3 p-4 rounded-2xl chat-glass mb-4 shadow-xl">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <span class="w-3.5 h-3.5 bg-green-500 border-2 border-gray-900 rounded-full absolute bottom-0 right-0 animate-pulse"></span>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center font-bold text-lg shadow-md">
                        💬
                    </div>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-white">Online Chat Portal</h1>
                    <p class="text-xs text-gray-400 flex items-center gap-1.5">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        Live Room &bull; File Sharing (up to 4GB) &bull; Voice/Video Notes &bull; Calls
                    </p>
                </div>
            </div>

            <!-- Header Controls & Auth -->
            <div class="flex items-center space-x-2 flex-wrap">
                <!-- Audio Call Launcher -->
                <button onclick="startAudioCall()" class="px-3 py-1.5 rounded-xl bg-blue-600/80 hover:bg-blue-600 text-white text-xs font-semibold flex items-center space-x-1.5 shadow-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>Audio Call</span>
                </button>

                <!-- Settings & Customization Button -->
                <button onclick="toggleSettingsModal()" class="px-3 py-1.5 rounded-xl bg-gray-800/80 hover:bg-gray-700 text-gray-200 text-xs font-semibold flex items-center space-x-1 border border-white/10 transition">
                    ⚙️ <span>Theme & FX</span>
                </button>

                <!-- Auth Buttons / User Info -->
                @auth
                    <div class="flex items-center space-x-2 bg-white/5 border border-white/10 px-3 py-1 rounded-xl">
                        <span class="text-xs font-medium text-emerald-400">👤 {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-rose-400 hover:underline">Logout</button>
                        </form>
                    </div>
                @else
                    <button onclick="openAuthModal('login')" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-semibold transition border border-white/20">
                        Login
                    </button>
                    <button onclick="openAuthModal('register')" class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold shadow-md transition">
                        Sign Up
                    </button>
                @endauth
            </div>
        </header>

        <!-- User Identity Settings Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-2 rounded-xl chat-glass mb-3 text-xs">
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <label class="text-gray-400 font-medium">Your Name:</label>
                <input type="text" id="senderNameInput" value="{{ $defaultName }}" class="bg-black/30 border border-white/20 rounded-lg px-2.5 py-1 text-white text-xs focus:outline-none focus:border-blue-500 w-36">
                <label class="text-gray-400 font-medium">Username:</label>
                <input type="text" id="usernameInput" value="{{ $defaultUsername }}" class="bg-black/30 border border-white/20 rounded-lg px-2.5 py-1 text-white text-xs focus:outline-none focus:border-blue-500 w-32">
            </div>
            
            <div class="flex items-center space-x-3 text-gray-300">
                <label class="flex items-center space-x-1.5 cursor-pointer">
                    <input type="checkbox" id="transparentModeToggle" onchange="toggleTransparentMode()" class="rounded border-gray-600 bg-gray-800 text-blue-600">
                    <span class="font-medium text-xs">Transparent Mode</span>
                </label>
                <span id="scheduledBadge" class="hidden px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[11px]">
                    ⏱ 0 Scheduled
                </span>
            </div>
        </div>

        <!-- Main Chat Box Container -->
        <div id="chatBoxContainer" class="flex-1 rounded-2xl chat-glass flex flex-col overflow-hidden shadow-2xl transition-all duration-300">
            
            <!-- Message Stream Area -->
            <div id="messageStream" class="flex-1 p-4 overflow-y-auto space-y-3 chat-scroll">
                <div id="loadingIndicator" class="text-center py-10 text-gray-400 text-xs flex items-center justify-center space-x-2">
                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Loading messages...</span>
                </div>
            </div>

            <!-- Voice Recording Status Bar (Hidden by Default) -->
            <div id="voiceRecorderBar" class="hidden px-4 py-2 bg-rose-950/80 border-t border-rose-500/30 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="w-3 h-3 rounded-full bg-rose-500 animate-ping"></span>
                    <span class="text-xs font-semibold text-rose-300">Recording Voice Note... <span id="voiceTimer">00:00</span></span>
                    <div class="flex items-center space-x-1">
                        <div class="w-1 bg-rose-400 wave-bar h-2"></div>
                        <div class="w-1 bg-rose-400 wave-bar h-4" style="animation-delay: 0.2s"></div>
                        <div class="w-1 bg-rose-400 wave-bar h-3" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="cancelVoiceRecording()" class="text-xs px-2.5 py-1 bg-gray-800 hover:bg-gray-700 rounded-lg text-gray-300">Cancel</button>
                    <button onclick="stopAndSendVoiceRecording()" class="text-xs px-3 py-1 bg-rose-600 hover:bg-rose-500 rounded-lg font-semibold text-white">Send Voice</button>
                </div>
            </div>

            <!-- Upload Progress Bar (Hidden by Default) -->
            <div id="uploadProgressBarContainer" class="hidden px-4 py-2 bg-blue-950/60 border-t border-blue-500/30">
                <div class="flex items-center justify-between text-xs text-blue-300 mb-1">
                    <span id="uploadStatusText">Uploading attachment...</span>
                    <span id="uploadPercentText">0%</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-1.5 overflow-hidden">
                    <div id="uploadProgressBar" class="bg-blue-500 h-1.5 rounded-full w-0 transition-all duration-200"></div>
                </div>
            </div>

            <!-- Chat Input Footer -->
            <div class="p-3 bg-black/40 border-t border-white/10 flex flex-col space-y-2">
                
                <!-- Quick Tools Bar (Emojis, GIF, File, Schedule, Voice, Video) -->
                <div class="flex items-center justify-between px-1 text-xs">
                    <div class="flex items-center space-x-1 sm:space-x-2">
                        <!-- Emoji Trigger -->
                        <button type="button" onclick="toggleEmojiPicker()" class="p-1.5 hover:bg-white/10 rounded-lg text-gray-300 hover:text-amber-400 transition" title="Add Emojis">
                            😊
                        </button>
                        <!-- GIF Picker Trigger -->
                        <button type="button" onclick="toggleGifPicker()" class="p-1.5 hover:bg-white/10 rounded-lg text-gray-300 hover:text-purple-400 font-bold transition" title="Attach GIF">
                            GIF
                        </button>
                        <!-- File / Large File Attachment -->
                        <label class="p-1.5 hover:bg-white/10 rounded-lg text-gray-300 hover:text-blue-400 cursor-pointer transition" title="Attach File (Up to 4GB)">
                            📎
                            <input type="file" id="fileAttachmentInput" onchange="handleFileSelect(event)" class="hidden">
                        </label>
                        <!-- Voice Recording Button -->
                        <button type="button" onclick="startVoiceRecording()" class="p-1.5 hover:bg-white/10 rounded-lg text-gray-300 hover:text-rose-400 transition" title="Record Voice Note">
                            🎙️
                        </button>
                        <!-- Video Recording Note Button -->
                        <button type="button" onclick="openVideoNoteModal()" class="p-1.5 hover:bg-white/10 rounded-lg text-gray-300 hover:text-emerald-400 transition" title="Record Video Note">
                            📹
                        </button>
                        <!-- Schedule Message Button -->
                        <button type="button" onclick="toggleScheduleModal()" class="p-1.5 hover:bg-white/10 rounded-lg text-gray-300 hover:text-amber-300 transition" title="Schedule Message">
                            ⏱️
                        </button>
                    </div>

                    <div id="scheduledNotice" class="hidden text-amber-300 font-mono text-[11px] flex items-center gap-1">
                        <span>Scheduled:</span>
                        <span id="scheduledTimeLabel"></span>
                        <button onclick="clearSchedule()" class="text-rose-400 font-bold">×</button>
                    </div>
                </div>

                <!-- Textarea and Send Button -->
                <div class="flex items-center space-x-2">
                    <textarea id="chatInput" rows="1" placeholder="Type a message, drop files (up to 4GB), or record notes..."
                        onkeydown="handleKeyPress(event)"
                        class="flex-1 bg-white/5 border border-white/15 rounded-xl px-3 py-2 text-white placeholder-gray-400 text-sm focus:outline-none focus:border-blue-500 resize-none chat-scroll"></textarea>
                    
                    <button onclick="dispatchMessage()" class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold rounded-xl text-xs shadow-lg flex items-center space-x-1.5 transition transform active:scale-95">
                        <span>Send</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- EMOJI PICKER POPUP -->
    <div id="emojiPicker" class="hidden fixed bottom-24 left-10 md:left-72 z-50 p-3 bg-gray-900 border border-white/20 rounded-2xl shadow-2xl w-64 max-h-48 overflow-y-auto chat-scroll grid grid-cols-6 gap-2 text-xl">
        <button onclick="addEmoji('😊')">😊</button><button onclick="addEmoji('😂')">😂</button>
        <button onclick="addEmoji('🔥')">🔥</button><button onclick="addEmoji('❤️')">❤️</button>
        <button onclick="addEmoji('👍')">👍</button><button onclick="addEmoji('🎉')">🎉</button>
        <button onclick="addEmoji('🚀')">🚀</button><button onclick="addEmoji('💡')">💡</button>
        <button onclick="addEmoji('😎')">😎</button><button onclick="addEmoji('🙏')">🙏</button>
        <button onclick="addEmoji('💯')">💯</button><button onclick="addEmoji('✨')">✨</button>
        <button onclick="addEmoji('🤝')">🤝</button><button onclick="addEmoji('🤖')">🤖</button>
        <button onclick="addEmoji('🎧')">🎧</button><button onclick="addEmoji('💻')">💻</button>
    </div>

    <!-- GIF PICKER POPUP -->
    <div id="gifPicker" class="hidden fixed bottom-24 left-10 md:left-72 z-50 p-4 bg-gray-900 border border-white/20 rounded-2xl shadow-2xl w-72">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-300">Choose GIF</span>
            <button onclick="toggleGifPicker()" class="text-gray-400 hover:text-white text-xs">✕</button>
        </div>
        <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto chat-scroll">
            <img src="https://media.giphy.com/media/26bgQ8u0e1bBf90aY/giphy.gif" onclick="sendGif('https://media.giphy.com/media/26bgQ8u0e1bBf90aY/giphy.gif')" class="w-full h-20 object-cover rounded-lg cursor-pointer hover:opacity-80">
            <img src="https://media.giphy.com/media/l0HlHFRbmaZtBRhXG/giphy.gif" onclick="sendGif('https://media.giphy.com/media/l0HlHFRbmaZtBRhXG/giphy.gif')" class="w-full h-20 object-cover rounded-lg cursor-pointer hover:opacity-80">
            <img src="https://media.giphy.com/media/3o7TKSjRrfIPjeiVyM/giphy.gif" onclick="sendGif('https://media.giphy.com/media/3o7TKSjRrfIPjeiVyM/giphy.gif')" class="w-full h-20 object-cover rounded-lg cursor-pointer hover:opacity-80">
            <img src="https://media.giphy.com/media/l3vR1v8L6n3zW/giphy.gif" onclick="sendGif('https://media.giphy.com/media/l3vR1v8L6n3zW/giphy.gif')" class="w-full h-20 object-cover rounded-lg cursor-pointer hover:opacity-80">
        </div>
    </div>

    <!-- SCHEDULE MODAL -->
    <div id="scheduleModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-5 rounded-2xl w-full max-w-sm shadow-2xl">
            <h3 class="text-sm font-bold text-white mb-2">⏱️ Schedule Message Delivery</h3>
            <p class="text-xs text-gray-400 mb-3">Choose a date and time for your message to be released:</p>
            <input type="datetime-local" id="scheduleDateTime" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white text-xs mb-4 focus:outline-none focus:border-amber-400">
            <div class="flex justify-end space-x-2">
                <button onclick="toggleScheduleModal()" class="px-3 py-1.5 bg-gray-800 text-xs rounded-xl text-gray-300">Cancel</button>
                <button onclick="applySchedule()" class="px-4 py-1.5 bg-amber-600 hover:bg-amber-500 text-xs font-semibold text-white rounded-xl">Set Schedule</button>
            </div>
        </div>
    </div>

    <!-- VIDEO NOTE RECORDING MODAL -->
    <div id="videoNoteModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-5 rounded-2xl w-full max-w-md shadow-2xl text-center">
            <h3 class="text-sm font-bold text-white mb-3">📹 Record Video Note</h3>
            <div class="relative mb-3 bg-black rounded-xl overflow-hidden aspect-video flex items-center justify-center">
                <video id="videoPreview" autoplay muted class="w-full h-full object-cover"></video>
                <span id="videoRecordingBadge" class="hidden absolute top-3 left-3 px-2 py-0.5 bg-rose-600 text-white text-[11px] rounded-full font-bold animate-pulse">REC</span>
            </div>
            <div class="flex items-center justify-center space-x-3">
                <button id="recordVideoBtn" onclick="startVideoRecording()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-xs font-semibold rounded-xl text-white">Start Recording</button>
                <button id="stopVideoBtn" onclick="stopVideoRecording()" class="hidden px-4 py-2 bg-rose-600 hover:bg-rose-500 text-xs font-semibold rounded-xl text-white">Stop & Send</button>
                <button onclick="closeVideoNoteModal()" class="px-3 py-2 bg-gray-800 text-xs rounded-xl text-gray-300">Close</button>
            </div>
        </div>
    </div>

    <!-- SETTINGS & CUSTOMIZATION MODAL -->
    <div id="settingsModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-5 rounded-2xl w-full max-w-md shadow-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-white">⚙️ Chat Customization & Themes</h3>
                <button onclick="toggleSettingsModal()" class="text-gray-400 hover:text-white text-xs">✕</button>
            </div>

            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-medium text-gray-300 mb-1.5">Preset Theme:</label>
                    <select id="themeSelect" onchange="changeTheme(this.value)" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                        <option value="sapphire">Deep Sapphire (Default)</option>
                        <option value="cyberpunk">Cyberpunk Neon</option>
                        <option value="emerald">Emerald Mint</option>
                        <option value="light">Light Pearl</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-300 mb-1.5">Chat Background Wallpaper:</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button onclick="setChatBg('default')" class="p-2 bg-gray-800 border border-white/10 rounded-lg hover:border-blue-500 text-gray-300">Default</button>
                        <button onclick="setChatBg('dark.jpg')" class="p-2 bg-gray-800 border border-white/10 rounded-lg hover:border-blue-500 text-gray-300">Dark WP</button>
                        <button onclick="setChatBg('light.jpg')" class="p-2 bg-gray-800 border border-white/10 rounded-lg hover:border-blue-500 text-gray-300">Light WP</button>
                    </div>
                </div>
            </div>

            <div class="mt-5 flex justify-end">
                <button onclick="toggleSettingsModal()" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-semibold">Done</button>
            </div>
        </div>
    </div>

    <!-- AUDIO CALL MODAL -->
    <div id="audioCallModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-6 rounded-2xl w-full max-w-xs shadow-2xl text-center">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-3xl shadow-xl animate-bounce">
                📞
            </div>
            <h3 class="text-base font-bold text-white">Audio Call</h3>
            <p id="callStatus" class="text-xs text-emerald-400 my-2 animate-pulse">Connecting Peer-to-Peer...</p>
            <div class="flex items-center justify-center space-x-4 mt-6">
                <button onclick="toggleMuteCall()" id="muteCallBtn" class="p-3 bg-gray-800 hover:bg-gray-700 rounded-full text-white">🎙️</button>
                <button onclick="endAudioCall()" class="p-3 bg-rose-600 hover:bg-rose-500 rounded-full text-white font-bold">📵</button>
            </div>
        </div>
    </div>

    <!-- AUTH MODAL (LOGIN / REGISTER) -->
    <div id="authModal" class="hidden fixed inset-0 bg-black/75 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-6 rounded-2xl w-full max-w-sm shadow-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 id="authModalTitle" class="text-base font-bold text-white">Account Login</h3>
                <button onclick="closeAuthModal()" class="text-gray-400 hover:text-white text-xs">✕</button>
            </div>

            <!-- Login Form -->
            <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block text-gray-400 mb-1">Email Address</label>
                    <input type="email" name="email" required class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-gray-400 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                </div>
                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition">Log In</button>
            </form>

            <!-- Register Form -->
            <form id="registerForm" method="POST" action="{{ route('register') }}" class="hidden space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block text-gray-400 mb-1">Full Name</label>
                    <input type="text" name="name" required class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-gray-400 mb-1">Email Address</label>
                    <input type="email" name="email" required class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-gray-400 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-gray-400 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                </div>
                <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl transition">Create Account</button>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT APP LOGIC -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let selectedScheduledTime = null;
        let mediaRecorder = null;
        let audioChunks = [];
        let videoRecorder = null;
        let videoChunks = [];
        let videoStream = null;
        let audioCallOscillator = null;

        // Initialize & Auto Poll
        document.addEventListener('DOMContentLoaded', () => {
            fetchMessages();
            setInterval(fetchMessages, 2000);
        });

        // Fetch Messages from Backend API
        async function fetchMessages() {
            try {
                const response = await fetch('{{ route("chat.messages") }}');
                const data = await response.json();

                if (data.status === 'success') {
                    const stream = document.getElementById('messageStream');
                    
                    if (data.messages.length === 0) {
                        stream.innerHTML = '<div class="text-center py-10 text-gray-400 text-xs">No messages yet. Be the first to start chatting!</div>';
                    } else {
                        stream.innerHTML = data.messages.map(msg => renderMessageBubble(msg)).join('');
                    }

                    // Scheduled count badge
                    const scheduledBadge = document.getElementById('scheduledBadge');
                    if (data.scheduled_count > 0) {
                        scheduledBadge.innerText = `⏱ ${data.scheduled_count} Scheduled`;
                        scheduledBadge.classList.remove('hidden');
                    } else {
                        scheduledBadge.classList.add('hidden');
                    }
                }
            } catch (err) {
                console.error('Fetch error:', err);
            }
        }

        // Render Message Bubble
        function renderMessageBubble(msg) {
            let contentHtml = '';

            if (msg.type === 'text') {
                contentHtml = `<p class="whitespace-pre-wrap">${escapeHtml(msg.message || '')}</p>`;
            } else if (msg.type === 'image' || (msg.mime_type && msg.mime_type.startsWith('image/'))) {
                contentHtml = `<img src="${msg.file_url}" class="max-w-xs max-h-60 rounded-xl border border-white/10 cursor-pointer hover:opacity-90" onclick="window.open('${msg.file_url}', '_blank')">`;
            } else if (msg.type === 'gif') {
                contentHtml = `<img src="${msg.file_url}" class="max-w-xs max-h-48 rounded-xl border border-white/10">`;
            } else if (msg.type === 'voice') {
                contentHtml = `
                    <div class="flex items-center space-x-2">
                        <span class="text-lg">🎙️</span>
                        <audio controls src="${msg.file_url}" class="h-8 w-48 sm:w-60"></audio>
                    </div>`;
            } else if (msg.type === 'video') {
                contentHtml = `
                    <video controls src="${msg.file_url}" class="max-w-xs max-h-60 rounded-xl border border-white/10"></video>`;
            } else if (msg.type === 'file') {
                contentHtml = `
                    <div class="flex items-center space-x-3 p-2 bg-black/20 rounded-xl border border-white/10">
                        <span class="text-2xl">📄</span>
                        <div class="overflow-hidden">
                            <p class="font-semibold text-xs truncate max-w-[180px]">${escapeHtml(msg.file_name || 'Attachment')}</p>
                            <p class="text-[10px] text-gray-400">${msg.file_size || ''}</p>
                        </div>
                        <a href="${msg.file_url}" download class="px-2 py-1 bg-blue-600 hover:bg-blue-500 rounded-lg text-[10px] font-bold text-white">Download</a>
                    </div>`;
            }

            const alignClass = msg.is_me ? 'justify-end' : 'justify-start';
            const bgClass = msg.is_me 
                ? 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white rounded-br-none' 
                : 'bg-white/10 text-gray-100 rounded-bl-none border border-white/10';

            return `
                <div class="flex ${alignClass} mb-2">
                    <div class="max-w-[85%] sm:max-w-md p-3 rounded-2xl ${bgClass} shadow-md text-xs">
                        <div class="flex items-center justify-between text-[10px] opacity-75 mb-1 space-x-3">
                            <span class="font-bold">${escapeHtml(msg.sender_name)} (@${escapeHtml(msg.username)})</span>
                            <span>${msg.created_at}</span>
                        </div>
                        ${contentHtml}
                    </div>
                </div>`;
        }

        // Send Text Message
        async function dispatchMessage() {
            const input = document.getElementById('chatInput');
            const text = input.value.trim();
            if (!text && !selectedScheduledTime) return;

            const senderName = document.getElementById('senderNameInput').value.trim() || 'Guest';
            const username = document.getElementById('usernameInput').value.trim() || 'guest';

            const payload = {
                sender_name: senderName,
                username: username,
                message: text,
                type: 'text',
                scheduled_at: selectedScheduledTime
            };

            input.value = '';
            clearSchedule();

            try {
                await fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });
                fetchMessages();
            } catch (err) {
                console.error(err);
            }
        }

        // File Attachment Upload (Up to 4GB support configuration)
        async function handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            const senderName = document.getElementById('senderNameInput').value.trim() || 'Guest';
            const username = document.getElementById('usernameInput').value.trim() || 'guest';

            let type = 'file';
            if (file.type.startsWith('image/')) type = 'image';

            const formData = new FormData();
            formData.append('file', file);
            formData.append('sender_name', senderName);
            formData.append('username', username);
            formData.append('type', type);
            if (selectedScheduledTime) formData.append('scheduled_at', selectedScheduledTime);

            showUploadProgress(true, 10);

            try {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route("chat.upload") }}', true);
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

                xhr.upload.onprogress = function (e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        showUploadProgress(true, percent);
                    }
                };

                xhr.onload = function () {
                    showUploadProgress(false, 100);
                    clearSchedule();
                    fetchMessages();
                };

                xhr.send(formData);
            } catch (err) {
                console.error(err);
                showUploadProgress(false, 0);
            }
        }

        // Voice Recording (MediaRecorder API)
        async function startVoiceRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.ondataavailable = event => audioChunks.push(event.data);
                mediaRecorder.start();

                document.getElementById('voiceRecorderBar').classList.remove('hidden');
            } catch (err) {
                alert('Microphone access required to record voice notes.');
            }
        }

        function stopAndSendVoiceRecording() {
            if (!mediaRecorder) return;

            mediaRecorder.onstop = async () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                const file = new File([audioBlob], 'voicenote.webm', { type: 'audio/webm' });

                const senderName = document.getElementById('senderNameInput').value.trim() || 'Guest';
                const username = document.getElementById('usernameInput').value.trim() || 'guest';

                const formData = new FormData();
                formData.append('file', file);
                formData.append('sender_name', senderName);
                formData.append('username', username);
                formData.append('type', 'voice');

                await fetch('{{ route("chat.upload") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });

                document.getElementById('voiceRecorderBar').classList.add('hidden');
                fetchMessages();
            };

            mediaRecorder.stop();
        }

        function cancelVoiceRecording() {
            if (mediaRecorder) mediaRecorder.stop();
            document.getElementById('voiceRecorderBar').classList.add('hidden');
        }

        // Video Note Modal & Recording
        async function openVideoNoteModal() {
            document.getElementById('videoNoteModal').classList.remove('hidden');
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                document.getElementById('videoPreview').srcObject = videoStream;
            } catch (err) {
                alert('Camera & Microphone permissions required for video notes.');
            }
        }

        function closeVideoNoteModal() {
            if (videoStream) videoStream.getTracks().forEach(track => track.stop());
            document.getElementById('videoNoteModal').classList.add('hidden');
        }

        function startVideoRecording() {
            if (!videoStream) return;
            videoRecorder = new MediaRecorder(videoStream);
            videoChunks = [];

            videoRecorder.ondataavailable = e => videoChunks.push(e.data);
            videoRecorder.start();

            document.getElementById('recordVideoBtn').classList.add('hidden');
            document.getElementById('stopVideoBtn').classList.remove('hidden');
            document.getElementById('videoRecordingBadge').classList.remove('hidden');
        }

        function stopVideoRecording() {
            if (!videoRecorder) return;

            videoRecorder.onstop = async () => {
                const videoBlob = new Blob(videoChunks, { type: 'video/webm' });
                const file = new File([videoBlob], 'videonote.webm', { type: 'video/webm' });

                const senderName = document.getElementById('senderNameInput').value.trim() || 'Guest';
                const username = document.getElementById('usernameInput').value.trim() || 'guest';

                const formData = new FormData();
                formData.append('file', file);
                formData.append('sender_name', senderName);
                formData.append('username', username);
                formData.append('type', 'video');

                await fetch('{{ route("chat.upload") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });

                closeVideoNoteModal();
                fetchMessages();
            };

            videoRecorder.stop();
        }

        // Send GIF
        async function sendGif(url) {
            toggleGifPicker();
            const senderName = document.getElementById('senderNameInput').value.trim() || 'Guest';
            const username = document.getElementById('usernameInput').value.trim() || 'guest';

            await fetch('{{ route("chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    sender_name: senderName,
                    username: username,
                    type: 'gif',
                    file_url: url
                })
            });
            fetchMessages();
        }

        // WebRTC / Audio Call Simulation
        function startAudioCall() {
            document.getElementById('audioCallModal').classList.remove('hidden');
            document.getElementById('callStatus').innerText = 'Connected • Call Active';
        }

        function endAudioCall() {
            document.getElementById('audioCallModal').classList.add('hidden');
        }

        function toggleMuteCall() {
            const btn = document.getElementById('muteCallBtn');
            btn.innerText = btn.innerText === '🎙️' ? '🔇' : '🎙️';
        }

        // Utilities & Toggles
        function toggleTransparentMode() {
            const container = document.getElementById('chatBoxContainer');
            container.classList.toggle('transparent-mode');
        }

        function changeTheme(theme) {
            const container = document.getElementById('chatBoxContainer');
            container.classList.remove('theme-cyberpunk', 'theme-emerald', 'theme-sapphire', 'theme-light');
            if (theme !== 'sapphire') container.classList.add(`theme-${theme}`);
        }

        function addEmoji(emoji) {
            const input = document.getElementById('chatInput');
            input.value += emoji;
            toggleEmojiPicker();
        }

        function toggleEmojiPicker() { document.getElementById('emojiPicker').classList.toggle('hidden'); }
        function toggleGifPicker() { document.getElementById('gifPicker').classList.toggle('hidden'); }
        function toggleSettingsModal() { document.getElementById('settingsModal').classList.toggle('hidden'); }
        function toggleScheduleModal() { document.getElementById('scheduleModal').classList.toggle('hidden'); }

        function applySchedule() {
            const val = document.getElementById('scheduleDateTime').value;
            if (val) {
                selectedScheduledTime = val;
                document.getElementById('scheduledNotice').classList.remove('hidden');
                document.getElementById('scheduledTimeLabel').innerText = val;
            }
            toggleScheduleModal();
        }

        function clearSchedule() {
            selectedScheduledTime = null;
            document.getElementById('scheduledNotice').classList.add('hidden');
        }

        function openAuthModal(mode) {
            document.getElementById('authModal').classList.remove('hidden');
            if (mode === 'login') {
                document.getElementById('authModalTitle').innerText = 'Account Login';
                document.getElementById('loginForm').classList.remove('hidden');
                document.getElementById('registerForm').classList.add('hidden');
            } else {
                document.getElementById('authModalTitle').innerText = 'Create Account';
                document.getElementById('loginForm').classList.add('hidden');
                document.getElementById('registerForm').classList.remove('hidden');
            }
        }

        function closeAuthModal() { document.getElementById('authModal').classList.add('hidden'); }

        function showUploadProgress(show, percent) {
            const container = document.getElementById('uploadProgressBarContainer');
            if (show) {
                container.classList.remove('hidden');
                document.getElementById('uploadProgressBar').style.width = percent + '%';
                document.getElementById('uploadPercentText').innerText = percent + '%';
            } else {
                container.classList.add('hidden');
            }
        }

        function handleKeyPress(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                dispatchMessage();
            }
        }

        function escapeHtml(text) {
            return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
    </script>
</body>
</html>
