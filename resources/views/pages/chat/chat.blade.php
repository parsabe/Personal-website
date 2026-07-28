<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Chat Portal - Parsa Besharat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind & External App CSS -->
    <script>window.tailwind = { config: { darkMode: 'class' } };</script>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">

    <!-- Separate Chat Portal CSS -->
    <link rel="stylesheet" href="{{ asset('css/chat-portal.css') }}">
</head>

<body data-authenticated="{{ $authenticated ? 'true' : 'false' }}" class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-3 lg:p-8 min-h-screen relative overflow-x-hidden">

    <!-- MAIN FLOATING WINDOW CONTAINER (MATCHES HOMEPAGE EXACTLY) -->
    <div id="main-container" class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden h-[88vh] z-10 transition-all duration-700 shadow-2xl border border-white/10">

        <!-- Top Right Mac Window Dots & Theme Toggle -->
        <div class="absolute top-5 right-6 flex items-center gap-4 z-40">
            <button id="theme-toggle" class="p-2 rounded-full ios-glass transition hover:scale-110">
                <span id="theme-icon-light" class="hidden text-xs">☀️</span>
                <span id="theme-icon-dark" class="hidden text-xs">🌙</span>
            </button>
            <div class="flex gap-2">
                <div class="w-3.5 h-3.5 rounded-full bg-[#ff5f56] shadow-sm border border-[#e0443e]"></div>
                <div class="w-3.5 h-3.5 rounded-full bg-[#ffbd2e] shadow-sm border border-[#dea123]"></div>
                <div class="w-3.5 h-3.5 rounded-full bg-[#27c93f] shadow-sm border border-[#1aab29]"></div>
            </div>
        </div>

        <!-- SIDEBAR INTEGRATED INSIDE THE CONTAINER -->
        @include('sidebar')

        <!-- MAIN CHAT CONTENT AREA -->
        <main class="flex-1 flex flex-col overflow-hidden relative p-4 lg:p-6 justify-between bg-black/20">

            @if (!$authenticated)
                <!-- AUTHENTICATION GATE (NO GUEST MODE) -->
                <div class="flex-1 flex flex-col items-center justify-center p-6 text-center animate-scale-up">
                    <div class="w-20 h-20 mb-4 rounded-full bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 flex items-center justify-center text-3xl shadow-2xl animate-bounce">
                        🔒
                    </div>
                    <h2 class="text-2xl font-extrabold text-white mb-1 tracking-tight">Members Only Chat Portal</h2>
                    <p class="text-xs text-gray-400 max-w-sm mb-6 font-medium">Guest access is disabled. Please log in or create an account with 2FA protection to access the chat portal.</p>

                    <div class="w-full max-w-md bg-black/40 p-6 rounded-3xl border border-white/15 shadow-2xl backdrop-blur-xl animate-fade-in">
                        <!-- Toggle Buttons -->
                        <div class="flex rounded-2xl bg-white/5 p-1 mb-5 text-xs font-semibold">
                            <button id="gateLoginTab" onclick="switchGateTab('login')" class="flex-1 py-2.5 rounded-xl bg-blue-600 text-white shadow-md transition transform active:scale-95">Log In</button>
                            <button id="gateRegisterTab" onclick="switchGateTab('register')" class="flex-1 py-2.5 rounded-xl text-gray-400 hover:text-white transition">Sign Up</button>
                        </div>

                        <!-- Login Form -->
                        <form id="gateLoginForm" method="POST" action="{{ route('login') }}" class="space-y-3.5 text-left text-xs">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ url()->current() }}">
                            <div>
                                <label class="block text-gray-400 mb-1 font-medium">Email Address</label>
                                <input type="email" name="email" required class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-gray-400 mb-1 font-medium">Password</label>
                                <input type="password" name="password" required class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-blue-500 transition">
                            </div>
                            <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg transition transform active:scale-95">
                                Log In to Chat
                            </button>
                        </form>

                        <!-- Register Form -->
                        <form id="gateRegisterForm" method="POST" action="{{ route('register') }}" class="hidden space-y-3.5 text-left text-xs">
                            @csrf
                            <div>
                                <label class="block text-gray-400 mb-1 font-medium">Full Name</label>
                                <input type="text" name="name" required class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-gray-400 mb-1 font-medium">Email Address</label>
                                <input type="email" name="email" required class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-gray-400 mb-1 font-medium">Password</label>
                                <input type="password" name="password" required class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-gray-400 mb-1 font-medium">Confirm Password</label>
                                <input type="password" name="password_confirmation" required class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-blue-500 transition">
                            </div>
                            <button type="submit" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-lg transition transform active:scale-95">
                                Create Account (with 2FA)
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- AUTHENTICATED CHAT PORTAL CONTENT -->

                <!-- Header Controls Bar (Telegram Style) -->
                <header class="flex items-center justify-between px-4 py-2.5 bg-black/40 border border-white/10 rounded-2xl shrink-0 mb-3 animate-fade-in backdrop-blur-md">
                    <div class="flex items-center space-x-3">
                        <button id="btnBackToUsers" onclick="backToUserDirectory()" class="hidden px-3 py-1.5 rounded-full bg-blue-600/80 hover:bg-blue-600 text-white text-xs font-semibold flex items-center space-x-1 shadow-md transition transform hover:scale-105 active:scale-95">
                            <span>← Back</span>
                        </button>
                        
                        <div id="activeContactHeader" class="flex items-center space-x-3">
                            <div class="relative">
                                <img id="activeContactAvatar" src="{{ $user->avatar ? asset($user->avatar) : asset('images/profile.jpg') }}" class="w-10 h-10 rounded-full border border-white/20 object-cover shadow-md">
                                <span id="activeContactDot" class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 rounded-full border-2 border-gray-900 animate-pulse"></span>
                            </div>
                            <div>
                                <h1 class="text-sm font-bold text-white flex items-center gap-1.5">
                                    <span id="activeContactName">Members Directory</span>
                                    <span id="activeContactUsername" class="text-xs font-normal text-gray-400">(@all)</span>
                                </h1>
                                <p id="activeContactStatus" class="text-[11px] text-indigo-400 font-medium">Select a user below to start chatting</p>
                            </div>
                        </div>
                    </div>

                    <!-- Telegram Style Action Buttons -->
                    <div class="flex items-center space-x-2">
                        <button onclick="startAudioCall()" class="px-3 py-1.5 rounded-full bg-blue-600/80 hover:bg-blue-600 text-white text-xs font-semibold flex items-center space-x-1 shadow-md transition transform hover:scale-105 active:scale-95">
                            📞 <span>Call</span>
                        </button>
                        <button onclick="toggleProfileModal()" class="px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-gray-200 text-xs font-semibold flex items-center space-x-1 transition transform hover:scale-105 active:scale-95">
                            👤 <span>My Profile</span>
                        </button>
                        <button id="theme-toggle" class="p-2 rounded-full ios-glass transition hover:scale-110" title="Toggle Theme">
                            <span id="theme-icon-light" class="hidden text-xs">☀️</span>
                            <span id="theme-icon-dark" class="hidden text-xs">🌙</span>
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-full bg-rose-950/80 hover:bg-rose-900 text-rose-300 text-xs font-semibold border border-rose-500/30 transition transform hover:scale-105 active:scale-95">
                                Logout
                            </button>
                        </form>
                    </div>
                </header>

                <!-- MAIN CHAT BODY CONTAINER -->
                <div id="chatBoxContainer" class="flex-1 flex flex-col overflow-hidden relative rounded-2xl border border-white/10 bg-black/30 backdrop-blur-md">
                    
                    <!-- VIEW 1: USER DIRECTORY DIRECT SCREEN (DEFAULT) -->
                    <div id="userDirectoryScreen" class="flex-1 flex flex-col p-4 overflow-y-auto chat-scroll space-y-4">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pb-3 border-b border-white/10">
                            <div>
                                <h2 class="text-sm font-bold text-white flex items-center gap-2">
                                    <span>👥 MEMBERS DIRECTORY & ACTIVE CHATS</span>
                                </h2>
                                <p class="text-xs text-gray-400">Click any user to initiate a direct chat session</p>
                            </div>
                            <input type="text" id="searchUsersInput" onkeyup="filterUsersDirectory()" placeholder="🔍 Search members..." 
                                class="w-full sm:w-64 bg-black/50 border border-white/15 rounded-xl px-3.5 py-1.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-blue-500">
                        </div>

                        <div id="mainUsersGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            <!-- JS dynamically injects member cards -->
                        </div>
                    </div>

                    <!-- VIEW 2: ACTIVE DIRECT CHAT SCREEN (HIDDEN UNTIL USER CLICKED) -->
                    <div id="activeChatScreen" class="hidden flex-1 flex flex-col overflow-hidden">
                        
                        <div id="messageStream" class="flex-1 p-4 overflow-y-auto space-y-3 chat-scroll">
                            <div id="loadingIndicator" class="text-center py-12 text-gray-400 text-xs flex items-center justify-center space-x-2">
                                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Loading conversation...</span>
                            </div>
                        </div>

                        <!-- Voice Note Recording Bar -->
                        <div id="voiceRecorderBar" class="hidden px-4 py-2 bg-rose-950/90 border-t border-rose-500/30 flex items-center justify-between text-xs animate-scale-up">
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
                        <div id="uploadProgressBarContainer" class="hidden px-4 py-2 bg-blue-950/80 border-t border-blue-500/30 animate-fade-in">
                            <div class="flex items-center justify-between text-xs text-blue-300 mb-1">
                                <span id="uploadStatusText">Uploading file (up to 4GB)...</span>
                                <span id="uploadPercentText">0%</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-1.5 overflow-hidden">
                                <div id="uploadProgressBar" class="bg-blue-500 h-1.5 rounded-full w-0 transition-all duration-200"></div>
                            </div>
                        </div>

                        <!-- Input Bar -->
                        <div class="p-3 bg-black/40 border-t border-white/10 flex flex-col space-y-2 shrink-0">
                            <div class="flex items-center justify-between px-1 text-xs">
                                <div class="flex items-center space-x-2">
                                    <button type="button" onclick="toggleEmojiPicker()" class="p-1.5 hover:bg-white/10 rounded-full text-gray-300 hover:text-amber-400 transition transform hover:scale-110" title="Add Emojis">😊</button>
                                    <button type="button" onclick="toggleGifPicker()" class="p-1.5 hover:bg-white/10 rounded-full text-gray-300 hover:text-purple-400 font-bold transition transform hover:scale-110" title="Attach GIF">GIF</button>
                                    <label class="p-1.5 hover:bg-white/10 rounded-full text-gray-300 hover:text-blue-400 cursor-pointer transition transform hover:scale-110" title="Attach File (Up to 4GB)">
                                        📎
                                        <input type="file" id="fileAttachmentInput" onchange="handleFileSelect(event)" class="hidden">
                                    </label>
                                    <button type="button" onclick="startVoiceRecording()" class="p-1.5 hover:bg-white/10 rounded-full text-gray-300 hover:text-rose-400 transition transform hover:scale-110" title="Record Voice Note">🎙️</button>
                                    <button type="button" onclick="openVideoNoteModal()" class="p-1.5 hover:bg-white/10 rounded-full text-gray-300 hover:text-emerald-400 transition transform hover:scale-110" title="Record Video Note">📹</button>
                                    <button type="button" onclick="toggleScheduleModal()" class="p-1.5 hover:bg-white/10 rounded-full text-gray-300 hover:text-amber-300 transition transform hover:scale-110" title="Schedule Message">⏱️</button>
                                </div>

                                <div id="scheduledNotice" class="hidden text-amber-300 font-mono text-[11px] flex items-center gap-1">
                                    <span>Scheduled:</span><span id="scheduledTimeLabel"></span>
                                    <button onclick="clearSchedule()" class="text-rose-400 font-bold">×</button>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <textarea id="chatInput" rows="1" placeholder="Type a message..."
                                    onkeydown="handleKeyPress(event)"
                                    class="flex-1 bg-white/5 border border-white/15 rounded-2xl px-4 py-2.5 text-white placeholder-gray-400 text-sm focus:outline-none focus:border-blue-500 resize-none chat-scroll transition duration-200"></textarea>
                                
                                <button id="sendMsgBtn" onclick="dispatchMessage()" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-2xl text-xs shadow-lg flex items-center space-x-1.5 transition transform active:scale-95 hover:scale-105">
                                    <span>Send</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

        </main>

    </div>

    <!-- NOTIFICATION TOAST POPUP (WITH SENDER AVATAR & PREVIEW) -->
    <div id="toastNotification" class="hidden fixed top-6 right-6 z-50 p-3.5 bg-gray-900/95 border border-blue-500/40 rounded-2xl shadow-2xl flex items-center space-x-3 text-xs w-80 animate-toast">
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
            <div class="bg-gray-900 border border-white/20 p-6 rounded-3xl w-full max-w-md shadow-2xl text-xs chat-scroll max-h-[90vh] overflow-y-auto animate-scale-up">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-white">👤 Profile Settings</h3>
                    <button onclick="toggleProfileModal()" class="text-gray-400 hover:text-white">✕</button>
                </div>

                <form id="profileForm" onsubmit="saveProfileSettings(event)" class="space-y-3">
                    <div class="flex items-center space-x-4 mb-2">
                        <img id="profileAvatarPreview" src="{{ $user->avatar ? asset($user->avatar) : asset('images/profile.jpg') }}" class="w-16 h-16 rounded-full border-2 border-blue-500 object-cover">
                        <div>
                            <label class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 rounded-xl font-semibold text-white cursor-pointer inline-block transition transform hover:scale-105">
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
        <div class="bg-gray-900 border border-white/20 p-5 rounded-3xl w-full max-w-sm shadow-2xl text-xs animate-scale-up">
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

    <!-- EMOJI PICKER -->
    <div id="emojiPicker" class="hidden fixed bottom-24 left-10 md:left-72 z-50 p-3 bg-gray-900 border border-white/20 rounded-2xl shadow-2xl w-64 max-h-48 overflow-y-auto chat-scroll grid grid-cols-6 gap-2 text-xl animate-scale-up">
        <button onclick="addEmoji('😊')">😊</button><button onclick="addEmoji('😂')">😂</button>
        <button onclick="addEmoji('🔥')">🔥</button><button onclick="addEmoji('❤️')">❤️</button>
        <button onclick="addEmoji('👍')">👍</button><button onclick="addEmoji('🎉')">🎉</button>
        <button onclick="addEmoji('🚀')">🚀</button><button onclick="addEmoji('💡')">💡</button>
        <button onclick="addEmoji('😎')">😎</button><button onclick="addEmoji('🙏')">🙏</button>
        <button onclick="addEmoji('💯')">💯</button><button onclick="addEmoji('✨')">✨</button>
    </div>

    <!-- GIF PICKER -->
    <div id="gifPicker" class="hidden fixed bottom-24 left-10 md:left-72 z-50 p-4 bg-gray-900 border border-white/20 rounded-2xl shadow-2xl w-72 animate-scale-up">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-300">Choose GIF</span>
            <button onclick="toggleGifPicker()" class="text-gray-400 hover:text-white text-xs">✕</button>
        </div>
        <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto chat-scroll">
            <img src="https://media.giphy.com/media/26bgQ8u0e1bBf90aY/giphy.gif" onclick="sendGif('https://media.giphy.com/media/26bgQ8u0e1bBf90aY/giphy.gif')" class="w-full h-20 object-cover rounded-lg cursor-pointer hover:opacity-80 transition transform hover:scale-105">
            <img src="https://media.giphy.com/media/l0HlHFRbmaZtBRhXG/giphy.gif" onclick="sendGif('https://media.giphy.com/media/l0HlHFRbmaZtBRhXG/giphy.gif')" class="w-full h-20 object-cover rounded-lg cursor-pointer hover:opacity-80 transition transform hover:scale-105">
            <img src="https://media.giphy.com/media/3o7TKSjRrfIPjeiVyM/giphy.gif" onclick="sendGif('https://media.giphy.com/media/3o7TKSjRrfIPjeiVyM/giphy.gif')" class="w-full h-20 object-cover rounded-lg cursor-pointer hover:opacity-80 transition transform hover:scale-105">
        </div>
    </div>

    <!-- SCHEDULE MODAL -->
    <div id="scheduleModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-5 rounded-2xl w-full max-w-sm shadow-2xl animate-scale-up">
            <h3 class="text-sm font-bold text-white mb-2">⏱️ Schedule Message Delivery</h3>
            <input type="datetime-local" id="scheduleDateTime" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white text-xs mb-4 focus:outline-none">
            <div class="flex justify-end space-x-2">
                <button onclick="toggleScheduleModal()" class="px-3 py-1.5 bg-gray-800 text-xs rounded-xl text-gray-300">Cancel</button>
                <button onclick="applySchedule()" class="px-4 py-1.5 bg-amber-600 hover:bg-amber-500 text-xs font-semibold text-white rounded-xl">Set Schedule</button>
            </div>
        </div>
    </div>

    <!-- VIDEO NOTE MODAL -->
    <div id="videoNoteModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-5 rounded-2xl w-full max-w-md shadow-2xl text-center animate-scale-up">
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
        <div class="bg-gray-900 border border-white/20 p-5 rounded-2xl w-full max-w-md shadow-2xl animate-scale-up">
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
        <div class="bg-gray-900 border border-white/20 p-6 rounded-2xl w-full max-w-xs shadow-2xl text-center animate-scale-up">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-3xl shadow-xl animate-bounce">📞</div>
            <h3 class="text-base font-bold text-white">Audio Call</h3>
            <p id="callStatus" class="text-xs text-emerald-400 my-2 animate-pulse">Connecting Peer-to-Peer...</p>
            <div class="flex items-center justify-center space-x-4 mt-6">
                <button onclick="endAudioCall()" class="p-3 bg-rose-600 rounded-full text-white font-bold transform hover:scale-110 transition">📵</button>
            </div>
        </div>
    </div>

    <!-- Taskbar & Mac Window Controls -->
    @include('taskbar')
    <script src="{{ asset('js/mac-window-controls.js') }}"></script>
    <script type="module" src="{{ asset('js/chat-portal.js') }}"></script>
</body>
</html>
