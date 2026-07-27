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
            $coverPath = '/storage/' . $request->file('cover_image')->store('blog_covers', 'public');
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

        return redirect()->route('blog')->with('success', 'Blog article published successfully!');
    }
}
