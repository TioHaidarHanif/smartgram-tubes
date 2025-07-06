<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\TutorialStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        
        $featuredPosts = Post::where('is_featured', true)
            ->where('is_published', true)
            ->with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(8)
            ->get();

        return view('welcome', compact('featuredPosts', 'categories'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Get posts from users the current user is following
        $followingIds = $user->following()->pluck('users.id')->toArray();
        $followingIds[] = $user->id; // Include own posts
        
        $posts = Post::whereIn('user_id', $followingIds)
            ->where('is_published', true)
            ->with(['user', 'category', 'comments.user', 'likes'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Check if user needs to see tutorials
        $needsTutorial = !$user->tutorialProgress()->exists();
        $tutorialSteps = collect();
        
        if ($needsTutorial) {
            $tutorialSteps = TutorialStep::active()
                ->ordered()
                ->get();
        }

        return view('dashboard', compact('posts', 'needsTutorial', 'tutorialSteps'));
    }

    public function profile($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        $posts = Post::where('user_id', $user->id)
            ->where('is_published', true)
            ->with(['category', 'comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();
        $postsCount = $user->posts()->where('is_published', true)->count();

        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = Auth::user()->isFollowing($user);
        }

        return view('profile', compact('user', 'posts', 'followersCount', 'followingCount', 'postsCount', 'isFollowing'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
