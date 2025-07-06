<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $posts = Post::with(['user', 'category', 'comments', 'likes'])
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('posts.index', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::with(['user', 'category', 'comments.user', 'comments.replies.user', 'likes'])
            ->findOrFail($id);

        if (!$post->is_published && $post->user_id !== Auth::id()) {
            abort(404);
        }

        // Increment views
        $post->incrementViews();

        return view('posts.show', compact('post'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:text,image,video,document',
            'media.*' => 'nullable|file|max:10240', // 10MB max
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $mediaUrls = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('posts', 'public');
                $mediaUrls[] = Storage::url($path);
            }
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'type' => $request->type,
            'media_urls' => $mediaUrls,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('posts.show', $post->id)->with('success', 'Post created successfully!');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:text,image,video,document',
            'media.*' => 'nullable|file|max:10240',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $mediaUrls = $post->media_urls ?? [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('posts', 'public');
                $mediaUrls[] = Storage::url($path);
            }
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'type' => $request->type,
            'media_urls' => $mediaUrls,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('posts.show', $post->id)->with('success', 'Post updated successfully!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        if ($post->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }

    public function myPosts()
    {
        $posts = Post::where('user_id', Auth::id())
            ->with(['category', 'comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('posts.my-posts', compact('posts'));
    }
}
