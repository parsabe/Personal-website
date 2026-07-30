<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }}{{ $user->username ? ' (@' . $user->username . ')' : '' }} | {{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Benutzerprofil' : 'User Profile' }} - Parsa Besharat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.tailwind = { config: { darkMode: 'class' } };</script>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
    <style>
        .animate-tab-fade {
            animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
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
                <div class="w-full h-48 relative bg-gradient-to-r from-indigo-900 via-purple-900 to-slate-900">
                    @if($user->header_banner)
                        <img id="mainProfileHeaderImg" src="{{ asset($user->header_banner) }}" class="w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent"></div>
                </div>

                <!-- AVATAR & EDIT BUTTON HEADER -->
                <div class="px-6 pb-6 relative z-10">
                    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between -mt-16 mb-4 gap-4">
                        <div class="relative">
                            <img id="mainProfileAvatarImg" src="{{ $user->avatar ? asset($user->avatar) : asset('images/profile.jpg') }}" 
                                class="w-28 h-28 rounded-full border-4 border-gray-900 object-cover shadow-2xl">
                        </div>

                        <div class="flex items-center gap-2">
                            <button onclick="toggleProfileModal()" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:to-pink-500 text-white font-bold rounded-2xl text-xs shadow-xl transition transform hover:scale-105 active:scale-95 flex items-center gap-2 border border-white/20">
                                ⚙️ {{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Profil-Einstellungen & Anpassen' : 'Profile Settings & Customizer' }}
                            </button>
                        </div>
                    </div>

                    <!-- USER INFO & STATS -->
                    <div class="space-y-4">
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-extrabold text-white tracking-tight flex items-center gap-2">
                                <span>{{ $user->name }}</span>
                                <span class="text-xs font-mono text-gray-400 font-normal">@({{ $user->username ?? 'user' }})</span>
                            </h1>
                            <p class="text-xs text-gray-300 pt-1 leading-relaxed max-w-2xl font-medium">
                                {{ $user->bio ?? 'No bio added yet.' }}
                            </p>
                        </div>

                        <!-- SPECIAL HIGHLIGHTED STATS COUNTERS WITH HIGH CONTRAST TEXT -->
                        <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-white/10 text-xs font-sans">
                            <div class="px-4 py-2 rounded-2xl bg-indigo-950/80 border border-indigo-500/40 flex items-center gap-2 shadow-lg text-white font-bold">
                                <span class="text-indigo-300 font-bold">📱</span>
                                <span class="text-white"><strong class="text-white font-extrabold text-sm">{{ count($posts) }}</strong> Posts</span>
                            </div>

                            <div class="px-4 py-2 rounded-2xl bg-pink-950/80 border border-pink-500/40 flex items-center gap-2 shadow-lg text-white font-bold">
                                <span class="text-pink-300 font-bold">📔</span>
                                <span class="text-white"><strong class="text-white font-extrabold text-sm">{{ count($articles) }}</strong> {{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Journale' : 'Journals' }}</span>
                            </div>

                            <button onclick="openFollowersModal()" class="px-4 py-2 rounded-2xl bg-blue-900/90 hover:bg-blue-800 border border-blue-400/60 flex items-center gap-2 shadow-xl transition transform hover:scale-105 active:scale-95 cursor-pointer text-white font-bold">
                                <span class="text-blue-300 font-bold">👥</span>
                                <span class="text-white"><strong class="text-white font-extrabold text-sm">{{ $followersCount }}</strong> Followers</span>
                            </button>

                            <button onclick="openFollowingModal()" class="px-4 py-2 rounded-2xl bg-purple-900/90 hover:bg-purple-800 border border-purple-400/60 flex items-center gap-2 shadow-xl transition transform hover:scale-105 active:scale-95 cursor-pointer text-white font-bold">
                                <span class="text-purple-300 font-bold">✨</span>
                                <span class="text-white"><strong class="text-white font-extrabold text-sm">{{ $followingCount }}</strong> Following</span>
                            </button>
                        </div>
            <!-- INSTAGRAM STORY HIGHLIGHTS & ARCHIVES TRAY -->
            <div class="p-5 rounded-3xl bg-black/40 border border-white/10 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-xs text-white flex items-center gap-2">
                        <span>📸 {{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Story-Archive & Highlights' : 'Story Archives & Highlights' }} ({{ count($archives) }})</span>
                    </h3>
                    <button onclick="openCreateArchiveModal()" class="px-3.5 py-1.5 bg-gradient-to-r from-amber-500 via-rose-500 to-purple-600 hover:from-amber-400 hover:to-purple-500 text-white font-bold rounded-xl text-[11px] shadow-lg transition transform hover:scale-105 active:scale-95 flex items-center gap-1 border border-white/20">
                        <span>➕</span>
                        <span>{{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Neues Archiv' : 'New Archive' }}</span>
                    </button>
                </div>

                <div class="flex items-center space-x-4 overflow-x-auto pb-2 chat-scroll">
                    <!-- + NEW ARCHIVE BUBBLE -->
                    <div onclick="openCreateArchiveModal()" class="flex flex-col items-center space-y-1 cursor-pointer shrink-0 group">
                        <div class="w-16 h-16 rounded-full border-2 border-dashed border-white/30 hover:border-pink-500 flex items-center justify-center bg-white/5 group-hover:bg-pink-500/20 transition">
                            <span class="text-xl text-gray-300 group-hover:text-white font-bold">+</span>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-400 group-hover:text-white transition">New</span>
                    </div>

                    @forelse($archives as $arc)
                        <div onclick="viewStoryArchive({{ json_encode($arc) }})" class="flex flex-col items-center space-y-1 cursor-pointer shrink-0 group relative">
                            <div class="p-[2px] rounded-full bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 shadow-md group-hover:scale-105 transition">
                                <img src="{{ asset($arc->cover_image) }}" class="w-16 h-16 rounded-full object-cover border-2 border-gray-900">
                            </div>
                            <span class="text-[11px] font-semibold text-gray-200 truncate max-w-[70px]">{{ $arc->title }}</span>
                            <button onclick="event.stopPropagation(); deleteStoryArchive({{ $arc->id }})" title="Delete Archive" class="absolute -top-1 -right-1 w-5 h-5 bg-rose-600 hover:bg-rose-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow">✕</button>
                        </div>
                    @empty
                        <div class="flex-1 py-3 text-center text-xs text-gray-400 font-mono italic bg-white/5 rounded-2xl border border-white/10">
                            No Story Archives created yet. Click "New Archive" to create your first Instagram-style Story Highlight!
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- SEPARATED TAB BUTTONS (POSTS / JOURNALS / GALLERIES) -->
            <div class="space-y-4">
                <div class="flex items-center space-x-2 border-b border-white/10 pb-2 overflow-x-auto">
                    <button onclick="switchProfileTab('posts')" id="tabBtnPosts" class="px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-indigo-600 text-white shadow-lg border border-indigo-400/40">
                        <span>📱</span>
                        <span>{{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Beiträge Chronik' : 'Posts Feed' }} ({{ count($posts) }})</span>
                    </button>

                    <button onclick="switchProfileTab('journals')" id="tabBtnJournals" class="px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-white/10 hover:bg-white/20 text-gray-300 border border-white/10">
                        <span>📔</span>
                        <span>{{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Veröffentlichte Journale' : 'Published Journals' }} ({{ count($articles) }})</span>
                    </button>

                    <button onclick="switchProfileTab('galleries')" id="tabBtnGalleries" class="px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-white/10 hover:bg-white/20 text-gray-300 border border-white/10">
                        <span>🖼️</span>
                        <span>{{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Avatare & Banner Galerie' : 'Avatars & Headers' }}</span>
                    </button>
                </div>

                <!-- 1. POSTS TAB CONTENT -->
                <div id="tabContentPosts" class="animate-tab-fade space-y-4">
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
                            <p>No posts published yet.</p>
                        </div>
                    @endif
                </div>

                <!-- 2. JOURNALS TAB CONTENT -->
                <div id="tabContentJournals" class="hidden animate-tab-fade space-y-4">
                    @if(count($articles) > 0)
                        <div class="space-y-3">
                            @foreach($articles as $art)
                                <div class="p-4 rounded-3xl bg-black/50 border border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs">
                                    <div class="space-y-1 overflow-hidden flex-1">
                                        <h4 class="font-bold text-white text-sm truncate">{{ $art->title }}</h4>
                                        <p class="text-[10px] text-indigo-400 font-mono">{{ $art->created_at->format('M d, Y') }} • Published Journal</p>
                                    </div>
                                    <div class="flex items-center space-x-2 shrink-0">
                                        <a href="/publications/articles/{{ $art->slug }}" class="px-3 py-1.5 bg-indigo-600/40 hover:bg-indigo-600 text-indigo-200 hover:text-white rounded-xl font-semibold transition">
                                            View ➔
                                        </a>
                                        <button onclick="openEditArticleModal({{ $art->id }}, '{{ addslashes($art->title) }}', '{{ addslashes(str_replace(["\r", "\n"], [' ', ' '], $art->content)) }}')" class="px-3 py-1.5 bg-blue-600/40 hover:bg-blue-600 text-blue-200 hover:text-white rounded-xl font-semibold transition">
                                            ✏️ Edit
                                        </button>
                                        <button onclick="deleteUserArticle({{ $art->id }})" class="px-3 py-1.5 bg-rose-600/40 hover:bg-rose-600 text-rose-200 hover:text-white rounded-xl font-semibold transition">
                                            🗑️ Delete
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 rounded-3xl bg-black/40 border border-white/10 text-center text-gray-400 text-xs font-mono space-y-2">
                            <p>No journals published yet.</p>
                        </div>
                    @endif
                </div>

                <!-- 3. GALLERIES TAB CONTENT -->
                <div id="tabContentGalleries" class="hidden animate-tab-fade space-y-6">
                    @php
                        $avatarsGallery = is_array($user->avatars_gallery) ? $user->avatars_gallery : [];
                        $headersGallery = is_array($user->headers_gallery) ? $user->headers_gallery : [];
                    @endphp
                    
                    <!-- AVATARS GALLERY -->
                    <div class="p-5 rounded-3xl bg-black/50 border border-white/10 space-y-3">
                        <h4 class="font-bold text-white text-xs flex items-center gap-2">
                            <span>👤 Avatars Gallery ({{ count($avatarsGallery) }})</span>
                        </h4>
                        @if(count($avatarsGallery) > 0)
                            <div class="flex flex-wrap gap-3">
                                @foreach($avatarsGallery as $avPath)
                                    <div class="relative group border-2 {{ $user->avatar === $avPath ? 'border-blue-500 scale-105' : 'border-transparent' }} rounded-full overflow-hidden transition">
                                        <img src="{{ asset($avPath) }}" onclick="selectAvatarFromGallery('{{ addslashes($avPath) }}')" class="w-14 h-14 rounded-full object-cover cursor-pointer hover:opacity-80 shadow-md">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">No saved avatars yet.</p>
                        @endif
                    </div>

                    <!-- HEADERS GALLERY -->
                    <div class="p-5 rounded-3xl bg-black/50 border border-white/10 space-y-3">
                        <h4 class="font-bold text-white text-xs flex items-center gap-2">
                            <span>🌄 Cover Banner Headers Gallery ({{ count($headersGallery) }})</span>
                        </h4>
                        @if(count($headersGallery) > 0)
                            <div class="flex flex-wrap gap-3">
                                @foreach($headersGallery as $headPath)
                                    <div class="relative group border-2 {{ $user->header_banner === $headPath ? 'border-indigo-500 scale-105' : 'border-transparent' }} rounded-2xl overflow-hidden transition">
                                        <img src="{{ asset($headPath) }}" onclick="selectHeaderFromGallery('{{ addslashes($headPath) }}')" class="w-28 h-14 rounded-xl object-cover cursor-pointer hover:opacity-80 shadow-md">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">No saved cover headers yet.</p>
                        @endif
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- FULL COMPLETE USER PROFILE CUSTOMIZER MODAL -->
    <div id="profileModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-6 rounded-3xl w-full max-w-lg shadow-2xl text-xs chat-scroll max-h-[90vh] overflow-y-auto animate-scale-up space-y-4">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <span>⚙️ Profile Settings & Customizer Suite</span>
                </h3>
                <button onclick="toggleProfileModal()" class="text-gray-400 hover:text-white text-sm font-bold">✕</button>
            </div>

            <form id="profileForm" onsubmit="saveProfileSettings(event)" class="space-y-4">
                @csrf
                
                <!-- HEADER COVER PREVIEW & UPLOAD -->
                <div class="relative w-full h-28 rounded-2xl overflow-hidden bg-gradient-to-r from-indigo-900 via-purple-900 to-slate-900 border border-white/10 group">
                    <img id="profileHeaderPreview" src="{{ $user->header_banner ? asset($user->header_banner) : '' }}" 
                        class="w-full h-full object-cover {{ $user->header_banner ? '' : 'hidden' }}">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <label class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 rounded-xl font-semibold text-white cursor-pointer transition text-[11px] shadow-lg">
                            🖼️ Change Cover Banner
                            <input type="file" name="header_banner" accept="image/*" onchange="previewHeaderImage(event)" class="hidden">
                        </label>
                    </div>
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-sm text-[9px] text-gray-300 font-mono">Cover Banner</span>
                </div>

                <!-- AVATAR & USERNAME SECTION -->
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img id="profileAvatarPreview" src="{{ $user->avatar ? asset($user->avatar) : asset('images/profile.jpg') }}" class="w-16 h-16 rounded-full border-2 border-blue-500 object-cover shadow-lg">
                        <label class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-blue-600 hover:bg-blue-500 flex items-center justify-center text-white cursor-pointer shadow">
                            📷
                            <input type="file" name="avatar" accept="image/*" onchange="previewAvatarImage(event)" class="hidden">
                        </label>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-white text-sm">{{ $user->name }}</h4>
                        <p class="text-[11px] text-gray-400 font-mono">{{ $user->username ? '@' . $user->username : $user->email }}</p>
                    </div>
                </div>

                <!-- BATCH MULTI AVATARS / HEADERS UPLOAD -->
                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-white/10">
                    <label class="px-3 py-2 bg-blue-600/30 hover:bg-blue-600/50 border border-blue-500/40 text-blue-200 rounded-xl text-center font-semibold cursor-pointer transition">
                        + Add Avatars
                        <input type="file" name="multiple_avatars[]" multiple accept="image/*" onchange="saveProfileSettings(event)" class="hidden">
                    </label>
                    <label class="px-3 py-2 bg-purple-600/30 hover:bg-purple-600/50 border border-purple-500/40 text-purple-200 rounded-xl text-center font-semibold cursor-pointer transition">
                        + Add Headers
                        <input type="file" name="multiple_headers[]" multiple accept="image/*" onchange="saveProfileSettings(event)" class="hidden">
                    </label>
                </div>

                <!-- NAME & USERNAME -->
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
                    <label class="block text-gray-400 mb-1">Bio / Headline</label>
                    <textarea name="bio" rows="2" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-2 text-white resize-none">{{ $user->bio }}</textarea>
                </div>

                <!-- PRIVACY SETTINGS -->
                <div class="space-y-2 pt-2 border-t border-white/10">
                    <label class="block font-bold text-gray-300">🛡️ Account Privacy</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-gray-400 text-[10px] mb-1">Visibility</label>
                            <select name="account_privacy" class="w-full bg-black/40 border border-white/20 rounded-xl px-2.5 py-1.5 text-white">
                                <option value="public" {{ $user->account_privacy === 'public' ? 'selected' : '' }}>🌐 Public</option>
                                <option value="private" {{ $user->account_privacy === 'private' ? 'selected' : '' }}>🔒 Private</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-[10px] mb-1">Story Privacy</label>
                            <select name="story_privacy" class="w-full bg-black/40 border border-white/20 rounded-xl px-2.5 py-1.5 text-white">
                                <option value="public" {{ $user->story_privacy === 'public' ? 'selected' : '' }}>🌐 Everyone</option>
                                <option value="followers" {{ $user->story_privacy === 'followers' ? 'selected' : '' }}>👥 Followers Only</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SOCIAL LINKS -->
                <div class="space-y-2 pt-2 border-t border-white/10">
                    <label class="block font-bold text-gray-300">Social Media Links</label>
                    <input type="text" name="social_linkedin" value="{{ $user->social_links['linkedin'] ?? '' }}" placeholder="LinkedIn URL" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-1.5 text-white">
                    <input type="text" name="social_github" value="{{ $user->social_links['github'] ?? '' }}" placeholder="GitHub URL" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-1.5 text-white">
                    <input type="text" name="social_twitter" value="{{ $user->social_links['twitter'] ?? '' }}" placeholder="Twitter URL" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-1.5 text-white">
                    <input type="text" name="social_website" value="{{ $user->social_links['website'] ?? '' }}" placeholder="Website URL" class="w-full bg-black/40 border border-white/20 rounded-xl px-3 py-1.5 text-white">
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t border-white/10">
                    <button type="button" onclick="toggleProfileModal()" class="px-4 py-2 bg-gray-800 text-gray-300 rounded-xl">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white font-bold rounded-xl shadow-lg">Save All Settings</button>
                </div>
            </form>
        </div>
    </div>

    <!-- FOLLOWERS MODAL -->
    <div id="followersModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-6 rounded-3xl w-full max-w-sm shadow-2xl text-xs space-y-4 animate-scale-up">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <span>👥 Followers List ({{ $followersCount }})</span>
                </h3>
                <button onclick="closeFollowersModal()" class="text-gray-400 hover:text-white">✕</button>
            </div>
            <div id="followersListContainer" class="space-y-3 max-h-60 overflow-y-auto chat-scroll">
                <p class="text-center text-gray-400 italic py-4">Loading followers...</p>
            </div>
        </div>
    </div>

    <!-- FOLLOWING MODAL -->
    <div id="followingModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-6 rounded-3xl w-full max-w-sm shadow-2xl text-xs space-y-4 animate-scale-up">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <span>✨ Following List ({{ $followingCount }})</span>
                </h3>
                <button onclick="closeFollowingModal()" class="text-gray-400 hover:text-white">✕</button>
            </div>
            <div id="followingListContainer" class="space-y-3 max-h-60 overflow-y-auto chat-scroll">
                <p class="text-center text-gray-400 italic py-4">Loading following list...</p>
            </div>
        </div>
    </div>

    @include('taskbar')
    <script src="{{ asset('js/mac-window-controls.js') }}"></script>
    <script>
        function toggleProfileModal() {
            const modal = document.getElementById('profileModal');
            if (modal) modal.classList.toggle('hidden');
        }

        function switchProfileTab(tab) {
            const btnPosts = document.getElementById('tabBtnPosts');
            const btnJournals = document.getElementById('tabBtnJournals');
            const btnGalleries = document.getElementById('tabBtnGalleries');

            const contentPosts = document.getElementById('tabContentPosts');
            const contentJournals = document.getElementById('tabContentJournals');
            const contentGalleries = document.getElementById('tabContentGalleries');

            btnPosts.className = "px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-white/10 hover:bg-white/20 text-gray-300 border border-white/10";
            btnJournals.className = "px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-white/10 hover:bg-white/20 text-gray-300 border border-white/10";
            btnGalleries.className = "px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-white/10 hover:bg-white/20 text-gray-300 border border-white/10";

            contentPosts.classList.add('hidden');
            contentJournals.classList.add('hidden');
            contentGalleries.classList.add('hidden');

            if (tab === 'posts') {
                btnPosts.className = "px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-indigo-600 text-white shadow-lg border border-indigo-400/40";
                contentPosts.classList.remove('hidden');
            } else if (tab === 'journals') {
                btnJournals.className = "px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-pink-600 text-white shadow-lg border border-pink-400/40";
                contentJournals.classList.remove('hidden');
            } else if (tab === 'galleries') {
                btnGalleries.className = "px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-purple-600 text-white shadow-lg border border-purple-400/40";
                contentGalleries.classList.remove('hidden');
            }
        }

        async function saveProfileSettings(e) {
            if (e && e.preventDefault) e.preventDefault();
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
                    setTimeout(() => location.reload(), 600);
                } else {
                    if (window.showToast) window.showToast(data.message || 'Error saving settings.', 'error');
                }
            } catch (err) {
                console.error(err);
            }
        }

        function previewHeaderImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.getElementById('profileHeaderPreview');
                    if (img) {
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        function previewAvatarImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.getElementById('profileAvatarPreview');
                    if (img) img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        async function selectAvatarFromGallery(path) {
            const formData = new FormData();
            formData.append('avatar_path', path);
            try {
                const res = await fetch('/user/profile/select-avatar', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    if (window.showToast) window.showToast('Avatar updated!', 'success');
                    location.reload();
                }
            } catch (e) { console.error(e); }
        }

        async function selectHeaderFromGallery(path) {
            const formData = new FormData();
            formData.append('header_path', path);
            try {
                const res = await fetch('/user/profile/select-header', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    if (window.showToast) window.showToast('Cover Header updated!', 'success');
                    location.reload();
                }
            } catch (e) { console.error(e); }
        }

        function openFollowersModal() {
            document.getElementById('followersModal').classList.remove('hidden');
        }
        function closeFollowersModal() {
            document.getElementById('followersModal').classList.add('hidden');
        }
        function openFollowingModal() {
            document.getElementById('followingModal').classList.remove('hidden');
        }
        function closeFollowingModal() {
            document.getElementById('followingModal').classList.add('hidden');
        }

        async function deleteUserArticle(id) {
            if (!confirm('Are you sure you want to delete this journal?')) return;
            try {
                const res = await fetch(`/user/articles/${id}/delete`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                });
                const data = await res.json();
                if (data.status === 'success') {
                    if (window.showToast) window.showToast('Journal deleted successfully.', 'success');
                    location.reload();
                }
            } catch (e) { console.error(e); }
        }

        function openCreateArchiveModal() {
            document.getElementById('createArchiveModal').classList.remove('hidden');
        }
        function closeCreateArchiveModal() {
            document.getElementById('createArchiveModal').classList.add('hidden');
        }
        function closeViewArchiveModal() {
            document.getElementById('viewArchiveModal').classList.add('hidden');
        }

        async function submitCreateArchive(e) {
            e.preventDefault();
            const form = document.getElementById('createArchiveForm');
            const formData = new FormData(form);

            try {
                const res = await fetch('/user/story-archives/create', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    if (window.showToast) window.showToast(data.message || 'Story Archive Highlight created!', 'success');
                    closeCreateArchiveModal();
                    location.reload();
                } else {
                    if (window.showToast) window.showToast(data.message || 'Error creating archive.', 'error');
                }
            } catch (err) { console.error(err); }
        }

        function viewStoryArchive(arc) {
            if (!arc) return;
            document.getElementById('archiveViewerTitle').innerText = arc.title || 'Story Archive';
            document.getElementById('archiveViewerCover').src = arc.cover_image ? '/' + arc.cover_image.replace(/^\//, '') : '/images/profile.jpg';
            
            const container = document.getElementById('archiveViewerMediaContainer');
            const items = arc.story_items || [];
            document.getElementById('archiveViewerCount').innerText = `${items.length} Story Media Items`;

            if (items.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-6 space-y-2">
                        <img src="/${arc.cover_image}" class="w-full max-h-64 object-cover rounded-2xl border border-white/10 shadow-md">
                        <p class="text-xs text-gray-300 font-bold">${arc.title}</p>
                    </div>
                `;
            } else {
                container.innerHTML = items.map(item => `
                    <div class="rounded-2xl overflow-hidden border border-white/10 shadow-md">
                        ${item.type === 'video' ? `<video src="/${item.url}" controls class="w-full max-h-72 rounded-2xl"></video>` : `<img src="/${item.url}" class="w-full max-h-72 object-cover rounded-2xl">`}
                    </div>
                `).join('');
            }

            document.getElementById('viewArchiveModal').classList.remove('hidden');
        }

        async function deleteStoryArchive(id) {
            if (!confirm('Are you sure you want to delete this Story Archive?')) return;
            try {
                const res = await fetch(`/user/story-archives/${id}/delete`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                });
                const data = await res.json();
                if (data.status === 'success') {
                    if (window.showToast) window.showToast('Story Archive deleted.', 'success');
                    location.reload();
                }
            } catch (e) { console.error(e); }
        }
    </script>

    <!-- CREATE STORY ARCHIVE MODAL -->
    <div id="createArchiveModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-6 rounded-3xl w-full max-w-md shadow-2xl text-xs space-y-4 animate-scale-up">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <span>📸 {{ (session('app_locale') === 'de' || app()->getLocale() === 'de') ? 'Story-Archiv Highlight Erstellen' : 'Create Story Archive Highlight' }}</span>
                </h3>
                <button onclick="closeCreateArchiveModal()" class="text-gray-400 hover:text-white text-base font-bold">✕</button>
            </div>

            <form id="createArchiveForm" onsubmit="submitCreateArchive(event)" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-300 font-semibold mb-1">Archive Title</label>
                    <input type="text" name="title" required placeholder="e.g. Travels ✈️, AI Research 🤖, Memories..." class="w-full bg-black/50 border border-white/20 rounded-xl px-3.5 py-2 text-white text-xs focus:outline-none focus:border-pink-500">
                </div>

                <div>
                    <label class="block text-gray-300 font-semibold mb-1">Cover Thumbnail Image (Optional)</label>
                    <input type="file" name="cover_image" accept="image/*" class="w-full text-xs text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-600/30 file:text-pink-300 hover:file:bg-pink-600/50">
                </div>

                <div>
                    <label class="block text-gray-300 font-semibold mb-1">Story Photos / Videos (Multiple)</label>
                    <input type="file" name="multiple_media[]" multiple accept="image/*,video/*" class="w-full text-xs text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/30 file:text-indigo-300 hover:file:bg-indigo-600/50">
                </div>

                <div>
                    <label class="block text-gray-300 font-semibold mb-1">Visibility</label>
                    <select name="visibility" class="w-full bg-black/50 border border-white/20 rounded-xl px-3 py-2 text-xs text-gray-200 focus:outline-none focus:border-pink-500">
                        <option value="public">🌐 Public (Everyone)</option>
                        <option value="private">🔒 Private (Only Me)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-3 border-t border-white/10">
                    <button type="button" onclick="closeCreateArchiveModal()" class="px-4 py-2 bg-gray-800 text-gray-300 rounded-xl font-semibold">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-amber-500 via-rose-500 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition">Create Archive</button>
                </div>
            </form>
        </div>
    </div>

    <!-- VIEW STORY ARCHIVE MODAL -->
    <div id="viewArchiveModal" class="hidden fixed inset-0 bg-black/90 backdrop-blur-xl z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 p-6 rounded-3xl w-full max-w-lg shadow-2xl text-xs space-y-4 animate-scale-up relative">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <div class="flex items-center space-x-3">
                    <img id="archiveViewerCover" src="{{ asset('images/profile.jpg') }}" class="w-9 h-9 rounded-full border border-pink-500 object-cover">
                    <div>
                        <h3 id="archiveViewerTitle" class="text-sm font-bold text-white">Archive Title</h3>
                        <p id="archiveViewerCount" class="text-[10px] text-gray-400 font-mono">0 Story Media Items</p>
                    </div>
                </div>
                <button onclick="closeViewArchiveModal()" class="text-gray-400 hover:text-white text-base font-bold">✕</button>
            </div>

            <div id="archiveViewerMediaContainer" class="w-full max-h-[60vh] overflow-y-auto chat-scroll space-y-3 p-2 bg-black/60 rounded-2xl border border-white/10">
                <p class="text-center text-gray-400 italic py-6">No media in this archive.</p>
            </div>
        </div>
    </div>
</body>
</html>
