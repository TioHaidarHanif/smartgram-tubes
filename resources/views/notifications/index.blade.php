@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Notification Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="markAllAsRead()">
                        <i class="fas fa-check-double me-2"></i>Mark All as Read
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Notification Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Statistics</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <small>Total Notifications</small>
                    <small class="fw-bold">{{ $notifications->total() }}</small>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small>Unread</small>
                    <small class="fw-bold" id="unreadCount">{{ $notifications->where('read_at', null)->count() }}</small>
                </div>
                <div class="d-flex justify-content-between">
                    <small>This Week</small>
                    <small class="fw-bold">{{ $notifications->where('created_at', '>', now()->subWeek())->count() }}</small>
                </div>
            </div>
        </div>

        <!-- Notification Types Filter -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Filter by Type</h6>
            </div>
            <div class="card-body">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="filterFollow" checked>
                    <label class="form-check-label" for="filterFollow">
                        <i class="fas fa-user-plus text-primary me-2"></i>Follows
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="filterLike" checked>
                    <label class="form-check-label" for="filterLike">
                        <i class="fas fa-heart text-danger me-2"></i>Likes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="filterComment" checked>
                    <label class="form-check-label" for="filterComment">
                        <i class="fas fa-comment text-info me-2"></i>Comments
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="filterPost" checked>
                    <label class="form-check-label" for="filterPost">
                        <i class="fas fa-edit text-success me-2"></i>Posts
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-bell me-2"></i>Notifications
                @if($notifications->where('read_at', null)->count() > 0)
                    <span class="badge bg-danger">{{ $notifications->where('read_at', null)->count() }}</span>
                @endif
            </h2>
            @if($notifications->where('read_at', null)->count() > 0)
                <button class="btn btn-outline-primary" onclick="markAllAsRead()">
                    <i class="fas fa-check-double me-2"></i>Mark All as Read
                </button>
            @endif
        </div>

        @if($notifications->count() > 0)
            <div class="notifications-list">
                @foreach($notifications as $notification)
                    <div class="card mb-3 notification-item {{ $notification->read_at ? 'read' : 'unread' }}" 
                         data-notification-id="{{ $notification->id }}" 
                         data-type="{{ $notification->type }}">
                        <div class="card-body">
                            <div class="d-flex">
                                <!-- Notification Icon -->
                                <div class="notification-icon me-3">
                                    @switch($notification->type)
                                        @case('follow')
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user-plus"></i>
                                            </div>
                                            @break
                                        @case('like')
                                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-heart"></i>
                                            </div>
                                            @break
                                        @case('comment')
                                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-comment"></i>
                                            </div>
                                            @break
                                        @case('post')
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-edit"></i>
                                            </div>
                                            @break
                                        @default
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-bell"></i>
                                            </div>
                                    @endswitch
                                </div>

                                <!-- Notification Content -->
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1 {{ $notification->read_at ? 'text-muted' : '' }}">
                                                {{ $notification->title }}
                                            </h6>
                                            <p class="mb-2 {{ $notification->read_at ? 'text-muted' : '' }}">
                                                {{ $notification->message }}
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                                @if($notification->read_at)
                                                    • <i class="fas fa-check text-success"></i> Read
                                                @endif
                                            </small>
                                        </div>
                                        
                                        <!-- Notification Actions -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if(!$notification->read_at)
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="markAsRead({{ $notification->id }})">
                                                            <i class="fas fa-check me-2"></i>Mark as Read
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="deleteNotification({{ $notification->id }})">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Action Buttons based on notification type -->
                                    @if($notification->data)
                                        <div class="mt-2">
                                            @switch($notification->type)
                                                @case('follow')
                                                    @if(isset($notification->data['follower_id']))
                                                        <a href="{{ route('profile', \App\Models\User::find($notification->data['follower_id'])->username ?? '#') }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-user me-1"></i>View Profile
                                                        </a>
                                                    @endif
                                                    @break
                                                @case('like')
                                                    @if(isset($notification->data['post_id']))
                                                        <a href="{{ route('posts.show', $notification->data['post_id']) }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye me-1"></i>View Post
                                                        </a>
                                                    @endif
                                                    @break
                                                @case('comment')
                                                    @if(isset($notification->data['post_id']))
                                                        <a href="{{ route('posts.show', $notification->data['post_id']) }}#comments" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-comment me-1"></i>View Comment
                                                        </a>
                                                    @endif
                                                    @break
                                                @case('post')
                                                    @if(isset($notification->data['post_id']))
                                                        <a href="{{ route('posts.show', $notification->data['post_id']) }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye me-1"></i>View Post
                                                        </a>
                                                    @endif
                                                    @break
                                            @endswitch
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                <h4>No notifications yet</h4>
                <p class="text-muted">You'll see notifications here when others interact with your content or follow you.</p>
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Your First Post
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.notification-item.unread {
    border-left: 4px solid #3b82f6;
    background-color: #f8fafc;
}

.notification-item.read {
    opacity: 0.7;
}

.notification-icon {
    flex-shrink: 0;
}
</style>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            notificationElement.classList.remove('unread');
            notificationElement.classList.add('read');
            
            // Update unread count
            const unreadCount = document.getElementById('unreadCount');
            const currentCount = parseInt(unreadCount.textContent);
            unreadCount.textContent = Math.max(0, currentCount - 1);
            
            // Update notification badge in navbar
            const navBadge = document.getElementById('notificationCount');
            if (navBadge) {
                const newCount = Math.max(0, parseInt(navBadge.textContent) - 1);
                if (newCount === 0) {
                    navBadge.style.display = 'none';
                } else {
                    navBadge.textContent = newCount;
                }
            }
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

function markAllAsRead() {
    fetch('/notifications/read-all', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mark all notifications as read visually
            document.querySelectorAll('.notification-item.unread').forEach(element => {
                element.classList.remove('unread');
                element.classList.add('read');
            });
            
            // Update unread count
            document.getElementById('unreadCount').textContent = '0';
            
            // Hide notification badge in navbar
            const navBadge = document.getElementById('notificationCount');
            if (navBadge) {
                navBadge.style.display = 'none';
            }
            
            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.innerHTML = `
                All notifications marked as read!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.notifications-list').prepend(alert);
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
    });
}

function deleteNotification(notificationId) {
    if (confirm('Are you sure you want to delete this notification?')) {
        // This would be implemented with a delete endpoint
        console.log('Delete notification:', notificationId);
        alert('Delete functionality would be implemented here');
    }
}

// Filter notifications by type
document.addEventListener('DOMContentLoaded', function() {
    const filters = ['filterFollow', 'filterLike', 'filterComment', 'filterPost'];
    
    filters.forEach(filterId => {
        document.getElementById(filterId).addEventListener('change', function() {
            filterNotifications();
        });
    });
});

function filterNotifications() {
    const showFollow = document.getElementById('filterFollow').checked;
    const showLike = document.getElementById('filterLike').checked;
    const showComment = document.getElementById('filterComment').checked;
    const showPost = document.getElementById('filterPost').checked;
    
    document.querySelectorAll('.notification-item').forEach(notification => {
        const type = notification.dataset.type;
        let show = false;
        
        switch (type) {
            case 'follow':
                show = showFollow;
                break;
            case 'like':
                show = showLike;
                break;
            case 'comment':
                show = showComment;
                break;
            case 'post':
                show = showPost;
                break;
            default:
                show = true;
        }
        
        notification.style.display = show ? 'block' : 'none';
    });
}
</script>
@endsection