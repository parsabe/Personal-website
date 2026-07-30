<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} (@{{ $user->username }}) - Profile</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.tailwind = { config: { darkMode: 'class' } };</script>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
</head>
<body class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-3 lg:p-8 min-h-screen relative overflow-x-hidden">

    <!-- MAIN CONTAINER -->
    <div id="main-container" class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden h-[88vh] z-10 transition-all duration-700 shadow-2xl border border-white/10 animate-page-zoom-in">

        @include('top-header-controls')
        @include('sidebar')

        <!-- MAIN PROFILE CONTENT CONTAINER -->
        <main class="flex-1 flex flex-col overflow-y-auto relative p-6 pt-12 lg:p-8 lg:pt-14 bg-black/30 gap-6 animate-page-slide-up">
            
            <!-- HEADER COVER BANNER & AVATAR CARD -->
            <div class="relative w-full rounded-3xl overflow-hidden bg-black/50 border border-white/15 shadow-2xl">
                <!-- COVER BANNER -->
                <div class="w-full h-44 relative bg-gradient-to-r from-indigo-900 via-purple-900 to-slate-900">
                    @if($user->header_banner)
                        <img src="{{ asset($user->header_banner) }}" class="w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                </div>

                <!-- AVATAR & EDIT BUTTON HEADER -->
                <div class="px-6 pb-6 relative z-10">
                    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between -mt-14 mb-4 gap-4">
                        <div class="relative">
                            <img src="{{ $user->avatar ? asset($user->avatar) : asset('images/profile.jpg') }}" 
                                class="w-24 h-24 rounded-full border-4 border-gray-900 object-cover shadow-2xl">
                        </div>

                        <div class="flex items-center gap-2">
                            <button onclick="toggleProfileModal()" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-2xl text-xs shadow-lg transition transform hover:scale-105 active:scale-95 flex items-center gap-1.5 border border-white/20">
                                ⚙️ {{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Profil Bearbeiten & Einstellungen' : 'Profile Settings & Customizer' }}
                            </button>
                        </div>
                    </div>

                    <!-- USER INFO & STATS -->
                    <div class="space-y-3">
                        <div>
                            <h1 class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-2">
                                <span>{{ $user->name }}</span>
                                <span class="text-xs font-mono text-gray-400 font-normal">@({{ $user->username ?? 'user' }})</span>
                            </h1>
                            <p class="text-xs text-gray-300 pt-1 leading-relaxed max-w-2xl font-medium">
                                {{ $user->bio ?? 'No bio added yet.' }}
                            </p>
                        </div>

                        <!-- STATS COUNTERS -->
                        <div class="flex items-center space-x-6 text-xs font-mono pt-2 border-t border-white/10 text-gray-300">
                            <div><strong class="text-white font-bold text-sm">{{ count($posts) }}</strong> Posts</div>
                            <div><strong class="text-white font-bold text-sm">{{ count($articles) }}</strong> {{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Journale' : 'Journals' }}</div>
                            <div><strong class="text-white font-bold text-sm">{{ $followersCount }}</strong> Followers</div>
                            <div><strong class="text-white font-bold text-sm">{{ $followingCount }}</strong> Following</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABS & FEED SECTION -->
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-white/10 pb-3">
                    <h3 class="font-bold text-sm text-white flex items-center gap-2">
                        <span>📱 {{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Meine Chronik & Veröffentlichungen' : 'My Posts & Activity Timeline' }}</span>
                    </h3>
                </div>

                <!-- POSTS FEED -->
                @if(count($posts) > 0)
                    <div class="space-y-4">
                        @foreach($posts as $p)
                            <div class="p-4 rounded-3xl bg-black/50 border border-white/10 space-y-3 text-xs">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2.5">
                                        <img src="{{ $p['avatar_url'] }}" class="w-9 h-9 rounded-full border border-white/20 object-cover">
                                        <div>
                                            <span class="font-bold text-white block">{{ $p['user_name'] }}</span>
                                            <span class="text-[10px] text-gray-400 font-mono">@({{ $p['username'] }}) • {{ $p['created_at'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if($p['content'])
                                    <p class="text-gray-200 leading-relaxed font-sans text-xs">{{ $p['content'] }}</p>
                                @endif

                                @if($p['media_url'] && $p['media_type'] === 'image')
                                    <img src="{{ $p['media_url'] }}" class="w-full max-h-72 object-cover rounded-2xl border border-white/10 shadow-md">
                                @endif

                                @if($p['media_url'] && $p['media_type'] === 'video')
                                    <video src="{{ $p['media_url'] }}" controls class="w-full max-h-72 rounded-2xl border border-white/10 shadow-md"></video>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 rounded-3xl bg-black/40 border border-white/10 text-center text-gray-400 text-xs font-mono space-y-2">
                        <p>No posts created yet.</p>
                    </div>
                @endif
            </div>

        </main>
    </div>

    <!-- PROFILE EDIT MODAL -->
    <div id="profileModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-6 rounded-3xl w-full max-w-md shadow-2xl text-xs chat-scroll max-h-[90vh] overflow-y-auto animate-scale-up space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <span>⚙️ Profile Settings & Customizer</span>
                </h3>
                <button onclick="toggleProfileModal()" class="text-gray-400 hover:text-white">✕</button>
            </div>

            <form id="profileForm" onsubmit="saveProfileSettings(event)" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-400 mb-1">First Name</label>
                    <input type="text" name="first_name" value="{{ $user->first_name }}" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white">
                </div>
                <div>
                    <label class="block text-gray-400 mb-1">Last Name</label>
                    <input type="text" name="last_name" value="{{ $user->last_name }}" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white">
                </div>
                <div>
                    <label class="block text-gray-400 mb-1">Bio / Headline</label>
                    <textarea name="bio" rows="3" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white resize-none">{{ $user->bio }}</textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="toggleProfileModal()" class="px-4 py-2 bg-gray-800 text-gray-300 rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg">Save Settings</button>
                </div>
            </form>
        </div>
    </div>

    @include('taskbar')
    <script src="{{ asset('js/mac-window-controls.js') }}"></script>
    <script>
        function toggleProfileModal() {
            const modal = document.getElementById('profileModal');
            if (modal) modal.classList.toggle('hidden');
        }

        async function saveProfileSettings(e) {
            e.preventDefault();
            const form = document.getElementById('profileForm');
            const formData = new FormData(form);

            try {
                const res = await fetch('/chat/profile', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    if (window.showToast) window.showToast(data.message || 'Profile settings saved!', 'success');
                    location.reload();
                } else {
                    if (window.showToast) window.showToast(data.message || 'Error saving settings.', 'error');
                }
            } catch (err) {
                console.error(err);
            }
        }
    </script>
</body>
</html>
