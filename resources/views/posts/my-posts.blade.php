@extends('layouts.app')

@section('title', 'My Posts')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="sidebar">
            <h5 class="mb-3">My Content</h5>
            <nav class="nav flex-column">
                <a class="nav-link active" href="{{ route('posts.my-posts') }}">
                    <i class="fas fa-edit me-2"></i>My Posts
                </a>
                <a class="nav-link" href="{{ route('posts.create') }}">
                    <i class="fas fa-plus me-2"></i>Create New Post
                </a>
                <a class="nav-link" href="{{ route('posts.index') }}">
                    <i class="fas fa-list me-2"></i>All Posts
                </a>
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            </nav>

            <!-- Quick Stats -->
            <div class="mt-4 pt-4 border-top">
                <h6 class="text-muted mb-3">Quick Stats</h6>
                <div class="d-flex justify-content-between mb-2">
                    <small>Total Posts</small>
                    <small class="fw-bold">{{ $posts->total() }}</small>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small>Published</small>
                    <small class="fw-bold">{{ $posts->where('is_published', true)->count() }}</small>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small>Drafts</small>
                    <small class="fw-bold">{{ $posts->where('is_published', false)->count() }}</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Posts</h2>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create New Post
            </a>
        </div>

        @if($posts->count() > 0)
            <div class="row">
                @foreach($posts as $post)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        @if(!$post->is_published)
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-warning">Draft</span>
                            </div>
                        @endif
                        
                        @if($post->type === 'image' && $post->media_urls)
                            <img src="{{ $post->media_urls[0] }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($post->content, 100) }}</p>
                            
                            @if($post->category)
                                <span class="badge bg-secondary mb-2">{{ $post->category->name }}</span>
                            @endif
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                    <div class="d-flex gap-2">
                                        <small class="text-muted">
                                            <i class="fas fa-heart"></i> {{ $post->likes->count() }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-comment"></i> {{ $post->comments->count() }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-eye"></i> {{ $post->views ?? 0 }}
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a></li>
                                            @if($post->is_published)
                                                <li><a class="dropdown-item" href="{{ route('posts.show', $post->id) }}">
                                                    <i class="fas fa-eye me-2"></i>View
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="sharePost({{ $post->id }})">
                                                    <i class="fas fa-share me-2"></i>Share
                                                </a></li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('posts.destroy', $post->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" 
                                                            onclick="return confirm('Are you sure you want to delete this post?')">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-edit fa-3x text-muted mb-3"></i>
                <h4>No posts yet</h4>
                <p class="text-muted">Start sharing your knowledge with the community!</p>
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Your First Post
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function sharePost(postId) {
    const url = '{{ url("/posts") }}/' + postId;
    if (navigator.share) {
        navigator.share({
            title: 'Check out this post on Smartgram',
            url: url
        });
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(url).then(function() {
            alert('Post link copied to clipboard!');
        });
    }
}
</script>
@endsection