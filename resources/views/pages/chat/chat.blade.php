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
        /* Glassmorphic Centered Template */
        .portal-glass {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .portal-glass.transparent-mode {
            background: rgba(0, 0, 0, 0.25) !important;
            backdrop-filter: blur(6px) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        /* Themes */
        .theme-cyberpunk { background: linear-gradient(135deg, rgba(24, 9, 39, 0.92), rgba(12, 38, 59, 0.92)) !important; border-color: #ff007f !important; }
        .theme-emerald { background: linear-gradient(135deg, rgba(6, 78, 59, 0.92), rgba(4, 47, 46, 0.92)) !important; border-color: #10b981 !important; }
        .theme-sunset { background: linear-gradient(135deg, rgba(136, 19, 55, 0.92), rgba(67, 56, 202, 0.92)) !important; border-color: #f43f5e !important; }
        .theme-light { background: rgba(255, 255, 255, 0.92) !important; color: #0f172a !important; border-color: rgba(0, 0, 0, 0.1) !important; }
        .theme-light .text-white { color: #0f172a !important; }
        .theme-light .text-gray-300, .theme-light .text-gray-400 { color: #334155 !important; }

        .chat-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .chat-scroll::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 9999px; }

        /* Notification Toast Slide Animation */
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .toast-animate { animation: slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col md:flex-row antialiased selection:bg-blue-500 selection:text-white">

    <!-- Sidebar Navigation -->
    @include('sidebar')

    <!-- Main Centered Layout Container -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden p-2 sm:p-4 lg:p-6 justify-center">
        
        <div class="w-full max-w-6xl mx-auto h-full max-h-[92vh] flex flex-col portal-glass rounded-3xl shadow-2xl overflow-hidden border border-white/10 relative">

            @if (!$authenticated)
                <!-- AUTHENTICATION GATE (NO GUEST MODE) -->
                <div class="flex-1 flex flex-col items-center justify-center p-6 text-center">
                    <div class="w-20 h-20 mb-4 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-3xl shadow-2xl">
                        🔒
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-1">Members Only Chat Portal</h2>
                    <p class="text-xs text-gray-400 max-w-sm mb-6">Guest access is disabled. Please log in or create an account with 2FA protection to access the chat portal.</p>

                    <div class="w-full max-w-md bg-black/40 p-6 rounded-2xl border border-white/10 shadow-xl">
                        <!-- Toggle Buttons -->
                        <div class="flex rounded-xl bg-white/5 p-1 mb-5 text-xs font-semibold">
                            <button id="gateLoginTab" onclick="switchGateTab('login')" class="flex-1 py-2 rounded-lg bg-blue-600 text-white transition">Log In</button>
                            <button id="gateRegisterTab" onclick="switchGateTab('register')" class="flex-1 py-2 rounded-lg text-gray-400 hover:text-white transition">Sign Up</button>
                        </div>

                        <!-- Login Form -->
                        <form id="gateLoginForm" method="POST" action="{{ route('login') }}" class="space-y-3 text-left text-xs">
                            @csrf
                            <div>
                                <label class="block text-gray-400 mb-1">Email Address</label>
                                <input type="email" name="email" required class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-400 mb-1">Password</label>
                                <input type="password" name="password" required class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-blue-500">
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg transition active:scale-95">
                                Log In to Chat
                            </button>
                        </form>

                        <!-- Register Form -->
                        <form id="gateRegisterForm" method="POST" action="{{ route('register') }}" class="hidden space-y-3 text-left text-xs">
                            @csrf
                            <div>
                                <label class="block text-gray-400 mb-1">Full Name</label>
                                <input type="text" name="name" required class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-400 mb-1">Email Address</label>
                                <input type="email" name="email" required class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-400 mb-1">Password</label>
                                <input type="password" name="password" required class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-400 mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" required class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-blue-500">
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-lg transition active:scale-95">
                                Create Account (with 2FA)
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- AUTHENTICATED CHAT PORTAL INTERFACE -->

                <!-- Header Bar -->
                <header class="flex items-center justify-between px-4 py-3 bg-black/40 border-b border-white/10 shrink-0">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $user->avatar ? asset($user->avatar) : asset('images/profile.jpg') }}" class="w-10 h-10 rounded-full border border-white/20 object-cover">
                        <div>
                            <h1 class="text-sm font-bold text-white flex items-center gap-1.5">
                                <span>{{ trim($user->first_name . ' ' . $user->last_name) ?: $user->name }}</span>
                                @if($user->username)<span class="text-xs font-normal text-gray-400">(@ {{ $user->username }})</span>@endif
                            </h1>
                            <p class="text-[11px] text-emerald-400 flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Online &bull; Chat Active
                            </p>
                        </div>
                    </div>

                    <!-- Header Controls & Neatly Grouped Buttons -->
                    <div class="flex items-center space-x-2">
                        <button onclick="startAudioCall()" class="px-3 py-1.5 rounded-full bg-blue-600/80 hover:bg-blue-600 text-white text-xs font-semibold flex items-center space-x-1.5 shadow-md transition">
                            📞 <span>Audio Call</span>
                        </button>
                        <button onclick="toggleContactsSidebar()" class="px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-gray-200 text-xs font-semibold flex items-center space-x-1 transition">
                            👥 <span>Contacts</span>
                        </button>
                        <button onclick="toggleProfileModal()" class="px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-gray-200 text-xs font-semibold flex items-center space-x-1 transition">
                            👤 <span>Profile</span>
                        </button>
                        <button onclick="toggleSettingsModal()" class="px-3 py-1.5 rounded-full bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs font-semibold flex items-center space-x-1 border border-white/10 transition">
                            ⚙️ <span>FX & Themes</span>
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-full bg-rose-950/80 hover:bg-rose-900 text-rose-300 text-xs font-semibold border border-rose-500/30 transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </header>

                <!-- 24-HOUR STORIES BAR (INSTAGRAM/TELEGRAM STYLE) -->
                <div class="px-4 py-2 bg-black/20 border-b border-white/5 flex items-center space-x-3 overflow-x-auto chat-scroll shrink-0">
                    <!-- Add Story Button -->
                    <button onclick="toggleAddStoryModal()" class="flex flex-col items-center space-y-1 shrink-0 group">
                        <div class="w-11 h-11 rounded-full border-2 border-dashed border-blue-500 flex items-center justify-center bg-blue-600/20 group-hover:scale-105 transition">
                            <span class="text-lg font-bold text-blue-400">+</span>
                        </div>
                        <span class="text-[10px] text-gray-300 font-medium">Add Story</span>
                    </button>

                    <!-- Active Stories List -->
                    <div id="storiesContainer" class="flex items-center space-x-3 shrink-0">
                        <!-- JS injects user story avatars here -->
                    </div>
                </div>

                <!-- MAIN CHAT BODY (MESSAGES + CONTACTS SIDEBAR) -->
                <div id="chatBoxContainer" class="flex-1 flex overflow-hidden relative">
                    
                    <!-- Messages Area -->
                    <div class="flex-1 flex flex-col overflow-hidden">
                        
                        <!-- Message Stream -->
                        <div id="messageStream" class="flex-1 p-4 overflow-y-auto space-y-3 chat-scroll">
                            <div id="loadingIndicator" class="text-center py-12 text-gray-400 text-xs flex items-center justify-center space-x-2">
                                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Loading messages...</span>
                            </div>
                        </div>

                        <!-- Voice Recorder Status Bar -->
                        <div id="voiceRecorderBar" class="hidden px-4 py-2 bg-rose-950/90 border-t border-rose-500/30 flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-3">
                                <span class="w-3 h-3 rounded-full bg-rose-500 animate-ping"></span>
                                <span class="font-semibold text-rose-300">Recording Voice Note...</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="cancelVoiceRecording()" class="px-2.5 py-1 bg-gray-800 rounded-lg text-gray-300">Cancel</button>
                                <button onclick="stopAndSendVoiceRecording()" class="px-3 py-1 bg-rose-600 font-semibold rounded-lg text-white">Send Voice</button>
                            </div>
                        </div>

                        <!-- Upload Progress Bar -->
                        <div id="uploadProgressBarContainer" class="hidden px-4 py-2 bg-blue-950/80 border-t border-blue-500/30">
                            <div class="flex items-center justify-between text-xs text-blue-300 mb-1">
                                <span id="uploadStatusText">Uploading file (up to 4GB)...</span>
                                <span id="uploadPercentText">0%</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-1.5 overflow-hidden">
                                <div id="uploadProgressBar" class="bg-blue-500 h-1.5 rounded-full w-0 transition-all duration-200"></div>
                            </div>
                        </div>

                        <!-- Footer Input Bar -->
                        <div class="p-3 bg-black/40 border-t border-white/10 flex flex-col space-y-2 shrink-0">
                            <div class="flex items-center justify-between px-1 text-xs">
                                <div class="flex items-center space-x-2">
                                    <button type="button" onclick="toggleEmojiPicker()" class="p-1.5 hover:bg-white/10 rounded-full text-gray-300 hover:text-amber-400 transition" title="Add Emojis">😊</button>
                                    <button type="button" onclick="toggleGifPicker()" class="p-1.5 hover:bg-white/10 rounded-full text-gray-300 hover:text-purple-400 font-bold transition" title="Attach GIF">GIF</button>
                                    <label class="p-1.5 hover:bg-white/10 rounded-full text-gray-300 hover:text-blue-400 cursor-pointer transition" title="Attach File (Up to 4GB)">
                                        📎
                                        <input type="file" id="fileAttachmentInput" onchange="handleFileSelect(event)" class="hidden">
                                    </label>
                                    <button type="button" onclick="startVoiceRecording()" class="p-1.5 hover:bg-white/10 rounded-full text-gray-300 hover:text-rose-400 transition" title="Record Voice Note">🎙️</button>
                                    <button type="button" onclick="openVideoNoteModal()" class="p-1.5 hover:bg-white/10 rounded-full text-gray-300 hover:text-emerald-400 transition" title="Record Video Note">📹</button>
                                    <button type="button" onclick="toggleScheduleModal()" class="p-1.5 hover:bg-white/10 rounded-full text-gray-300 hover:text-amber-300 transition" title="Schedule Message">⏱️</button>
                                </div>

                                <div id="scheduledNotice" class="hidden text-amber-300 font-mono text-[11px] flex items-center gap-1">
                                    <span>Scheduled:</span><span id="scheduledTimeLabel"></span>
                                    <button onclick="clearSchedule()" class="text-rose-400 font-bold">×</button>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <textarea id="chatInput" rows="1" placeholder="Type a message..."
                                    onkeydown="handleKeyPress(event)"
                                    class="flex-1 bg-white/5 border border-white/15 rounded-2xl px-4 py-2.5 text-white placeholder-gray-400 text-sm focus:outline-none focus:border-blue-500 resize-none chat-scroll"></textarea>
                                
                                <button onclick="dispatchMessage()" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-2xl text-xs shadow-lg flex items-center space-x-1.5 transition transform active:scale-95">
                                    <span>Send</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- User Contacts Drawer Panel -->
                    <div id="contactsSidebar" class="hidden w-64 bg-black/60 border-l border-white/10 p-3 flex-col overflow-y-auto chat-scroll absolute right-0 top-0 bottom-0 z-20 backdrop-blur-xl">
                        <div class="flex items-center justify-between pb-2 mb-2 border-b border-white/10">
                            <h3 class="text-xs font-bold text-white">👥 Members & Contacts</h3>
                            <button onclick="toggleContactsSidebar()" class="text-gray-400 hover:text-white text-xs">✕</button>
                        </div>
                        <div id="usersListContainer" class="space-y-2">
                            <!-- JS injects contact items here -->
                        </div>
                    </div>

                </div>
            @endif

        </div>

    </main>

    <!-- NOTIFICATION TOAST POPUP (WITH PREVIEW & SENDER AVATAR) -->
    <div id="toastNotification" class="hidden fixed top-6 right-6 z-50 p-3.5 bg-gray-900/95 border border-blue-500/40 rounded-2xl shadow-2xl flex items-center space-x-3 text-xs w-80 toast-animate">
        <img id="toastAvatar" src="{{ asset('images/profile.jpg') }}" class="w-10 h-10 rounded-full border border-blue-400 object-cover">
        <div class="overflow-hidden flex-1">
            <h4 id="toastSender" class="font-bold text-white truncate">New Message</h4>
            <p id="toastMessage" class="text-gray-300 truncate text-[11px]">Preview...</p>
        </div>
        <button onclick="hideToastNotification()" class="text-gray-400 hover:text-white text-xs">✕</button>
    </div>

    <!-- USER PROFILE CUSTOMIZER MODAL -->
    @if ($authenticated)
        <div id="profileModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-gray-900 border border-white/20 p-6 rounded-3xl w-full max-w-md shadow-2xl text-xs chat-scroll max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-white">👤 Profile Settings</h3>
                    <button onclick="toggleProfileModal()" class="text-gray-400 hover:text-white">✕</button>
                </div>

                <form id="profileForm" onsubmit="saveProfileSettings(event)" class="space-y-3">
                    <div class="flex items-center space-x-4 mb-2">
                        <img id="profileAvatarPreview" src="{{ $user->avatar ? asset($user->avatar) : asset('images/profile.jpg') }}" class="w-16 h-16 rounded-full border-2 border-blue-500 object-cover">
                        <div>
                            <label class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 rounded-xl font-semibold text-white cursor-pointer inline-block">
                                Change Photo
                                <input type="file" name="avatar" accept="image/*" onchange="previewAvatarImage(event)" class="hidden">
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-gray-400 mb-1">First Name</label>
                            <input type="text" name="first_name" value="{{ $user->first_name }}" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white">
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ $user->last_name }}" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-400 mb-1">@Username</label>
                        <input type="text" name="username" value="{{ $user->username }}" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white">
                    </div>

                    <div>
                        <label class="block text-gray-400 mb-1">Bio / About You</label>
                        <textarea name="bio" rows="2" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white resize-none">{{ $user->bio }}</textarea>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-white/10">
                        <label class="block font-bold text-gray-300">Social Media Links</label>
                        <input type="url" name="social_linkedin" value="{{ $user->social_links['linkedin'] ?? '' }}" placeholder="LinkedIn URL" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-1.5 text-white">
                        <input type="url" name="social_github" value="{{ $user->social_links['github'] ?? '' }}" placeholder="GitHub URL" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-1.5 text-white">
                        <input type="url" name="social_twitter" value="{{ $user->social_links['twitter'] ?? '' }}" placeholder="Twitter / X URL" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-1.5 text-white">
                        <input type="url" name="social_website" value="{{ $user->social_links['website'] ?? '' }}" placeholder="Website URL" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-1.5 text-white">
                    </div>

                    <div class="flex justify-end space-x-2 pt-3">
                        <button type="button" onclick="toggleProfileModal()" class="px-4 py-2 bg-gray-800 rounded-xl text-gray-300">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ADD STORY MODAL -->
    <div id="addStoryModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-5 rounded-3xl w-full max-w-sm shadow-2xl text-xs">
            <h3 class="text-sm font-bold text-white mb-2">📸 Post 24-Hour Story</h3>
            <form id="storyForm" onsubmit="submitStory(event)" class="space-y-3">
                <textarea name="content" rows="3" placeholder="What's on your mind?..." class="w-full bg-black/40 border border-white/20 rounded-xl p-3 text-white focus:outline-none focus:border-blue-500 resize-none"></textarea>
                <div>
                    <label class="block text-gray-400 mb-1">Optional Photo/Media</label>
                    <input type="file" name="media" accept="image/*,video/*" class="w-full bg-black/40 border border-white/20 rounded-xl p-2 text-white">
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="toggleAddStoryModal()" class="px-3 py-1.5 bg-gray-800 text-gray-300 rounded-xl">Cancel</button>
                    <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-500 font-bold text-white rounded-xl">Post Story</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EMOJI PICKER POPUP -->
    <div id="emojiPicker" class="hidden fixed bottom-24 left-10 md:left-72 z-50 p-3 bg-gray-900 border border-white/20 rounded-2xl shadow-2xl w-64 max-h-48 overflow-y-auto chat-scroll grid grid-cols-6 gap-2 text-xl">
        <button onclick="addEmoji('😊')">😊</button><button onclick="addEmoji('😂')">😂</button>
        <button onclick="addEmoji('🔥')">🔥</button><button onclick="addEmoji('❤️')">❤️</button>
        <button onclick="addEmoji('👍')">👍</button><button onclick="addEmoji('🎉')">🎉</button>
        <button onclick="addEmoji('🚀')">🚀</button><button onclick="addEmoji('💡')">💡</button>
        <button onclick="addEmoji('😎')">😎</button><button onclick="addEmoji('🙏')">🙏</button>
        <button onclick="addEmoji('💯')">💯</button><button onclick="addEmoji('✨')">✨</button>
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
        </div>
    </div>

    <!-- SCHEDULE MODAL -->
    <div id="scheduleModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-5 rounded-2xl w-full max-w-sm shadow-2xl">
            <h3 class="text-sm font-bold text-white mb-2">⏱️ Schedule Message Delivery</h3>
            <input type="datetime-local" id="scheduleDateTime" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white text-xs mb-4 focus:outline-none">
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
            </div>
            <div class="flex items-center justify-center space-x-3">
                <button id="recordVideoBtn" onclick="startVideoRecording()" class="px-4 py-2 bg-emerald-600 text-xs font-semibold rounded-xl text-white">Start Recording</button>
                <button id="stopVideoBtn" onclick="stopVideoRecording()" class="hidden px-4 py-2 bg-rose-600 text-xs font-semibold rounded-xl text-white">Stop & Send</button>
                <button onclick="closeVideoNoteModal()" class="px-3 py-2 bg-gray-800 text-xs rounded-xl text-gray-300">Close</button>
            </div>
        </div>
    </div>

    <!-- SETTINGS & FX MODAL -->
    <div id="settingsModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-5 rounded-2xl w-full max-w-md shadow-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-white">⚙️ Themes & Audio FX</h3>
                <button onclick="toggleSettingsModal()" class="text-gray-400 hover:text-white text-xs">✕</button>
            </div>
            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-medium text-gray-300 mb-1.5">Theme Presets:</label>
                    <select id="themeSelect" onchange="changeTheme(this.value)" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white">
                        <option value="sapphire">Deep Sapphire (Default)</option>
                        <option value="cyberpunk">Cyberpunk Neon</option>
                        <option value="emerald">Emerald Mint</option>
                        <option value="sunset">Sunset Rose</option>
                        <option value="light">Light Pearl</option>
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-300">Audio Chime Notifications:</span>
                    <input type="checkbox" id="soundToggle" checked class="rounded border-gray-600 bg-gray-800 text-blue-600">
                </div>
            </div>
            <div class="mt-5 flex justify-end">
                <button onclick="toggleSettingsModal()" class="px-4 py-1.5 bg-blue-600 text-white rounded-xl font-semibold">Done</button>
            </div>
        </div>
    </div>

    <!-- AUDIO CALL MODAL -->
    <div id="audioCallModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-6 rounded-2xl w-full max-w-xs shadow-2xl text-center">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-3xl shadow-xl animate-bounce">📞</div>
            <h3 class="text-base font-bold text-white">Audio Call</h3>
            <p id="callStatus" class="text-xs text-emerald-400 my-2 animate-pulse">Connecting Peer-to-Peer...</p>
            <div class="flex items-center justify-center space-x-4 mt-6">
                <button onclick="endAudioCall()" class="p-3 bg-rose-600 rounded-full text-white font-bold">📵</button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let selectedScheduledTime = null;
        let mediaRecorder = null;
        let audioChunks = [];
        let videoRecorder = null;
        let videoChunks = [];
        let videoStream = null;
        let lastMessageCount = 0;

        document.addEventListener('DOMContentLoaded', () => {
            @if($authenticated)
                fetchMessages();
                fetchUsers();
                fetchStories();
                setInterval(() => {
                    fetchMessages();
                    fetchStories();
                }, 2000);
            @endif
        });

        // Fetch Messages & Handle Notifications
        async function fetchMessages() {
            try {
                const response = await fetch('{{ route("chat.messages") }}');
                const data = await response.json();

                if (data.status === 'success') {
                    const stream = document.getElementById('messageStream');
                    
                    if (data.messages.length === 0) {
                        stream.innerHTML = '<div class="text-center py-12 text-gray-400 text-xs">No messages yet. Be the first to start chatting!</div>';
                    } else {
                        stream.innerHTML = data.messages.map(msg => renderMessageBubble(msg)).join('');

                        // Check for new incoming message notification
                        if (lastMessageCount > 0 && data.messages.length > lastMessageCount) {
                            const newestMsg = data.messages[data.messages.length - 1];
                            if (!newestMsg.is_me) {
                                playNotificationSound();
                                showToastNotification(newestMsg);
                            }
                        }
                        lastMessageCount = data.messages.length;
                    }
                }
            } catch (err) {
                console.error(err);
            }
        }

        // Render Message Bubble with Telegram Reactions
        function renderMessageBubble(msg) {
            let contentHtml = '';

            if (msg.type === 'text') {
                contentHtml = `<p class="whitespace-pre-wrap">${escapeHtml(msg.message || '')}</p>`;
            } else if (msg.type === 'image' || (msg.mime_type && msg.mime_type.startsWith('image/'))) {
                contentHtml = `<img src="${msg.file_url}" class="max-w-xs max-h-60 rounded-2xl border border-white/10 cursor-pointer hover:opacity-90" onclick="window.open('${msg.file_url}', '_blank')">`;
            } else if (msg.type === 'gif') {
                contentHtml = `<img src="${msg.file_url}" class="max-w-xs max-h-48 rounded-2xl border border-white/10">`;
            } else if (msg.type === 'voice') {
                contentHtml = `<div class="flex items-center space-x-2"><span class="text-lg">🎙️</span><audio controls src="${msg.file_url}" class="h-8 w-48 sm:w-60"></audio></div>`;
            } else if (msg.type === 'video') {
                contentHtml = `<video controls src="${msg.file_url}" class="max-w-xs max-h-60 rounded-2xl border border-white/10"></video>`;
            } else if (msg.type === 'file') {
                contentHtml = `
                    <div class="flex items-center space-x-3 p-2.5 bg-black/20 rounded-2xl border border-white/10">
                        <span class="text-2xl">📄</span>
                        <div class="overflow-hidden">
                            <p class="font-semibold text-xs truncate max-w-[180px]">${escapeHtml(msg.file_name || 'Attachment')}</p>
                            <p class="text-[10px] text-gray-400">${msg.file_size || ''}</p>
                        </div>
                        <a href="${msg.file_url}" download class="px-2.5 py-1 bg-blue-600 hover:bg-blue-500 rounded-xl text-[10px] font-bold text-white">Download</a>
                    </div>`;
            }

            // Reaction Pille Badges
            const reactionPills = msg.reactions.map(r => {
                const activeClass = r.user_reacted ? 'bg-blue-600/40 border-blue-400 text-white font-bold' : 'bg-black/30 border-white/10 text-gray-300';
                return `<button onclick="toggleReaction(${msg.id}, '${r.emoji}')" class="px-2 py-0.5 rounded-full border text-[11px] flex items-center space-x-1 ${activeClass}">
                    <span>${r.emoji}</span><span>${r.count}</span>
                </button>`;
            }).join('');

            // Quick Emoji Reaction Bar
            const reactionPickerBar = `
                <div class="flex items-center space-x-1 mt-1.5 opacity-90 hover:opacity-100">
                    <button onclick="toggleReaction(${msg.id}, '❤️')" class="hover:scale-125 transition text-xs">❤️</button>
                    <button onclick="toggleReaction(${msg.id}, '👍')" class="hover:scale-125 transition text-xs">👍</button>
                    <button onclick="toggleReaction(${msg.id}, '🔥')" class="hover:scale-125 transition text-xs">🔥</button>
                    <button onclick="toggleReaction(${msg.id}, '😂')" class="hover:scale-125 transition text-xs">😂</button>
                    <button onclick="toggleReaction(${msg.id}, '🚀')" class="hover:scale-125 transition text-xs">🚀</button>
                </div>`;

            const alignClass = msg.is_me ? 'justify-end' : 'justify-start';
            const bgClass = msg.is_me 
                ? 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white rounded-br-none' 
                : 'bg-white/10 text-gray-100 rounded-bl-none border border-white/10';

            return `
                <div class="flex ${alignClass} mb-2">
                    <div class="flex items-start space-x-2 max-w-[88%] sm:max-w-md">
                        ${!msg.is_me ? `<img src="${msg.avatar_url}" class="w-8 h-8 rounded-full border border-white/20 object-cover mt-1">` : ''}
                        <div>
                            <div class="p-3 rounded-2xl ${bgClass} shadow-md text-xs">
                                <div class="flex items-center justify-between text-[10px] opacity-75 mb-1 space-x-3">
                                    <span class="font-bold">${escapeHtml(msg.sender_name)}</span>
                                    <span>${msg.created_at}</span>
                                </div>
                                ${contentHtml}
                            </div>
                            <div class="flex items-center space-x-1 mt-1">
                                ${reactionPills}
                                ${reactionPickerBar}
                            </div>
                        </div>
                    </div>
                </div>`;
        }

        // Toggle Reaction
        async function toggleReaction(msgId, emoji) {
            try {
                await fetch('{{ route("chat.react") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ chat_message_id: msgId, emoji: emoji })
                });
                fetchMessages();
            } catch (err) { console.error(err); }
        }

        // Fetch Users Contacts
        async function fetchUsers() {
            try {
                const response = await fetch('{{ route("chat.users") }}');
                const data = await response.json();
                if (data.status === 'success') {
                    const container = document.getElementById('usersListContainer');
                    if (container) {
                        container.innerHTML = data.users.map(u => `
                            <div class="flex items-center space-x-2.5 p-2 rounded-xl bg-white/5 border border-white/5">
                                <img src="${u.avatar_url}" class="w-8 h-8 rounded-full border border-white/20 object-cover">
                                <div class="overflow-hidden flex-1">
                                    <p class="font-semibold text-xs text-white truncate">${escapeHtml(u.name)}</p>
                                    <p class="text-[10px] text-gray-400 truncate">@ ${escapeHtml(u.username)}</p>
                                </div>
                            </div>
                        `).join('');
                    }
                }
            } catch (err) { console.error(err); }
        }

        // Fetch 24h Stories
        async function fetchStories() {
            try {
                const response = await fetch('{{ route("chat.stories") }}');
                const data = await response.json();
                if (data.status === 'success') {
                    const container = document.getElementById('storiesContainer');
                    if (container) {
                        container.innerHTML = data.stories.map(s => `
                            <div class="flex flex-col items-center space-y-1 cursor-pointer shrink-0" onclick="alert('${escapeHtml(s.user_name)}: ${escapeHtml(s.content || '')}')">
                                <div class="w-11 h-11 rounded-full p-0.5 bg-gradient-to-tr from-amber-400 to-rose-500">
                                    <img src="${s.avatar_url}" class="w-full h-full rounded-full object-cover border-2 border-gray-900">
                                </div>
                                <span class="text-[10px] text-gray-300 truncate max-w-[50px]">${escapeHtml(s.user_name)}</span>
                            </div>
                        `).join('');
                    }
                }
            } catch (err) { console.error(err); }
        }

        // Dispatch Message
        async function dispatchMessage() {
            const input = document.getElementById('chatInput');
            const text = input.value.trim();
            if (!text && !selectedScheduledTime) return;

            const payload = { message: text, type: 'text', scheduled_at: selectedScheduledTime };
            input.value = '';
            clearSchedule();

            try {
                await fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(payload)
                });
                fetchMessages();
            } catch (err) { console.error(err); }
        }

        // File Attachment Upload
        async function handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            let type = 'file';
            if (file.type.startsWith('image/')) type = 'image';

            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', type);
            if (selectedScheduledTime) formData.append('scheduled_at', selectedScheduledTime);

            showUploadProgress(true, 10);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("chat.upload") }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.upload.onprogress = e => {
                if (e.lengthComputable) showUploadProgress(true, Math.round((e.loaded / e.total) * 100));
            };
            xhr.onload = () => {
                showUploadProgress(false, 100);
                clearSchedule();
                fetchMessages();
            };
            xhr.send(formData);
        }

        // Voice Recording
        async function startVoiceRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];
                mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
                mediaRecorder.start();
                document.getElementById('voiceRecorderBar').classList.remove('hidden');
            } catch (err) { alert('Microphone permission required.'); }
        }

        function stopAndSendVoiceRecording() {
            if (!mediaRecorder) return;
            mediaRecorder.onstop = async () => {
                const blob = new Blob(audioChunks, { type: 'audio/webm' });
                const file = new File([blob], 'voicenote.webm', { type: 'audio/webm' });
                const formData = new FormData();
                formData.append('file', file);
                formData.append('type', 'voice');
                await fetch('{{ route("chat.upload") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: formData });
                document.getElementById('voiceRecorderBar').classList.add('hidden');
                fetchMessages();
            };
            mediaRecorder.stop();
        }

        function cancelVoiceRecording() {
            if (mediaRecorder) mediaRecorder.stop();
            document.getElementById('voiceRecorderBar').classList.add('hidden');
        }

        // Video Recording Note
        async function openVideoNoteModal() {
            document.getElementById('videoNoteModal').classList.remove('hidden');
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                document.getElementById('videoPreview').srcObject = videoStream;
            } catch (err) { alert('Camera permission required.'); }
        }

        function closeVideoNoteModal() {
            if (videoStream) videoStream.getTracks().forEach(t => t.stop());
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
        }

        function stopVideoRecording() {
            if (!videoRecorder) return;
            videoRecorder.onstop = async () => {
                const blob = new Blob(videoChunks, { type: 'video/webm' });
                const file = new File([blob], 'videonote.webm', { type: 'video/webm' });
                const formData = new FormData();
                formData.append('file', file);
                formData.append('type', 'video');
                await fetch('{{ route("chat.upload") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: formData });
                closeVideoNoteModal();
                fetchMessages();
            };
            videoRecorder.stop();
        }

        // Profile Form Submit
        async function saveProfileSettings(e) {
            e.preventDefault();
            const formData = new FormData(document.getElementById('profileForm'));
            try {
                const res = await fetch('{{ route("chat.profile") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    alert('Profile updated successfully!');
                    toggleProfileModal();
                    location.reload();
                } else { alert(data.message || 'Error updating profile'); }
            } catch (err) { console.error(err); }
        }

        // Submit Story
        async function submitStory(e) {
            e.preventDefault();
            const formData = new FormData(document.getElementById('storyForm'));
            try {
                await fetch('{{ route("chat.stories.create") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: formData });
                toggleAddStoryModal();
                fetchStories();
            } catch (err) { console.error(err); }
        }

        // Audio & Toast Notification Effects
        function playNotificationSound() {
            if (!document.getElementById('soundToggle')?.checked) return;
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.15); // A5
                gain.gain.setValueAtTime(0.15, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.3);
            } catch (e) {}
        }

        function showToastNotification(msg) {
            const toast = document.getElementById('toastNotification');
            if (!toast) return;
            document.getElementById('toastAvatar').src = msg.avatar_url;
            document.getElementById('toastSender').innerText = msg.sender_name;
            document.getElementById('toastMessage').innerText = msg.message || (msg.type + ' attachment');
            toast.classList.remove('hidden');
            setTimeout(hideToastNotification, 4500);
        }

        function hideToastNotification() {
            document.getElementById('toastNotification')?.classList.add('hidden');
        }

        // UI Helpers & Modals
        function switchGateTab(tab) {
            if (tab === 'login') {
                document.getElementById('gateLoginForm').classList.remove('hidden');
                document.getElementById('gateRegisterForm').classList.add('hidden');
                document.getElementById('gateLoginTab').className = 'flex-1 py-2 rounded-lg bg-blue-600 text-white transition';
                document.getElementById('gateRegisterTab').className = 'flex-1 py-2 rounded-lg text-gray-400 hover:text-white transition';
            } else {
                document.getElementById('gateLoginForm').classList.add('hidden');
                document.getElementById('gateRegisterForm').classList.remove('hidden');
                document.getElementById('gateLoginTab').className = 'flex-1 py-2 rounded-lg text-gray-400 hover:text-white transition';
                document.getElementById('gateRegisterTab').className = 'flex-1 py-2 rounded-lg bg-indigo-600 text-white transition';
            }
        }

        function toggleContactsSidebar() { document.getElementById('contactsSidebar').classList.toggle('hidden'); }
        function toggleProfileModal() { document.getElementById('profileModal')?.classList.toggle('hidden'); }
        function toggleAddStoryModal() { document.getElementById('addStoryModal')?.classList.toggle('hidden'); }
        function toggleSettingsModal() { document.getElementById('settingsModal').classList.toggle('hidden'); }
        function toggleEmojiPicker() { document.getElementById('emojiPicker').classList.toggle('hidden'); }
        function toggleGifPicker() { document.getElementById('gifPicker').classList.toggle('hidden'); }
        function toggleScheduleModal() { document.getElementById('scheduleModal').classList.toggle('hidden'); }

        function startAudioCall() { document.getElementById('audioCallModal').classList.remove('hidden'); }
        function endAudioCall() { document.getElementById('audioCallModal').classList.add('hidden'); }

        function changeTheme(t) {
            const el = document.getElementById('chatBoxContainer');
            el.classList.remove('theme-cyberpunk', 'theme-emerald', 'theme-sunset', 'theme-light');
            if (t !== 'sapphire') el.classList.add(`theme-${t}`);
        }

        function addEmoji(e) { document.getElementById('chatInput').value += e; toggleEmojiPicker(); }
        async function sendGif(url) {
            toggleGifPicker();
            await fetch('{{ route("chat.send") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ type: 'gif', file_url: url })
            });
            fetchMessages();
        }

        function applySchedule() {
            const val = document.getElementById('scheduleDateTime').value;
            if (val) {
                selectedScheduledTime = val;
                document.getElementById('scheduledNotice').classList.remove('hidden');
                document.getElementById('scheduledTimeLabel').innerText = val;
            }
            toggleScheduleModal();
        }

        function clearSchedule() { selectedScheduledTime = null; document.getElementById('scheduledNotice').classList.add('hidden'); }

        function showUploadProgress(show, percent) {
            const el = document.getElementById('uploadProgressBarContainer');
            if (show) {
                el.classList.remove('hidden');
                document.getElementById('uploadProgressBar').style.width = percent + '%';
                document.getElementById('uploadPercentText').innerText = percent + '%';
            } else { el.classList.add('hidden'); }
        }

        function handleKeyPress(e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); dispatchMessage(); } }
        function previewAvatarImage(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = ev => document.getElementById('profileAvatarPreview').src = ev.target.result;
                reader.readAsDataURL(file);
            }
        }
        function escapeHtml(t) { return String(t).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
    </script>
</body>
</html>
