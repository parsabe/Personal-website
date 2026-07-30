<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Render Rich Text Blog view.
     */
    public function index()
    {
        $posts = BlogPost::with('author')
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->get();

        return view('pages.blog', compact('posts'));
    }

    /**
     * Store new rich text blog post.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must log in to post articles.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $uploadDir = public_path('uploads/blog_covers');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'cover_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $coverPath = '/uploads/blog_covers/' . $filename;
        }

        $title = $request->input('title');
        $content = $request->input('content');

        $post = BlogPost::create([
            'author_id' => Auth::id(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'content' => $content,
            'cover_image' => $coverPath,
            'is_published' => true,
            'published_at' => now(),
        ]);

        // Generate Blade Template File inside resources/views/pages/publications/articles/
        $this->generateArticleBladeFile($post);

        return redirect()->route('blog')->with('success', 'Blog article published successfully!');
    }

    /**
     * Show standalone publication article view.
     */
    public function showArticle($slug)
    {
        $viewName = 'pages.publications.articles.' . $slug;
        if (view()->exists($viewName)) {
            return view($viewName);
        }

        $post = BlogPost::with('author')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('pages.blog_show', compact('post'));
    }

    /**
     * Generate Blade Template File in resources/views/pages/publications/articles/
     */
    private function generateArticleBladeFile(BlogPost $post)
    {
        $dir = resource_path('views/pages/publications/articles');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $filePath = $dir . '/' . $post->slug . '.blade.php';

        $bladeContent = '<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . e($post->title) . ' - Parsa Besharat Publications</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.tailwind = { config: { darkMode: "class" } };</script>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(["resources/css/app.css", "resources/js/app.js"])
    <link rel="icon" href="{{ asset("images/profile.jpg") }}">
    <link rel="stylesheet" href="{{ asset("css/blog.css") }}">
</head>
<body class="text-gray-800 dark:text-gray-100 antialiased flex items-center justify-center p-3 lg:p-8 min-h-screen relative overflow-x-hidden">
    <div id="main-container" class="ios-glass relative w-full max-w-6xl flex flex-col md:flex-row rounded-[2.5rem] overflow-hidden h-[88vh] z-10 transition-all duration-700 shadow-2xl border border-white/10 animate-page-zoom-in">
        @include("top-header-controls")
        @include("sidebar")
        <main class="flex-1 flex flex-col overflow-y-auto relative p-6 pt-12 lg:p-10 lg:pt-14 bg-black/40 gap-6 animate-page-slide-up">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <a href="/blog" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <span>← Back to Blog</span>
                </a>
                <span class="text-xs text-indigo-400 font-mono font-bold uppercase">Publication Article</span>
            </div>
            
            <article class="max-w-3xl mx-auto w-full space-y-6">
                ' . ($post->cover_image ? '<img src="' . asset($post->cover_image) . '" class="w-full max-h-72 object-cover rounded-3xl border border-white/15 shadow-2xl">' : '') . '
                <div>
                    <h1 class="text-2xl lg:text-3xl font-extrabold text-white tracking-wide leading-tight mb-2">' . e($post->title) . '</h1>
                    <div class="flex items-center gap-3 text-xs text-gray-400 font-mono border-b border-white/10 pb-4">
                        <span>Published by <strong class="text-white">' . e($post->author->name ?? 'Parsa Besharat') . '</strong></span>
                        <span>•</span>
                        <span>' . ($post->published_at ? $post->published_at->format('M d, Y') : 'Recent') . '</span>
                    </div>
                </div>
                
                <div class="blog-content text-sm text-slate-200 leading-relaxed font-sans space-y-4">
                    ' . $post->content . '
                </div>
            </article>
        </main>
    </div>
    @include("taskbar")
    <script src="{{ asset("js/mac-window-controls.js") }}"></script>
</body>
</html>';

        file_put_contents($filePath, $bladeContent);
    }

    /**
     * Get articles for logged in user JSON / response.
     */
    public function userArticles()
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $articles = BlogPost::where('author_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'articles' => $articles]);
    }

    /**
     * Update user's article.
     */
    public function updateArticle(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $post = BlogPost::where('id', $id)->where('author_id', Auth::id())->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();

        // Re-generate Blade file
        $this->generateArticleBladeFile($post);

        return response()->json(['status' => 'success', 'message' => 'Article updated successfully!', 'post' => $post]);
    }

    /**
     * Delete user's article (soft delete).
     */
    public function deleteArticle(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $post = BlogPost::where('id', $id)->where('author_id', Auth::id())->firstOrFail();
        $post->delete();

        return response()->json(['status' => 'success', 'message' => 'Article deleted successfully!']);
    }
}
