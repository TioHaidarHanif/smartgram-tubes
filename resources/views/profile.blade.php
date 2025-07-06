@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Profile Info -->
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $user->avatar ?? 'https://via.placeholder.com/120' }}" 
                     class="rounded-circle mb-3" width="120" height="120" alt="Avatar">
                
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">@{{ $user->username }}</p>
                
                @if($user->bio)
                    <p class="text-muted mb-3">{{ $user->bio }}</p>
                @endif
                
                @if($user->location)
                    <p class="text-muted mb-1">
                        <i class="fas fa-map-marker-alt me-2"></i>{{ $user->location }}
                    </p>
                @endif
                
                @if($user->website)
                    <p class="text-muted mb-3">
                        <i class="fas fa-link me-2"></i>
                        <a href="{{ $user->website }}" target="_blank" class="text-decoration-none">
                            {{ $user->website }}
                        </a>
                    </p>
                @endif
                
                <p class="text-muted mb-3">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Joined {{ $user->created_at->format('F Y') }}
                </p>

                <!-- Stats -->
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <div class="fw-bold">{{ $postsCount }}</div>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold">{{ $followersCount }}</div>
                        <small class="text-muted">
                            <a href="{{ route('profile.followers', $user->username) }}" class="text-decoration-none">
                                Followers
                            </a>
                        </small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold">{{ $followingCount }}</div>
                        <small class="text-muted">
                            <a href="{{ route('profile.following', $user->username) }}" class="text-decoration-none">
                                Following
                            </a>
                        </small>
                    </div>
                </div>

                <!-- Follow/Unfollow Button -->
                @auth
                    @if($user->id !== auth()->id())
                        <button id="followButton" class="btn {{ $isFollowing ? 'btn-outline-primary' : 'btn-primary' }} btn-sm" 
                                onclick="toggleFollow({{ $user->id }})">
                            <i class="fas {{ $isFollowing ? 'fa-user-minus' : 'fa-user-plus' }} me-2"></i>
                            <span id="followText">{{ $isFollowing ? 'Unfollow' : 'Follow' }}</span>
                        </button>
                    @else
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Activity Summary -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">Posts this month</small>
                    <small class="fw-bold">{{ $user->posts()->whereMonth('created_at', now()->month)->count() }}</small>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">Comments this month</small>
                    <small class="fw-bold">{{ $user->comments()->whereMonth('created_at', now()->month)->count() }}</small>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Likes received</small>
                    <small class="fw-bold">{{ $user->posts()->withCount('likes')->get()->sum('likes_count') }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab">
                    <i class="fas fa-edit me-2"></i>Posts ({{ $postsCount }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="likes-tab" data-bs-toggle="tab" data-bs-target="#likes" type="button" role="tab">
                    <i class="fas fa-heart me-2"></i>Liked Posts
                </button>
            </li>
            @if($user->id === auth()->id())
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="drafts-tab" data-bs-toggle="tab" data-bs-target="#drafts" type="button" role="tab">
                        <i class="fas fa-file-alt me-2"></i>Drafts
                    </button>
                </li>
            @endif
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="profileTabsContent">
            <!-- Posts Tab -->
            <div class="tab-pane fade show active" id="posts" role="tabpanel">
                @if($posts->count() > 0)
                    <div class="row">
                        @foreach($posts as $post)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
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
                                            
                                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-outline-primary">
                                                Read More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $posts->appends(['tab' => 'posts'])->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-edit fa-3x text-muted mb-3"></i>
                        <h4>No posts yet</h4>
                        <p class="text-muted">
                            @if($user->id === auth()->id())
                                Start sharing your knowledge with the community!
                            @else
                                {{ $user->name }} hasn't posted anything yet.
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

            <!-- Liked Posts Tab -->
            <div class="tab-pane fade" id="likes" role="tabpanel">
                <div class="text-center py-5">
                    <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                    <h4>Liked Posts</h4>
                    <p class="text-muted">This feature will show posts that {{ $user->name }} has liked.</p>
                </div>
            </div>

            <!-- Drafts Tab (Only for own profile) -->
            @if($user->id === auth()->id())
                <div class="tab-pane fade" id="drafts" role="tabpanel">
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h4>Draft Posts</h4>
                        <p class="text-muted">Your unpublished posts will appear here.</p>
                        <a href="{{ route('posts.my-posts') }}" class="btn btn-outline-primary">
                            View All My Posts
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleFollow(userId) {
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
        
        const button = document.getElementById('followButton');
        const text = document.getElementById('followText');
        const icon = button.querySelector('i');
        
        if (data.following) {
            button.className = 'btn btn-outline-primary btn-sm';
            text.textContent = 'Unfollow';
            icon.className = 'fas fa-user-minus me-2';
        } else {
            button.className = 'btn btn-primary btn-sm';
            text.textContent = 'Follow';
            icon.className = 'fas fa-user-plus me-2';
        }
        
        // Update followers count
        document.querySelector('.col-4:nth-child(2) .fw-bold').textContent = data.followers_count;
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

// Handle tab switching with URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');
    
    if (activeTab) {
        const tabElement = document.querySelector(`#${activeTab}-tab`);
        if (tabElement) {
            const tab = new bootstrap.Tab(tabElement);
            tab.show();
        }
    }
});
</script>
@endsection