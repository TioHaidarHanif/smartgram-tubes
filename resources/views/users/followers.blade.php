@extends('layouts.app')

@section('title', $user->name . ' - Followers')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Profile Summary -->
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $user->avatar ?? 'https://via.placeholder.com/80' }}" 
                     class="rounded-circle mb-3" width="80" height="80" alt="Avatar">
                
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-3">@{{ $user->username }}</p>
                
                <div class="d-flex justify-content-center gap-4">
                    <a href="{{ route('profile', $user->username) }}" class="text-decoration-none">
                        <div class="text-center">
                            <div class="fw-bold">{{ $user->posts()->where('is_published', true)->count() }}</div>
                            <small class="text-muted">Posts</small>
                        </div>
                    </a>
                    <a href="{{ route('profile.followers', $user->username) }}" class="text-decoration-none text-primary">
                        <div class="text-center">
                            <div class="fw-bold">{{ $user->followers()->count() }}</div>
                            <small>Followers</small>
                        </div>
                    </a>
                    <a href="{{ route('profile.following', $user->username) }}" class="text-decoration-none">
                        <div class="text-center">
                            <div class="fw-bold">{{ $user->following()->count() }}</div>
                            <small class="text-muted">Following</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Profile Navigation</h6>
                <nav class="nav flex-column">
                    <a class="nav-link" href="{{ route('profile', $user->username) }}">
                        <i class="fas fa-user me-2"></i>Profile
                    </a>
                    <a class="nav-link active" href="{{ route('profile.followers', $user->username) }}">
                        <i class="fas fa-users me-2"></i>Followers
                    </a>
                    <a class="nav-link" href="{{ route('profile.following', $user->username) }}">
                        <i class="fas fa-user-friends me-2"></i>Following
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $user->name }}'s Followers</h5>
            </div>
            <div class="card-body">
                @if($followers->count() > 0)
                    <div class="row">
                        @foreach($followers as $follower)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img src="{{ $follower->avatar ?? 'https://via.placeholder.com/60' }}" 
                                             class="rounded-circle mb-3" width="60" height="60" alt="Avatar">
                                        
                                        <h6 class="mb-1">
                                            <a href="{{ route('profile', $follower->username) }}" class="text-decoration-none">
                                                {{ $follower->name }}
                                            </a>
                                        </h6>
                                        <p class="text-muted mb-2">@{{ $follower->username }}</p>
                                        
                                        @if($follower->bio)
                                            <p class="text-muted small mb-3">{{ Str::limit($follower->bio, 50) }}</p>
                                        @endif
                                        
                                        <div class="d-flex justify-content-center gap-3 mb-3">
                                            <small class="text-muted">
                                                <strong>{{ $follower->posts()->where('is_published', true)->count() }}</strong> posts
                                            </small>
                                            <small class="text-muted">
                                                <strong>{{ $follower->followers()->count() }}</strong> followers
                                            </small>
                                        </div>
                                        
                                        @auth
                                            @if($follower->id !== auth()->id())
                                                <button class="btn btn-sm {{ auth()->user()->isFollowing($follower) ? 'btn-outline-primary' : 'btn-primary' }}" 
                                                        onclick="toggleFollow({{ $follower->id }}, this)">
                                                    <i class="fas {{ auth()->user()->isFollowing($follower) ? 'fa-user-minus' : 'fa-user-plus' }} me-1"></i>
                                                    {{ auth()->user()->isFollowing($follower) ? 'Unfollow' : 'Follow' }}
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

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $followers->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h4>No followers yet</h4>
                        <p class="text-muted">
                            @if($user->id === auth()->id())
                                Start creating content and engaging with the community to gain followers!
                            @else
                                {{ $user->name }} doesn't have any followers yet.
                            @endif
                        </p>
                        @if($user->id === auth()->id())
                            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Your First Post
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
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
        
        const icon = button.querySelector('i');
        
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