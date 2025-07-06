<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $comment = Comment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $comment->update([
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Comment updated successfully!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    public function getReplies($id)
    {
        $comment = Comment::with(['replies.user', 'replies.likes'])->findOrFail($id);
        
        return response()->json([
            'replies' => $comment->replies
        ]);
    }
}
