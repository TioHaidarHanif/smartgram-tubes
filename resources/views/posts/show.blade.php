@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="post-card">
            <div class="post-header">
                <img src="{{ $post->user->avatar ?? 'https://via.placeholder.com/40' }}" 
                     class="post-avatar" alt="Avatar">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-0">
                                <a href="{{ route('profile', $post->user->username) }}" class="text-decoration-none">
                                    {{ $post->user->name }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                {{ $post->created_at->diffForHumans() }}
                                @if($post->category)
                                    • <span class="badge bg-secondary">{{ $post->category->name }}</span>
                                @endif
                            </small>
                        </div>
                        @if($post->user_id === auth()->id())
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a></li>
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
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="post-content">
                <h3>{{ $post->title }}</h3>
                
                @if($post->type === 'image' && $post->media_urls)
                    <div class="mb-3">
                        @foreach($post->media_urls as $mediaUrl)
                            <img src="{{ $mediaUrl }}" class="img-fluid rounded mb-2" alt="Post image">
                        @endforeach
                    </div>
                @elseif($post->type === 'video' && $post->media_urls)
                    <div class="mb-3">
                        @foreach($post->media_urls as $mediaUrl)
                            <video controls class="img-fluid rounded mb-2">
                                <source src="{{ $mediaUrl }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endforeach
                    </div>
                @elseif($post->type === 'document' && $post->media_urls)
                    <div class="mb-3">
                        @foreach($post->media_urls as $mediaUrl)
                            <div class="border rounded p-3 mb-2">
                                <i class="fas fa-file-alt fa-2x text-muted mb-2"></i>
                                <div>
                                    <a href="{{ $mediaUrl }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-download me-2"></i>Download Document
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <div class="content-text">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </div>
            
            <div class="post-actions">
                <div class="d-flex align-items-center gap-4">
                    <a href="#" class="post-action" onclick="toggleLike('post', {{ $post->id }})">
                        <i class="fas fa-heart" id="like-icon-{{ $post->id }}"></i>
                        <span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span>
                    </a>
                    <a href="#comments" class="post-action">
                        <i class="fas fa-comment"></i>
                        <span>{{ $post->comments->count() }}</span>
                    </a>
                    <a href="#" class="post-action" onclick="sharePost({{ $post->id }})">
                        <i class="fas fa-share"></i>
                        <span>Share</span>
                    </a>
                    <div class="ms-auto text-muted">
                        <i class="fas fa-eye"></i> {{ $post->views ?? 0 }} views
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="card mt-4" id="comments">
            <div class="card-header">
                <h5 class="mb-0">Comments ({{ $post->comments->count() }})</h5>
            </div>
            <div class="card-body">
                @auth
                    <form method="POST" action="{{ route('comments.store') }}" class="mb-4">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <input type="hidden" name="commentable_type" value="App\Models\Post">
                        <input type="hidden" name="commentable_id" value="{{ $post->id }}">
                        
                        <div class="d-flex">
                            <img src="{{ auth()->user()->avatar ?? 'https://via.placeholder.com/32' }}" 
                                 class="rounded-circle me-3" width="32" height="32" alt="Avatar">
                            <div class="flex-grow-1">
                                <textarea name="content" class="form-control" rows="3" 
                                          placeholder="Write a comment..."></textarea>
                                <button type="submit" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-paper-plane me-2"></i>Post Comment
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-center py-3">
                        <p class="text-muted">Please <a href="{{ route('login') }}">login</a> to comment on this post.</p>
                    </div>
                @endauth

                @foreach($post->comments as $comment)
                    <div class="comment mb-3 border-bottom pb-3">
                        <div class="d-flex">
                            <img src="{{ $comment->user->avatar ?? 'https://via.placeholder.com/32' }}" 
                                 class="rounded-circle me-3" width="32" height="32" alt="Avatar">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($comment->user_id === auth()->id())
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="editComment({{ $comment->id }})">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </a></li>
                                                <li>
                                                    <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this comment?')">
                                                            <i class="fas fa-trash me-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <p class="mt-2 mb-1">{{ $comment->content }}</p>
                                <div class="d-flex gap-3">
                                    <a href="#" class="text-muted text-decoration-none" onclick="toggleLike('comment', {{ $comment->id }})">
                                        <i class="fas fa-heart"></i> {{ $comment->likes->count() }}
                                    </a>
                                    <a href="#" class="text-muted text-decoration-none" onclick="showReplyForm({{ $comment->id }})">
                                        <i class="fas fa-reply"></i> Reply
                                    </a>
                                </div>

                                <!-- Reply Form -->
                                <div id="reply-form-{{ $comment->id }}" class="mt-3" style="display: none;">
                                    @auth
                                        <form method="POST" action="{{ route('comments.store') }}">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                                            <input type="hidden" name="commentable_type" value="App\Models\Post">
                                            <input type="hidden" name="commentable_id" value="{{ $post->id }}">
                                            
                                            <div class="d-flex">
                                                <img src="{{ auth()->user()->avatar ?? 'https://via.placeholder.com/32' }}" 
                                                     class="rounded-circle me-3" width="24" height="24" alt="Avatar">
                                                <div class="flex-grow-1">
                                                    <textarea name="content" class="form-control form-control-sm" rows="2" 
                                                              placeholder="Write a reply..."></textarea>
                                                    <div class="mt-2">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-paper-plane me-2"></i>Reply
                                                        </button>
                                                        <button type="button" class="btn btn-secondary btn-sm" onclick="hideReplyForm({{ $comment->id }})">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endauth
                                </div>

                                <!-- Replies -->
                                @foreach($comment->replies as $reply)
                                    <div class="ms-4 mt-3 border-start ps-3">
                                        <div class="d-flex">
                                            <img src="{{ $reply->user->avatar ?? 'https://via.placeholder.com/32' }}" 
                                                 class="rounded-circle me-3" width="24" height="24" alt="Avatar">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-0 fs-6">{{ $reply->user->name }}</h6>
                                                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    @if($reply->user_id === auth()->id())
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-h"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <form method="POST" action="{{ route('comments.destroy', $reply->id) }}" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger" 
                                                                                onclick="return confirm('Are you sure you want to delete this reply?')">
                                                                            <i class="fas fa-trash me-2"></i>Delete
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                                <p class="mt-2 mb-1">{{ $reply->content }}</p>
                                                <div class="d-flex gap-3">
                                                    <a href="#" class="text-muted text-decoration-none" onclick="toggleLike('comment', {{ $reply->id }})">
                                                        <i class="fas fa-heart"></i> {{ $reply->likes->count() }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="sidebar">
            <h5 class="mb-3">Post Actions</h5>
            <div class="d-grid gap-2">
                <a href="{{ route('posts.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Posts
                </a>
                @if($post->user_id === auth()->id())
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-edit me-2"></i>Edit Post
                    </a>
                @endif
                <button class="btn btn-outline-success" onclick="sharePost({{ $post->id }})">
                    <i class="fas fa-share me-2"></i>Share Post
                </button>
            </div>
        </div>

        <!-- Author Info -->
        <div class="sidebar mt-4">
            <h5 class="mb-3">About Author</h5>
            <div class="d-flex align-items-center">
                <img src="{{ $post->user->avatar ?? 'https://via.placeholder.com/50' }}" 
                     class="rounded-circle me-3" width="50" height="50" alt="Avatar">
                <div>
                    <h6 class="mb-0">
                        <a href="{{ route('profile', $post->user->username) }}" class="text-decoration-none">
                            {{ $post->user->name }}
                        </a>
                    </h6>
                    <small class="text-muted">@{{ $post->user->username }}</small>
                </div>
            </div>
            @if($post->user->bio)
                <p class="mt-3 mb-0">{{ $post->user->bio }}</p>
            @endif
        </div>
    </div>
</div>

<script>
function showReplyForm(commentId) {
    document.getElementById('reply-form-' + commentId).style.display = 'block';
}

function hideReplyForm(commentId) {
    document.getElementById('reply-form-' + commentId).style.display = 'none';
}

function sharePost(postId) {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: url
        });
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(url).then(function() {
            alert('Post link copied to clipboard!');
        });
    }
}

function editComment(commentId) {
    // This would typically open a modal or inline editor
    // For now, we'll just show an alert
    alert('Edit comment functionality would be implemented here');
}
</script>
@endsection