<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ForumController extends Controller
{
    public function index()
    {
        $forumPosts = ForumPost::with(['user', 'category'])
            ->withCount('comments')
            ->orderBy('is_sticky', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $categories = Category::where('is_active', true)
            ->withCount('forumPosts')
            ->orderBy('name')
            ->get();

        return view('forum.index', compact('forumPosts', 'categories'));
    }

    public function show($id)
    {
        $forumPost = ForumPost::with(['user', 'category', 'comments.user', 'comments.replies.user'])
            ->findOrFail($id);

        // Increment views
        $forumPost->incrementViews();

        return view('forum.show', compact('forumPost'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('forum.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $forumPost = ForumPost::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('forum.show', $forumPost->id)->with('success', 'Forum post created successfully!');
    }

    public function edit($id)
    {
        $forumPost = ForumPost::findOrFail($id);
        
        if ($forumPost->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('forum.edit', compact('forumPost', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $forumPost = ForumPost::findOrFail($id);
        
        if ($forumPost->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $forumPost->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('forum.show', $forumPost->id)->with('success', 'Forum post updated successfully!');
    }

    public function destroy($id)
    {
        $forumPost = ForumPost::findOrFail($id);
        
        if ($forumPost->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $forumPost->delete();

        return redirect()->route('forum.index')->with('success', 'Forum post deleted successfully!');
    }
}
