@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Search Filters</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('search') }}" method="GET">
                    <div class="mb-3">
                        <label for="q" class="form-label">Search Query</label>
                        <input type="text" class="form-control" id="q" name="q" value="{{ $query }}" placeholder="Enter search terms...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Search Type</label>
                        <select class="form-select" id="type" name="type">
                            <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All</option>
                            <option value="posts" {{ $type === 'posts' ? 'selected' : '' }}>Posts</option>
                            <option value="users" {{ $type === 'users' ? 'selected' : '' }}>Users</option>
                            <option value="categories" {{ $type === 'categories' ? 'selected' : '' }}>Categories</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </form>
            </div>
        </div>

        @if($query)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Search Results Summary</h6>
                </div>
                <div class="card-body">
                    @if(isset($results['posts']))
                        <div class="d-flex justify-content-between mb-2">
                            <small>Posts</small>
                            <small class="fw-bold">{{ $results['posts']->total() }}</small>
                        </div>
                    @endif
                    @if(isset($results['users']))
                        <div class="d-flex justify-content-between mb-2">
                            <small>Users</small>
                            <small class="fw-bold">{{ $results['users']->total() }}</small>
                        </div>
                    @endif
                    @if(isset($results['categories']))
                        <div class="d-flex justify-content-between mb-2">
                            <small>Categories</small>
                            <small class="fw-bold">{{ $results['categories']->total() }}</small>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-9">
        @if($query)
            <div class="mb-4">
                <h3>Search Results for "{{ $query }}"</h3>
                <p class="text-muted">
                    @if($type === 'all')
                        Showing results for all types
                    @else
                        Showing results for {{ $type }}
                    @endif
                    @if($category)
                        in category: {{ $categories->where('id', $category)->first()->name ?? 'Unknown' }}
                    @endif
                </p>
            </div>

            <!-- Posts Results -->
            @if(isset($results['posts']) && $results['posts']->count() > 0)
                <div class="mb-5">
                    <h4 class="mb-3">
                        <i class="fas fa-edit me-2"></i>Posts
                        <span class="badge bg-secondary">{{ $results['posts']->total() }}</span>
                    </h4>
                    <div class="row">
                        @foreach($results['posts'] as $post)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    @if($post->type === 'image' && $post->media_urls)
                                        <img src="{{ $post->media_urls[0] }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                                    @endif
                                    
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <img src="{{ $post->user->avatar ?? 'https://via.placeholder.com/24' }}" 
                                                 class="rounded-circle me-2" width="24" height="24" alt="Avatar">
                                            <div>
                                                <small class="text-muted">{{ $post->user->name }}</small>
                                                <small class="text-muted">• {{ $post->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        
                                        <h5 class="card-title">{{ $post->title }}</h5>
                                        <p class="card-text text-muted">{{ Str::limit($post->content, 100) }}</p>
                                        
                                        @if($post->category)
                                            <span class="badge bg-secondary mb-2">{{ $post->category->name }}</span>
                                        @endif
                                        
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="d-flex gap-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-heart"></i> {{ $post->likes->count() }}
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="fas fa-comment"></i> {{ $post->comments->count() }}
                                                    </small>
                                                </div>
                                            </div>
                                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-outline-primary">
                                                Read More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $results['posts']->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif

            <!-- Users Results -->
            @if(isset($results['users']) && $results['users']->count() > 0)
                <div class="mb-5">
                    <h4 class="mb-3">
                        <i class="fas fa-users me-2"></i>Users
                        <span class="badge bg-secondary">{{ $results['users']->total() }}</span>
                    </h4>
                    <div class="row">
                        @foreach($results['users'] as $user)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img src="{{ $user->avatar ?? 'https://via.placeholder.com/60' }}" 
                                             class="rounded-circle mb-3" width="60" height="60" alt="Avatar">
                                        
                                        <h6 class="mb-1">
                                            <a href="{{ route('profile', $user->username) }}" class="text-decoration-none">
                                                {{ $user->name }}
                                            </a>
                                        </h6>
                                        <p class="text-muted mb-2">@{{ $user->username }}</p>
                                        
                                        @if($user->bio)
                                            <p class="text-muted small mb-3">{{ Str::limit($user->bio, 60) }}</p>
                                        @endif
                                        
                                        <div class="d-flex justify-content-center gap-3 mb-3">
                                            <small class="text-muted">
                                                <strong>{{ $user->posts_count }}</strong> posts
                                            </small>
                                            <small class="text-muted">
                                                <strong>{{ $user->followers_count }}</strong> followers
                                            </small>
                                        </div>
                                        
                                        @auth
                                            @if($user->id !== auth()->id())
                                                <button class="btn btn-sm {{ auth()->user()->isFollowing($user) ? 'btn-outline-primary' : 'btn-primary' }}" 
                                                        onclick="toggleFollow({{ $user->id }}, this)">
                                                    <i class="fas {{ auth()->user()->isFollowing($user) ? 'fa-user-minus' : 'fa-user-plus' }} me-1"></i>
                                                    {{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-user-plus me-1"></i>Follow
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $results['users']->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif

            <!-- Categories Results -->
            @if(isset($results['categories']) && $results['categories']->count() > 0)
                <div class="mb-5">
                    <h4 class="mb-3">
                        <i class="fas fa-tags me-2"></i>Categories
                        <span class="badge bg-secondary">{{ $results['categories']->total() }}</span>
                    </h4>
                    <div class="row">
                        @foreach($results['categories'] as $category)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $category->name }}</h5>
                                        @if($category->description)
                                            <p class="card-text text-muted">{{ $category->description }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-edit"></i> {{ $category->posts_count }} posts
                                            </small>
                                            <a href="{{ route('search', ['category' => $category->id, 'type' => 'posts']) }}" class="btn btn-sm btn-outline-primary">
                                                View Posts
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $results['categories']->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif

            <!-- No Results -->
            @if((!isset($results['posts']) || $results['posts']->count() === 0) && 
                (!isset($results['users']) || $results['users']->count() === 0) && 
                (!isset($results['categories']) || $results['categories']->count() === 0))
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No results found</h4>
                    <p class="text-muted">Try adjusting your search terms or filters.</p>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4>Search Smartgram</h4>
                <p class="text-muted">Find posts, users, and categories that match your interests.</p>
                <div class="mt-4">
                    <p class="text-muted mb-2">Popular searches:</p>
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <a href="{{ route('search', ['q' => 'programming']) }}" class="btn btn-sm btn-outline-primary">Programming</a>
                        <a href="{{ route('search', ['q' => 'education']) }}" class="btn btn-sm btn-outline-primary">Education</a>
                        <a href="{{ route('search', ['q' => 'tutorial']) }}" class="btn btn-sm btn-outline-primary">Tutorial</a>
                        <a href="{{ route('search', ['q' => 'tips']) }}" class="btn btn-sm btn-outline-primary">Tips</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function toggleFollow(userId, button) {
    fetch('/follow/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        
        if (data.following) {
            button.className = 'btn btn-sm btn-outline-primary';
            button.innerHTML = '<i class="fas fa-user-minus me-1"></i>Unfollow';
        } else {
            button.className = 'btn btn-sm btn-primary';
            button.innerHTML = '<i class="fas fa-user-plus me-1"></i>Follow';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>
@endsection