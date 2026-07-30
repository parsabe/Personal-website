<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parsa Besharat - AI Researcher</title>

    <meta name="description" content="Parsa Besharat is an Iranian Researcher and AI Engineer. He is currently pursuing his
    MS.c degree in Data Science at the TU Freiberg University in Sachsen, Germany.">
    <meta name="author" content="Parsa Besharat">
    <meta name="keywords"
        content="Parsa Besharat, Researcher, AI Engineer, Data Scientist, Machine Learning, Deep Learning, Natural Language Processing, Computer Vision, TU Freiberg University, Germany">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="profile">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Parsa Besharat - Researcher & AI Engineer">
    <meta property="og:description"
        content="Parsa Besharat is an Iranian Researcher and AI Engineer. He is currently pursuing his MS.c degree in Data Science at the TU Freiberg University in Sachsen, Germany.">
    <meta property="og:image" content="{{ asset('images/profile.jpg') }}">
    <meta property="profile:first_name" content="Parsa">
    <meta property="profile:last_name" content="Besharat">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Parsa Besharat - Researcher & AI Engineer">
    <meta name="twitter:description"
        content="Parsa Besharat is an Iranian Researcher and AI Engineer. He is currently pursuing his MS.c degree in Data Science at the TU Freiberg University in Sachsen, Germany.">
    <meta name="twitter:image" content="{{ asset('images/profile.jpg') }}">

    <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <script>window.tailwind = { config: { darkMode: 'class' } };</script>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">

    {!! $profileSchema->toScript() !!}

</head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-E441FBGYXG"></script>
<script type="module" src="{{ asset('js/gtag.js') }}"></script>

<body
    class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-4 lg:p-10 min-h-screen relative overflow-x-hidden">

    @include('loading_screen')

    <div id="main-container"
        class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden h-[85vh] z-10 transition-colors duration-700 animate-page-zoom-in">

        @include('top-header-controls')

        @include('sidebar')

        <main class="flex-1 p-8 pt-12 lg:p-14 lg:pt-14 relative flex flex-col justify-center overflow-y-auto">
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mt-12 lg:mt-0">

                <div class="animate-page-slide-up">
                    <span
                        class="inline-flex items-center gap-2 px-4 py-1.5 ios-glass text-gray-900 dark:text-white rounded-full text-sm font-bold mb-6 hover:scale-105 transition-transform">
                        👋 HELLO!
                    </span>

                    <h1
                        class="text-5xl lg:text-6xl font-extrabold mb-6 tracking-tight text-gray-900 dark:text-white drop-shadow-sm">
                        I'm <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-pink-600 dark:from-orange-400 dark:to-pink-500">Parsa
                            Besharat.</span>
                    </h1>

                    <p
                        class="text-lg text-gray-800 dark:text-gray-200 leading-relaxed mb-10 font-medium drop-shadow-sm">
                        I am a Persian AI Researcher, currently pursuing my
                        MS.c degree in Data Science at the TU Freiberg University in Sachsen, Germany.
                    </p>

                    <div class="flex flex-wrap items-center gap-5">
                        <a href="/contact"
                            class="px-8 py-3.5 bg-gradient-to-r from-orange-500 to-pink-600 text-white font-bold rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition transform border border-white/20 active:scale-95">
                            Contact me
                        </a>
                        <a href="/chat"
                            class="px-8 py-3.5 ios-glass text-gray-900 dark:text-white font-bold rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition transform border border-white/20 active:scale-95 flex items-center gap-2">
                            <span>💬 Social Chat & Portal</span>
                        </a>
                    </div>
                </div>

                <div class="relative flex flex-col items-center justify-center mt-10 lg:mt-0 animate-page-zoom-in delay-200">
                    <img src="{{ asset('images/profile.jpg') }}" alt="Hero Portrait"
                        class="w-full max-w-xs rounded-full object-cover object-[50%_25%] aspect-square border-4 border-white/40 shadow-[0_10px_40px_rgba(0,0,0,0.2)] hover:scale-105 transition-transform duration-500 mb-6">

                    @if(Auth::check())
                        <!-- HOMEPAGE QUICK POST & COMMUNITY FEED WIDGET -->
                        <div class="w-full bg-black/60 backdrop-blur-md border border-white/15 rounded-3xl p-5 shadow-2xl space-y-4 text-xs font-sans text-white">
                            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                                <h3 class="font-bold text-sm text-white flex items-center gap-2">
                                    <span>🐦 Community Timeline & Quick Post</span>
                                </h3>
                                <span class="text-[10px] text-emerald-400 font-mono flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Live Feed
                                </span>
                            </div>

                            <!-- QUICK POST FORM -->
                            <form id="homeQuickPostForm" onsubmit="submitHomePost(event)" class="space-y-3">
                                <textarea id="homePostContent" rows="2" placeholder="Share a thoughts, photo, video, or update..." 
                                    class="w-full bg-white/5 border border-white/15 rounded-2xl p-3 text-white text-xs placeholder-gray-400 focus:outline-none focus:border-pink-500 resize-none font-sans"></textarea>
                                
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <label class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-gray-200 rounded-xl text-[11px] font-semibold cursor-pointer transition flex items-center gap-1">
                                            <span>📷 Photo / Video</span>
                                            <input type="file" id="homePostMedia" accept="image/*,video/*" class="hidden" onchange="document.getElementById('homeMediaName').innerText = this.files[0]?.name || ''">
                                        </label>
                                        <span id="homeMediaName" class="text-[10px] text-amber-400 font-mono truncate max-w-[100px]"></span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <input type="datetime-local" id="homePostScheduledAt" title="Optional Scheduled Release" class="bg-black/50 border border-white/20 rounded-xl px-2 py-1 text-[10px] text-gray-300 font-mono">
                                        <button type="submit" class="px-5 py-1.5 bg-gradient-to-r from-orange-500 to-pink-600 hover:from-orange-400 hover:to-pink-500 text-white font-bold rounded-xl text-xs shadow-lg transition transform active:scale-95">
                                            Post ➔
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- HOMEPAGE LIVE FEED STREAM -->
                            <div id="homeLiveFeedStream" class="space-y-3 max-h-64 overflow-y-auto chat-scroll pr-1 pt-2 border-t border-white/10">
                                <p class="text-center text-gray-400 text-xs italic">Loading community posts...</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                fetchHomePublicFeed();
            });

            async function fetchHomePublicFeed() {
                const stream = document.getElementById('homeLiveFeedStream');
                if (!stream) return;

                try {
                    const res = await fetch('/user/posts/feed');
                    const data = await res.json();
                    if (data.status === 'success' && data.posts) {
                        if (data.posts.length === 0) {
                            stream.innerHTML = '<p class="text-center text-gray-400 text-xs italic p-4">No community posts yet. Be the first to post!</p>';
                            return;
                        }

                        stream.innerHTML = data.posts.map(p => `
                            <div class="p-3.5 rounded-2xl bg-black/40 border border-white/10 space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <img src="${p.avatar_url}" class="w-8 h-8 rounded-full border border-white/20 object-cover">
                                        <div>
                                            <span class="font-bold text-white block">${escapeHtml(p.user_name)}</span>
                                            <span class="text-[10px] text-gray-400 font-mono">@${escapeHtml(p.username)} • ${p.created_at}</span>
                                        </div>
                                    </div>
                                    ${p.scheduled_at ? `<span class="px-2 py-0.5 rounded-md bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[9px] font-mono">Scheduled ${p.scheduled_at}</span>` : ''}
                                </div>

                                ${p.content ? `<p class="text-gray-200 leading-relaxed font-sans text-xs">${escapeHtml(p.content)}</p>` : ''}

                                ${p.media_url && p.media_type === 'image' ? `
                                    <img src="${p.media_url}" class="w-full max-h-52 object-cover rounded-xl border border-white/10 shadow-md">
                                ` : ''}

                                ${p.media_url && p.media_type === 'video' ? `
                                    <video src="${p.media_url}" controls class="w-full max-h-52 rounded-xl border border-white/10 shadow-md"></video>
                                ` : ''}

                                <!-- ACTION BUTTONS: LIKE, COMMENT, REPOST, BOOKMARK, SHARE -->
                                <div class="flex items-center justify-between pt-2 border-t border-white/10 text-[11px] text-gray-400 font-mono">
                                    <button onclick="homeToggleAction(${p.id}, 'like')" class="flex items-center space-x-1 hover:text-rose-400 transition ${p.is_liked ? 'text-rose-500 font-bold' : ''}">
                                        <span>${p.is_liked ? '❤️' : '🤍'}</span>
                                        <span>${p.likes_count}</span>
                                    </button>

                                    <button onclick="toggleCommentsDrawer(${p.id})" class="flex items-center space-x-1 hover:text-blue-400 transition">
                                        <span>💬</span>
                                        <span>${p.comments_count}</span>
                                    </button>

                                    <button onclick="homeToggleAction(${p.id}, 'repost')" class="flex items-center space-x-1 hover:text-emerald-400 transition ${p.is_reposted ? 'text-emerald-400 font-bold' : ''}">
                                        <span>🔁</span>
                                        <span>${p.reposts_count}</span>
                                    </button>

                                    <button onclick="homeToggleAction(${p.id}, 'bookmark')" class="flex items-center space-x-1 hover:text-amber-400 transition ${p.is_bookmarked ? 'text-amber-400 font-bold' : ''}">
                                        <span>${p.is_bookmarked ? '🔖' : '📑'}</span>
                                        <span>${p.bookmarks_count}</span>
                                    </button>

                                    <button onclick="sharePostLink(${p.id})" class="hover:text-purple-400 transition">
                                        <span>🔗 Share</span>
                                    </button>
                                </div>

                                <!-- INLINE COMMENTS DRAWER -->
                                <div id="commentsDrawer-${p.id}" class="hidden space-y-2 pt-2 border-t border-white/10">
                                    <div class="space-y-1 max-h-32 overflow-y-auto chat-scroll">
                                        ${p.comments.map(c => `
                                            <div class="p-2 rounded-xl bg-white/5 border border-white/5 text-[11px]">
                                                <span class="font-bold text-blue-400">${escapeHtml(c.user_name)}:</span>
                                                <span class="text-gray-200">${escapeHtml(c.comment)}</span>
                                                <span class="text-[9px] text-gray-500 block">${c.created_at}</span>
                                            </div>
                                        `).join('')}
                                    </div>

                                    <div class="flex items-center gap-1.5 pt-1">
                                        <input type="text" id="commentInput-${p.id}" placeholder="Write a comment..." class="flex-1 bg-white/10 border border-white/20 rounded-xl px-2.5 py-1 text-white text-[11px]">
                                        <button onclick="submitPostComment(${p.id})" class="px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-[10px]">Send</button>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }
                } catch (e) {
                    console.error(e);
                }
            }

            async function submitHomePost(e) {
                e.preventDefault();
                const content = document.getElementById('homePostContent')?.value || '';
                const mediaFile = document.getElementById('homePostMedia')?.files[0];
                const scheduledAt = document.getElementById('homePostScheduledAt')?.value || '';

                const formData = new FormData();
                if (content) formData.append('content', content);
                if (mediaFile) formData.append('media', mediaFile);
                if (scheduledAt) formData.append('scheduled_at', scheduledAt);

                try {
                    const res = await fetch('/user/posts/create', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' },
                        body: formData
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        if (document.getElementById('homePostContent')) document.getElementById('homePostContent').value = '';
                        if (document.getElementById('homePostMedia')) document.getElementById('homePostMedia').value = '';
                        if (document.getElementById('homeMediaName')) document.getElementById('homeMediaName').innerText = '';
                        fetchHomePublicFeed();
                    } else {
                        alert(data.message || 'Error creating post.');
                    }
                } catch (err) {
                    console.error(err);
                }
            }

            async function homeToggleAction(postId, action) {
                try {
                    await fetch(`/user/posts/${postId}/${action}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' }
                    });
                    fetchHomePublicFeed();
                } catch (e) {
                    console.error(e);
                }
            }

            function toggleCommentsDrawer(postId) {
                const drawer = document.getElementById(`commentsDrawer-${postId}`);
                if (drawer) drawer.classList.toggle('hidden');
            }

            async function submitPostComment(postId) {
                const input = document.getElementById(`commentInput-${postId}`);
                if (!input || !input.value.trim()) return;
                const text = input.value.trim();
                input.value = '';

                try {
                    const res = await fetch(`/user/posts/${postId}/comment`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({ comment: text })
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        fetchHomePublicFeed();
                    }
                } catch (e) {
                    console.error(e);
                }
            }

            function sharePostLink(postId) {
                const url = window.location.origin + '/user/posts/' + postId;
                navigator.clipboard.writeText(url);
                alert('Post link copied to clipboard!');
            }

            function escapeHtml(t) {
                return String(t).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }
        </script>

    </div>

    <!-- Taskbar & Mac Window Controls -->
    @include('taskbar')
    <script src="{{ asset('js/mac-window-controls.js') }}"></script>
</body>

</html>