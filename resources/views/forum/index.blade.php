@extends('layouts.app')

@section('title', 'Community Forum')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Forum Categories</h5>
            </div>
            <div class="card-body">
                <nav class="nav flex-column">
                    <a class="nav-link {{ !request('category') ? 'active' : '' }}" href="{{ route('forum.index') }}">
                        <i class="fas fa-list me-2"></i>All Discussions
                    </a>
                    @foreach($categories as $category)
                        <a class="nav-link {{ request('category') == $category->id ? 'active' : '' }}" 
                           href="{{ route('forum.index', ['category' => $category->id]) }}">
                            <i class="fas fa-tag me-2"></i>{{ $category->name }}
                            <span class="badge bg-secondary ms-auto">{{ $category->forum_posts_count }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        @auth
            <div class="card mt-4">
                <div class="card-body text-center">
                    <h6 class="card-title">Start a Discussion</h6>
                    <p class="card-text text-muted">Share your knowledge and connect with the community.</p>
                    <a href="{{ route('forum.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Discussion
                    </a>
                </div>
            </div>
        @endauth

        <!-- Forum Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Forum Statistics</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <small>Total Discussions</small>
                    <small class="fw-bold">{{ $forumPosts->total() }}</small>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small>Active Categories</small>
                    <small class="fw-bold">{{ $categories->count() }}</small>
                </div>
                <div class="d-flex justify-content-between">
                    <small>Total Members</small>
                    <small class="fw-bold">{{ \App\Models\User::where('is_active', true)->count() }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-comments me-2"></i>Community Forum
            </h2>
            @auth
                <a href="{{ route('forum.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Start Discussion
                </a>
            @endauth
        </div>

        <!-- Forum Rules/Guidelines -->
        <div class="alert alert-info mb-4">
            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Forum Guidelines</h6>
            <ul class="mb-0">
                <li>Be respectful and constructive in your discussions</li>
                <li>Stay on topic and use appropriate categories</li>
                <li>Search before posting to avoid duplicates</li>
                <li>Help others by sharing your knowledge and experience</li>
            </ul>
        </div>

        @if($forumPosts->count() > 0)
            <!-- Sticky Posts -->
            @foreach($forumPosts as $post)
                @if($post->is_sticky)
                    <div class="card mb-3 border-warning">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-thumbtack text-warning me-2"></i>
                                        <span class="badge bg-warning text-dark me-2">Pinned</span>
                                        @if($post->category)
                                            <span class="badge bg-secondary me-2">{{ $post->category->name }}</span>
                                        @endif
                                    </div>
                                    <h5 class="mb-2">
                                        <a href="{{ route('forum.show', $post->id) }}" class="text-decoration-none">
                                            {{ $post->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-2">{{ Str::limit($post->content, 150) }}</p>
                                    <div class="d-flex align-items-center text-muted">
                                        <img src="{{ $post->user->avatar ?? 'https://via.placeholder.com/24' }}" 
                                             class="rounded-circle me-2" width="24" height="24" alt="Avatar">
                                        <small>
                                            by <a href="{{ route('profile', $post->user->username) }}" class="text-decoration-none">{{ $post->user->name }}</a>
                                            • {{ $post->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <div class="text-center" style="min-width: 80px;">
                                    <div class="fw-bold">{{ $post->comments_count }}</div>
                                    <small class="text-muted">replies</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Regular Posts -->
            @foreach($forumPosts as $post)
                @if(!$post->is_sticky)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        @if($post->category)
                                            <span class="badge bg-secondary me-2">{{ $post->category->name }}</span>
                                        @endif
                                        @if($post->created_at->diffInHours() < 24)
                                            <span class="badge bg-success me-2">New</span>
                                        @endif
                                    </div>
                                    <h5 class="mb-2">
                                        <a href="{{ route('forum.show', $post->id) }}" class="text-decoration-none">
                                            {{ $post->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-2">{{ Str::limit($post->content, 150) }}</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center text-muted">
                                            <img src="{{ $post->user->avatar ?? 'https://via.placeholder.com/24' }}" 
                                                 class="rounded-circle me-2" width="24" height="24" alt="Avatar">
                                            <small>
                                                by <a href="{{ route('profile', $post->user->username) }}" class="text-decoration-none">{{ $post->user->name }}</a>
                                                • {{ $post->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="d-flex gap-3">
                                            <small class="text-muted">
                                                <i class="fas fa-eye"></i> {{ $post->views ?? 0 }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-heart"></i> {{ $post->likes ?? 0 }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center" style="min-width: 80px;">
                                    <div class="fw-bold">{{ $post->comments_count }}</div>
                                    <small class="text-muted">replies</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $forumPosts->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <h4>No discussions yet</h4>
                <p class="text-muted">Be the first to start a conversation in the community forum!</p>
                @auth
                    <a href="{{ route('forum.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Start First Discussion
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Login to Participate
                    </a>
                @endauth
            </div>
        @endif
    </div>
</div>
@endsection