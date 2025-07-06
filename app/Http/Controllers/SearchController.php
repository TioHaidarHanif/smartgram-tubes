<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $category = $request->get('category');
        $type = $request->get('type', 'all'); // all, posts, users, categories

        $results = [];

        if ($query) {
            if ($type === 'all' || $type === 'posts') {
                $results['posts'] = $this->searchPosts($query, $category);
            }

            if ($type === 'all' || $type === 'users') {
                $results['users'] = $this->searchUsers($query);
            }

            if ($type === 'all' || $type === 'categories') {
                $results['categories'] = $this->searchCategories($query);
            }
        }

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('search.index', compact('results', 'query', 'category', 'type', 'categories'));
    }

    private function searchPosts($query, $categoryId = null)
    {
        $posts = Post::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->with(['user', 'category', 'comments', 'likes']);

        if ($categoryId) {
            $posts->where('category_id', $categoryId);
        }

        return $posts->orderBy('created_at', 'desc')->paginate(12);
    }

    private function searchUsers($query)
    {
        return User::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('username', 'LIKE', "%{$query}%")
                  ->orWhere('bio', 'LIKE', "%{$query}%");
            })
            ->withCount(['posts', 'followers', 'following'])
            ->orderBy('name')
            ->paginate(12);
    }

    private function searchCategories($query)
    {
        return Category::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->withCount('posts')
            ->orderBy('name')
            ->paginate(12);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all');

        $results = [];

        if ($query) {
            if ($type === 'all' || $type === 'posts') {
                $posts = Post::where('is_published', true)
                    ->where('title', 'LIKE', "%{$query}%")
                    ->select('id', 'title')
                    ->take(5)
                    ->get();
                
                $results['posts'] = $posts;
            }

            if ($type === 'all' || $type === 'users') {
                $users = User::where('is_active', true)
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%")
                          ->orWhere('username', 'LIKE', "%{$query}%");
                    })
                    ->select('id', 'name', 'username', 'avatar')
                    ->take(5)
                    ->get();
                
                $results['users'] = $users;
            }
        }

        return response()->json($results);
    }
}
