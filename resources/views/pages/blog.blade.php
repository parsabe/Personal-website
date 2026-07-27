<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rich Text Blog - Parsa Besharat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="{{ asset('js/tailwind-config.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/profile.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
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

        <!-- MAIN BLOG CONTENT AREA -->
        <main class="flex-1 flex flex-col overflow-y-auto relative p-6 lg:p-8 bg-black/30 gap-6">
            
            <!-- Header Title -->
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 flex items-center justify-center text-2xl shadow-lg">
                        ✍️
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                            PARSABE BLOG & RESEARCH CHRONICLES
                        </h1>
                        <p class="text-xs text-gray-400">Rich Text Publishing, Technical AI Insights & Articles</p>
                    </div>
                </div>
            </div>

            <!-- RICH TEXT POST PUBLISHER FORM -->
            @auth
                <div class="blog-card p-6 rounded-3xl backdrop-blur-xl space-y-4">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        📝 Publish New Article
                    </h2>

                    <form id="blog-form" action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <input type="text" name="title" required placeholder="Article Title..."
                                class="w-full bg-black/40 border border-white/15 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500">
                        </div>

                        <!-- Rich Text Editor Toolbar -->
                        <div class="rich-editor-toolbar flex flex-wrap gap-1.5 p-2 bg-black/50 border border-white/10 rounded-xl">
                            <button type="button" class="rich-tool-btn" data-cmd="bold"><b>B</b></button>
                            <button type="button" class="rich-tool-btn" data-cmd="italic"><i>I</i></button>
                            <button type="button" class="rich-tool-btn" data-cmd="underline"><u>U</u></button>
                            <button type="button" class="rich-tool-btn" data-cmd="formatBlock" data-val="h2">H2</button>
                            <button type="button" class="rich-tool-btn" data-cmd="formatBlock" data-val="h3">H3</button>
                            <button type="button" class="rich-tool-btn" data-cmd="insertUnorderedList">• Bullet List</button>
                            <button type="button" class="rich-tool-btn" data-cmd="createLink">🔗 Link</button>
                        </div>

                        <!-- Content Editable Area -->
                        <div id="rich-editor-area" contenteditable="true"
                            class="w-full min-h-[140px] bg-black/40 border border-white/15 rounded-xl p-4 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 overflow-y-auto">
                            Write your rich text article here...
                        </div>
                        <input type="hidden" name="content" id="blog-content-input">

                        <div class="flex items-center justify-between pt-2">
                            <input type="file" name="cover_image" class="text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/30 file:text-indigo-300 hover:file:bg-indigo-600/50">
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-xs rounded-xl shadow-lg hover:opacity-90 active:scale-95 transition-all">
                                PUBLISH ARTICLE
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="p-4 bg-indigo-950/40 border border-indigo-500/30 rounded-2xl text-xs text-indigo-300 flex items-center justify-between">
                    <span>Sign in to publish your rich text blog posts and technical research.</span>
                    <a href="{{ route('login') }}" class="px-4 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold">Sign In</a>
                </div>
            @endauth

            <!-- PUBLISHED ARTICLES LIST -->
            <div class="space-y-6">
                @forelse($posts as $post)
                    <article class="blog-card p-6 rounded-3xl flex flex-col md:flex-row gap-6 items-start">
                        @if($post->cover_image)
                            <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full md:w-48 h-32 object-cover rounded-2xl border border-white/10">
                        @endif
                        <div class="flex-1 space-y-2">
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] uppercase font-mono px-2.5 py-0.5 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Recent' }}
                                </span>
                                <span class="text-xs text-gray-400">by <strong class="text-white">{{ $post->author->name ?? 'Parsa Besharat' }}</strong></span>
                            </div>
                            <h2 class="text-lg font-bold text-white tracking-wide">{{ $post->title }}</h2>
                            <div class="blog-content text-xs text-slate-300 line-clamp-3">
                                {!! $post->content !!}
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="blog-card p-8 rounded-3xl text-center text-gray-400 text-xs">
                        No articles published yet. Be the first to share research insights!
                    </div>
                @endforelse
            </div>

        </main>

    </div>

    <!-- External Blog ESM Script -->
    <script type="module" src="{{ asset('js/blog.js') }}"></script>
</body>
</html>
