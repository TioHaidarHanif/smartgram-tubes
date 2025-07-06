@extends('layouts.app')

@section('title', $user->name . ' - Following')

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
                    <a href="{{ route('profile.followers', $user->username) }}" class="text-decoration-none">
                        <div class="text-center">
                            <div class="fw-bold">{{ $user->followers()->count() }}</div>
                            <small class="text-muted">Followers</small>
                        </div>
                    </a>
                    <a href="{{ route('profile.following', $user->username) }}" class="text-decoration-none text-primary">
                        <div class="text-center">
                            <div class="fw-bold">{{ $user->following()->count() }}</div>
                            <small>Following</small>
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
                    <a class="nav-link" href="{{ route('profile.followers', $user->username) }}">
                        <i class="fas fa-users me-2"></i>Followers
                    </a>
                    <a class="nav-link active" href="{{ route('profile.following', $user->username) }}">
                        <i class="fas fa-user-friends me-2"></i>Following
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $user->name }} is Following</h5>
            </div>
            <div class="card-body">
                @if($following->count() > 0)
                    <div class="row">
                        @foreach($following as $followedUser)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img src="{{ $followedUser->avatar ?? 'https://via.placeholder.com/60' }}" 
                                             class="rounded-circle mb-3" width="60" height="60" alt="Avatar">
                                        
                                        <h6 class="mb-1">
                                            <a href="{{ route('profile', $followedUser->username) }}" class="text-decoration-none">
                                                {{ $followedUser->name }}
                                            </a>
                                        </h6>
                                        <p class="text-muted mb-2">@{{ $followedUser->username }}</p>
                                        
                                        @if($followedUser->bio)
                                            <p class="text-muted small mb-3">{{ Str::limit($followedUser->bio, 50) }}</p>
                                        @endif
                                        
                                        <div class="d-flex justify-content-center gap-3 mb-3">
                                            <small class="text-muted">
                                                <strong>{{ $followedUser->posts()->where('is_published', true)->count() }}</strong> posts
                                            </small>
                                            <small class="text-muted">
                                                <strong>{{ $followedUser->followers()->count() }}</strong> followers
                                            </small>
                                        </div>
                                        
                                        @auth
                                            @if($followedUser->id !== auth()->id())
                                                <button class="btn btn-sm {{ auth()->user()->isFollowing($followedUser) ? 'btn-outline-primary' : 'btn-primary' }}" 
                                                        onclick="toggleFollow({{ $followedUser->id }}, this)">
                                                    <i class="fas {{ auth()->user()->isFollowing($followedUser) ? 'fa-user-minus' : 'fa-user-plus' }} me-1"></i>
                                                    {{ auth()->user()->isFollowing($followedUser) ? 'Unfollow' : 'Follow' }}
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
                        {{ $following->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                        <h4>Not following anyone yet</h4>
                        <p class="text-muted">
                            @if($user->id === auth()->id())
                                Discover and follow other users to see their content in your feed!
                            @else
                                {{ $user->name }} is not following anyone yet.
                            @endif
                        </p>
                        @if($user->id === auth()->id())
                            <a href="{{ route('search') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Discover Users
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