@extends('layouts.app')

@section('title', 'Admin - Manage Help Topics')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Admin Menu</h6>
            </div>
            <div class="card-body">
                <nav class="nav flex-column">
                    <a class="nav-link active" href="{{ route('admin.help') }}">
                        <i class="fas fa-question-circle me-2"></i>Help Topics
                    </a>
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </nav>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Help Statistics</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <small>Total Topics</small>
                    <small class="fw-bold">{{ $helpTopics->count() }}</small>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small>Published</small>
                    <small class="fw-bold">{{ $helpTopics->where('is_published', true)->count() }}</small>
                </div>
                <div class="d-flex justify-content-between">
                    <small>Drafts</small>
                    <small class="fw-bold">{{ $helpTopics->where('is_published', false)->count() }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-question-circle me-2"></i>Manage Help Topics
            </h2>
            <a href="{{ route('admin.help.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Topic
            </a>
        </div>

        @if($helpTopics->count() > 0)
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>Title</th>
                                    <th width="100">Order</th>
                                    <th width="100">Status</th>
                                    <th width="150">Created</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="helpTopicsTable">
                                @foreach($helpTopics as $topic)
                                    <tr data-topic-id="{{ $topic->id }}">
                                        <td>{{ $topic->id }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $topic->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit(strip_tags($topic->content), 60) }}</small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-link me-1"></i>
                                                    <code>/help/{{ $topic->slug }}</code>
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm order-input" 
                                                   value="{{ $topic->order }}" 
                                                   data-topic-id="{{ $topic->id }}"
                                                   style="width: 80px;">
                                        </td>
                                        <td>
                                            @if($topic->is_published)
                                                <span class="badge bg-success">Published</span>
                                            @else
                                                <span class="badge bg-secondary">Draft</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $topic->created_at->format('M d, Y') }}
                                                <br>
                                                {{ $topic->created_at->format('g:i A') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('help.show', $topic->slug) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   target="_blank" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.help.edit', $topic->id) }}" 
                                                   class="btn btn-sm btn-outline-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteHelpTopic({{ $topic->id }})" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Bulk Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-outline-success" onclick="publishAll()">
                                <i class="fas fa-eye me-2"></i>Publish All Drafts
                            </button>
                            <button class="btn btn-outline-secondary" onclick="unpublishAll()">
                                <i class="fas fa-eye-slash me-2"></i>Unpublish All
                            </button>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-outline-primary" onclick="reorderTopics()">
                                <i class="fas fa-sort me-2"></i>Update Order
                            </button>
                            <button class="btn btn-outline-info" onclick="exportTopics()">
                                <i class="fas fa-download me-2"></i>Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                <h4>No help topics yet</h4>
                <p class="text-muted">Create your first help topic to assist users with using Smartgram.</p>
                <a href="{{ route('admin.help.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create First Help Topic
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this help topic? This action cannot be undone.</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> Users will no longer be able to access this help topic, and any bookmarks or links to it will be broken.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Topic
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteHelpTopic(topicId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/admin/help/${topicId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function reorderTopics() {
    const orderInputs = document.querySelectorAll('.order-input');
    const updates = [];
    
    orderInputs.forEach(input => {
        updates.push({
            id: input.dataset.topicId,
            order: input.value
        });
    });
    
    // Here you would send the updates to the server
    console.log('Updating order:', updates);
    
    // Simulate API call
    fetch('/admin/help/reorder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ updates: updates })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Order updated successfully!', 'success');
            location.reload();
        } else {
            showAlert('Error updating order.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error updating order.', 'danger');
    });
}

function publishAll() {
    if (confirm('Are you sure you want to publish all draft help topics?')) {
        // Simulate API call
        showAlert('All topics published successfully!', 'success');
    }
}

function unpublishAll() {
    if (confirm('Are you sure you want to unpublish all help topics? This will hide them from users.')) {
        // Simulate API call
        showAlert('All topics unpublished successfully!', 'warning');
    }
}

function exportTopics() {
    // Simulate export functionality
    showAlert('Export functionality would be implemented here.', 'info');
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.querySelector('.col-md-9').prepend(alertDiv);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Make table sortable by dragging rows
document.addEventListener('DOMContentLoaded', function() {
    // Add drag and drop functionality here if needed
    
    // Auto-save order changes
    document.querySelectorAll('.order-input').forEach(input => {
        input.addEventListener('change', function() {
            // Optionally auto-save individual order changes
            console.log('Order changed for topic', this.dataset.topicId, 'to', this.value);
        });
    });
});
</script>
@endsection