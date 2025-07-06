<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Request $request)
    {
        $type = $request->input('type'); // 'post' or 'comment'
        $id = $request->input('id');

        if ($type === 'post') {
            $model = Post::findOrFail($id);
        } elseif ($type === 'comment') {
            $model = Comment::findOrFail($id);
        } else {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        $like = Like::where('user_id', Auth::id())
            ->where('likeable_type', get_class($model))
            ->where('likeable_id', $model->id)
            ->first();

        if ($like) {
            // Unlike
            $like->delete();
            $liked = false;
        } else {
            // Like
            Like::create([
                'user_id' => Auth::id(),
                'likeable_type' => get_class($model),
                'likeable_id' => $model->id,
            ]);
            $liked = true;
        }

        $likesCount = $model->likes()->count();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount
        ]);
    }

    public function getLikes(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        if ($type === 'post') {
            $model = Post::findOrFail($id);
        } elseif ($type === 'comment') {
            $model = Comment::findOrFail($id);
        } else {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        $likes = $model->likes()->with('user')->get();

        return response()->json([
            'likes' => $likes,
            'count' => $likes->count()
        ]);
    }
}
