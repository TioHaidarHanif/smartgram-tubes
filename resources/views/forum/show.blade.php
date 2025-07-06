@extends('layouts.app')

@section('title', $forumPost->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Main Discussion -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        @if($forumPost->is_sticky)
                            <span class="badge bg-warning text-dark me-2">Pinned</span>
                        @endif
                        @if($forumPost->category)
                            <span class="badge bg-secondary me-2">{{ $forumPost->category->name }}</span>
                        @endif
                        @if($forumPost->created_at->diffInHours() < 24)
                            <span class="badge bg-success">New</span>
                        @endif
                    </div>
                    @if($forumPost->user_id === auth()->id() || auth()->user()->role === 'admin')
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('forum.edit', $forumPost->id) }}">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('forum.destroy', $forumPost->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" 
                                                onclick="return confirm('Are you sure you want to delete this discussion?')">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <h3 class="mb-3">{{ $forumPost->title }}</h3>
                
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $forumPost->user->avatar ?? 'https://via.placeholder.com/40' }}" 
                         class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                    <div>
                        <h6 class="mb-0">
                            <a href="{{ route('profile', $forumPost->user->username) }}" class="text-decoration-none">
                                {{ $forumPost->user->name }}
                            </a>
                        </h6>
                        <small class="text-muted">
                            {{ $forumPost->created_at->diffForHumans() }}
                            @if($forumPost->updated_at != $forumPost->created_at)
                                • Updated {{ $forumPost->updated_at->diffForHumans() }}
                            @endif
                        </small>
                    </div>
                </div>
                
                <div class="content-text mb-4">
                    {!! nl2br(e($forumPost->content)) !!}
                </div>
                
                <div class="d-flex align-items-center gap-4">
                    <a href="#" class="text-muted text-decoration-none" onclick="toggleLike('forum_post', {{ $forumPost->id }})">
                        <i class="fas fa-heart" id="like-icon-{{ $forumPost->id }}"></i>
                        <span id="like-count-{{ $forumPost->id }}">{{ $forumPost->likes ?? 0 }}</span>
                    </a>
                    <a href="#comments" class="text-muted text-decoration-none">
                        <i class="fas fa-comment"></i>
                        <span>{{ $forumPost->comments->count() }} replies</span>
                    </a>
                    <a href="#" class="text-muted text-decoration-none" onclick="shareDiscussion({{ $forumPost->id }})">
                        <i class="fas fa-share"></i>
                        <span>Share</span>
                    </a>
                    <div class="ms-auto text-muted">
                        <i class="fas fa-eye"></i> {{ $forumPost->views ?? 0 }} views
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments/Replies Section -->
        <div class="card mt-4" id="comments">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-comments me-2"></i>
                    Replies ({{ $forumPost->comments->count() }})
                </h5>
            </div>
            <div class="card-body">
                @auth
                    <form method="POST" action="{{ route('comments.store') }}" class="mb-4">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $forumPost->id }}">
                        <input type="hidden" name="commentable_type" value="App\Models\ForumPost">
                        <input type="hidden" name="commentable_id" value="{{ $forumPost->id }}">
                        
                        <div class="d-flex">
                            <img src="{{ auth()->user()->avatar ?? 'https://via.placeholder.com/32' }}" 
                                 class="rounded-circle me-3" width="32" height="32" alt="Avatar">
                            <div class="flex-grow-1">
                                <textarea name="content" class="form-control" rows="3" 
                                          placeholder="Share your thoughts or ask a follow-up question..."></textarea>
                                <button type="submit" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-reply me-2"></i>Post Reply
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-center py-3">
                        <p class="text-muted">Please <a href="{{ route('login') }}">login</a> to join this discussion.</p>
                    </div>
                @endauth

                @foreach($forumPost->comments as $comment)
                    <div class="comment mb-4 border-bottom pb-4">
                        <div class="d-flex">
                            <img src="{{ $comment->user->avatar ?? 'https://via.placeholder.com/32' }}" 
                                 class="rounded-circle me-3" width="32" height="32" alt="Avatar">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-0">
                                            <a href="{{ route('profile', $comment->user->username) }}" class="text-decoration-none">
                                                {{ $comment->user->name }}
                                            </a>
                                            @if($comment->user_id === $forumPost->user_id)
                                                <span class="badge bg-primary ms-2">Original Poster</span>
                                            @endif
                                        </h6>
                                        <small class="text-muted">
                                            {{ $comment->created_at->diffForHumans() }}
                                            @if($comment->updated_at != $comment->created_at)
                                                • Edited
                                            @endif
                                        </small>
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
                                                                onclick="return confirm('Are you sure you want to delete this reply?')">
                                                            <i class="fas fa-trash me-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <p class="mt-2 mb-2">{{ $comment->content }}</p>
                                <div class="d-flex gap-3">
                                    <a href="#" class="text-muted text-decoration-none" onclick="toggleLike('comment', {{ $comment->id }})">
                                        <i class="fas fa-heart"></i> {{ $comment->likes ?? 0 }}
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
                                            <input type="hidden" name="post_id" value="{{ $forumPost->id }}">
                                            <input type="hidden" name="commentable_type" value="App\Models\ForumPost">
                                            <input type="hidden" name="commentable_id" value="{{ $forumPost->id }}">
                                            
                                            <div class="d-flex">
                                                <img src="{{ auth()->user()->avatar ?? 'https://via.placeholder.com/32' }}" 
                                                     class="rounded-circle me-3" width="24" height="24" alt="Avatar">
                                                <div class="flex-grow-1">
                                                    <textarea name="content" class="form-control form-control-sm" rows="2" 
                                                              placeholder="Write a reply..."></textarea>
                                                    <div class="mt-2">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-reply me-2"></i>Reply
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

                                <!-- Nested Replies -->
                                @foreach($comment->replies as $reply)
                                    <div class="ms-4 mt-3 border-start ps-3">
                                        <div class="d-flex">
                                            <img src="{{ $reply->user->avatar ?? 'https://via.placeholder.com/32' }}" 
                                                 class="rounded-circle me-3" width="24" height="24" alt="Avatar">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-0 fs-6">
                                                            <a href="{{ route('profile', $reply->user->username) }}" class="text-decoration-none">
                                                                {{ $reply->user->name }}
                                                            </a>
                                                            @if($reply->user_id === $forumPost->user_id)
                                                                <span class="badge bg-primary ms-2">OP</span>
                                                            @endif
                                                        </h6>
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
                                                        <i class="fas fa-heart"></i> {{ $reply->likes ?? 0 }}
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
        <!-- Discussion Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Discussion Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('forum.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Forum
                    </a>
                    @if($forumPost->user_id === auth()->id())
                        <a href="{{ route('forum.edit', $forumPost->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-edit me-2"></i>Edit Discussion
                        </a>
                    @endif
                    <button class="btn btn-outline-success" onclick="shareDiscussion({{ $forumPost->id }})">
                        <i class="fas fa-share me-2"></i>Share Discussion
                    </button>
                    @auth
                        <a href="{{ route('forum.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Start New Discussion
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Author Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Discussion Starter</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <img src="{{ $forumPost->user->avatar ?? 'https://via.placeholder.com/50' }}" 
                         class="rounded-circle me-3" width="50" height="50" alt="Avatar">
                    <div>
                        <h6 class="mb-0">
                            <a href="{{ route('profile', $forumPost->user->username) }}" class="text-decoration-none">
                                {{ $forumPost->user->name }}
                            </a>
                        </h6>
                        <small class="text-muted">@{{ $forumPost->user->username }}</small>
                    </div>
                </div>
                @if($forumPost->user->bio)
                    <p class="mt-3 mb-0">{{ $forumPost->user->bio }}</p>
                @endif
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>{{ $forumPost->user->posts()->where('is_published', true)->count() }}</strong> posts •
                        <strong>{{ $forumPost->user->followers()->count() }}</strong> followers
                    </small>
                </div>
            </div>
        </div>

        <!-- Related Discussions -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Related Discussions</h6>
            </div>
            <div class="card-body">
                <div class="text-center text-muted">
                    <i class="fas fa-comments fa-2x mb-2"></i>
                    <p class="small">Related discussions will appear here based on category and tags.</p>
                </div>
            </div>
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

function shareDiscussion(discussionId) {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: url
        });
    } else {
        navigator.clipboard.writeText(url).then(function() {
            alert('Discussion link copied to clipboard!');
        });
    }
}

function editComment(commentId) {
    alert('Edit comment functionality would be implemented here');
}

function toggleLike(type, id) {
    // This would integrate with the like system
    console.log('Toggle like for', type, id);
}
</script>
@endsection