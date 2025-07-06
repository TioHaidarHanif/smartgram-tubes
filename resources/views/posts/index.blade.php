@extends('layouts.app')

@section('title', 'All Posts')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="sidebar">
            <h5 class="mb-3">Quick Actions</h5>
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('posts.create') }}">
                    <i class="fas fa-plus me-2"></i>Create Post
                </a>
                <a class="nav-link" href="{{ route('posts.my-posts') }}">
                    <i class="fas fa-edit me-2"></i>My Posts
                </a>
                <a class="nav-link" href="{{ route('forum.index') }}">
                    <i class="fas fa-comments me-2"></i>Forum
                </a>
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            </nav>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>All Posts</h2>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create Post
            </a>
        </div>

        @if($posts->count() > 0)
            <div class="row">
                @foreach($posts as $post)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        @if($post->type === 'image' && $post->media_urls)
                            <img src="{{ $post->media_urls[0] }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $post->user->avatar ?? 'https://via.placeholder.com/32' }}" 
                                     class="rounded-circle me-2" width="32" height="32" alt="Avatar">
                                <div>
                                    <h6 class="mb-0">{{ $post->user->name }}</h6>
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($post->content, 100) }}</p>
                            
                            @if($post->category)
                                <span class="badge bg-secondary mb-2">{{ $post->category->name }}</span>
                            @endif
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-3">
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
                                    <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-outline-primary">
                                        Read More
                                    </a>
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
                <p class="text-muted">Be the first to share your knowledge!</p>
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Your First Post
                </a>
            </div>
        @endif
    </div>
</div>
@endsection