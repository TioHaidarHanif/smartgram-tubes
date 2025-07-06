<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Request $request)
    {
        $userId = $request->input('user_id');
        $userToFollow = User::findOrFail($userId);

        if ($userToFollow->id === Auth::id()) {
            return response()->json(['error' => 'You cannot follow yourself'], 400);
        }

        $follow = Follow::where('follower_id', Auth::id())
            ->where('following_id', $userToFollow->id)
            ->first();

        if ($follow) {
            // Unfollow
            $follow->delete();
            $following = false;
        } else {
            // Follow
            Follow::create([
                'follower_id' => Auth::id(),
                'following_id' => $userToFollow->id,
            ]);
            $following = true;

            // Create notification
            Notification::create([
                'user_id' => $userToFollow->id,
                'type' => 'follow',
                'title' => 'New Follower',
                'message' => Auth::user()->name . ' started following you',
                'data' => [
                    'follower_id' => Auth::id(),
                    'follower_name' => Auth::user()->name,
                ],
            ]);
        }

        $followersCount = $userToFollow->followers()->count();

        return response()->json([
            'following' => $following,
            'followers_count' => $followersCount
        ]);
    }

    public function followers($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $followers = $user->followers()->paginate(20);

        return view('users.followers', compact('user', 'followers'));
    }

    public function following($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $following = $user->following()->paginate(20);

        return view('users.following', compact('user', 'following'));
    }

    public function suggestions()
    {
        // Get users that the current user is not following
        $followingIds = Auth::user()->following()->pluck('users.id')->toArray();
        $followingIds[] = Auth::id(); // Exclude self

        $suggestions = User::whereNotIn('id', $followingIds)
            ->where('is_active', true)
            ->withCount('followers')
            ->orderBy('followers_count', 'desc')
            ->take(10)
            ->get();

        return response()->json(['suggestions' => $suggestions]);
    }
}
