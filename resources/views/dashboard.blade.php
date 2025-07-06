@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Sidebar -->
    <div class="col-lg-3 col-md-4">
        <div class="sidebar">
            <div class="d-flex align-items-center mb-4">
                <img src="{{ auth()->user()->avatar ?? 'https://via.placeholder.com/50' }}" 
                     class="rounded-circle me-3" width="50" height="50" alt="Avatar">
                <div>
                    <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                    <small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                </div>
            </div>

            <nav class="nav flex-column">
                <a class="nav-link active" href="{{ route('dashboard') }}">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
                <a class="nav-link" href="{{ route('posts.my-posts') }}">
                    <i class="fas fa-edit me-2"></i>My Posts
                </a>
                <a class="nav-link" href="{{ route('posts.create') }}">
                    <i class="fas fa-plus me-2"></i>Create Post
                </a>
                <a class="nav-link" href="{{ route('notifications.index') }}">
                    <i class="fas fa-bell me-2"></i>Notifications
                </a>
                <a class="nav-link" href="{{ route('profile', auth()->user()->username) }}">
                    <i class="fas fa-user me-2"></i>Profile
                </a>
                <a class="nav-link" href="{{ route('help.index') }}">
                    <i class="fas fa-question-circle me-2"></i>Help
                </a>
            </nav>

            <!-- Quick Stats -->
            <div class="mt-4 pt-4 border-top">
                <h6 class="text-muted mb-3">Quick Stats</h6>
                <div class="d-flex justify-content-between mb-2">
                    <small>Posts</small>
                    <small class="fw-bold">{{ auth()->user()->posts()->count() }}</small>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small>Followers</small>
                    <small class="fw-bold">{{ auth()->user()->followers()->count() }}</small>
                </div>
                <div class="d-flex justify-content-between">
                    <small>Following</small>
                    <small class="fw-bold">{{ auth()->user()->following()->count() }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-9 col-md-8">
        @if($needsTutorial)
        <!-- Tutorial Notice -->
        <div class="card border-primary mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-1">Welcome to Smartgram!</h5>
                        <p class="card-text mb-2">Take a quick tour to learn how to use our platform effectively.</p>
                        <button class="btn btn-primary btn-sm" onclick="startTutorial()">
                            <i class="fas fa-play me-1"></i>Start Tutorial
                        </button>
                        <button class="btn btn-outline-secondary btn-sm ms-2" onclick="skipTutorial()">
                            Skip for now
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Create Post Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <img src="{{ auth()->user()->avatar ?? 'https://via.placeholder.com/40' }}" 
                         class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                    <div class="flex-grow-1">
                        <a href="{{ route('posts.create') }}" class="form-control text-decoration-none" 
                           style="background: #f8f9fa; border: 1px solid #dee2e6; cursor: pointer;">
                            What would you like to share today?
                        </a>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('posts.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>Create Post
                    </a>
                    <a href="{{ route('forum.create') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-comments me-1"></i>Start Discussion
                    </a>
                </div>
            </div>
        </div>

        <!-- Posts Feed -->
        @if($posts->count() > 0)
            @foreach($posts as $post)
            <div class="post-card">
                <div class="post-header">
                    <img src="{{ $post->user->avatar ?? 'https://via.placeholder.com/40' }}" 
                         class="post-avatar" alt="Avatar">
                    <div class="flex-grow-1">
                        <h6 class="mb-0">
                            <a href="{{ route('profile', $post->user->username) }}" class="text-decoration-none">
                                {{ $post->user->name }}
                            </a>
                        </h6>
                        <small class="text-muted">
                            {{ $post->created_at->diffForHumans() }} • 
                            <span class="badge bg-light text-dark">{{ $post->category->name }}</span>
                        </small>
                    </div>
                    @if($post->user_id === auth()->id())
                    <div class="dropdown">
                        <button class="btn btn-sm" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="deletePost({{ $post->id }})">
                                <i class="fas fa-trash me-2"></i>Delete
                            </a></li>
                        </ul>
                    </div>
                    @endif
                </div>

                <div class="post-content">
                    <h5><a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none">{{ $post->title }}</a></h5>
                    <p class="mb-3">{{ Str::limit(strip_tags($post->content), 200) }}</p>
                    
                    @if($post->media_urls && count($post->media_urls) > 0)
                    <div class="mb-3">
                        <img src="{{ $post->media_urls[0] }}" class="img-fluid rounded" alt="Post media" style="max-height: 300px;">
                    </div>
                    @endif
                </div>

                <div class="post-actions">
                    <a href="#" class="post-action" onclick="toggleLike('post', {{ $post->id }})">
                        <i class="fas fa-heart"></i>
                        <span id="likes-count-{{ $post->id }}">{{ $post->likesCount() }}</span>
                    </a>
                    <a href="{{ route('posts.show', $post->id) }}" class="post-action">
                        <i class="fas fa-comment"></i>
                        {{ $post->commentsCount() }}
                    </a>
                    <a href="#" class="post-action">
                        <i class="fas fa-share"></i>
                        Share
                    </a>
                    <a href="{{ route('posts.show', $post->id) }}" class="post-action ms-auto">
                        <i class="fas fa-eye"></i>
                        {{ $post->views_count }} views
                    </a>
                </div>
            </div>
            @endforeach

            <div class="d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5>No posts in your feed yet</h5>
                <p class="text-muted mb-4">Follow other users to see their posts in your feed, or create your first post!</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Post
                    </a>
                    <a href="{{ route('search') }}" class="btn btn-outline-primary">
                        <i class="fas fa-search me-2"></i>Find Users
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleLike(type, id) {
    fetch('/likes/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ type: type, id: id })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById(`likes-count-${id}`).textContent = data.likes_count;
    })
    .catch(error => console.error('Error:', error));
}

function deletePost(id) {
    if (confirm('Are you sure you want to delete this post?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/posts/${id}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}

@if($needsTutorial)
function startTutorial() {
    window.location.href = '{{ route("tutorial.index") }}';
}

function skipTutorial() {
    // Mark all tutorial steps as skipped
    fetch('/tutorial/skip', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ step_id: 'all' })
    })
    .then(() => {
        location.reload();
    });
}
@endif
</script>
@endpush
@endsection