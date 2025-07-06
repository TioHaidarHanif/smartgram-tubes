@extends('layouts.app')

@section('title', 'Admin - Edit Help Topic')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Edit Help Topic</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.help.update', $helpTopic->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Topic Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $helpTopic->title) }}" required
                               placeholder="Enter a clear and descriptive title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Current URL: <code>/help/{{ $helpTopic->slug }}</code>
                            <br>
                            <small class="text-warning">Changing the title will update the URL slug.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="12" required
                                  placeholder="Enter the help topic content...">{{ old('content', $helpTopic->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Use clear, step-by-step instructions. You can use line breaks for formatting.
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order" class="form-label">Display Order</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                       id="order" name="order" value="{{ old('order', $helpTopic->order) }}" required min="1">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Lower numbers appear first in the help section.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Publication Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_published" name="is_published" 
                                           {{ old('is_published', $helpTopic->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        Published
                                    </label>
                                </div>
                                <div class="form-text">
                                    Uncheck to hide this topic from users (save as draft).
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Topic Information -->
                    <div class="alert alert-light border">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Topic Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Created:</strong> {{ $helpTopic->created_at->format('M d, Y \a\t g:i A') }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Current Status:</strong> 
                                    @if($helpTopic->is_published)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </small>
                            </div>
                            @if($helpTopic->updated_at != $helpTopic->created_at)
                                <div class="col-md-6 mt-2">
                                    <small class="text-muted">
                                        <strong>Last Updated:</strong> {{ $helpTopic->updated_at->format('M d, Y \a\t g:i A') }}
                                    </small>
                                </div>
                            @endif
                            <div class="col-md-6 mt-2">
                                <small class="text-muted">
                                    <strong>Current Order:</strong> {{ $helpTopic->order }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Guidelines -->
                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Editing Guidelines</h6>
                        <ul class="mb-0 small">
                            <li>Be mindful that users may have bookmarked this topic</li>
                            <li>Changing the title will update the URL slug</li>
                            <li>Test any revised instructions to ensure they still work</li>
                            <li>Consider adding an "Updated:" note if making significant changes</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.help') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Help Topics
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('help.show', $helpTopic->slug) }}" target="_blank" class="btn btn-outline-info">
                                <i class="fas fa-external-link-alt me-2"></i>View Live
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Topic
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Live Preview -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Live Preview</h6>
            </div>
            <div class="card-body">
                <div id="preview-content">
                    <h5 id="preview-title">{{ $helpTopic->title }}</h5>
                    <div id="preview-body">{!! nl2br(e($helpTopic->content)) !!}</div>
                </div>
            </div>
        </div>

        <!-- Version History (Placeholder) -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Revision History</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Created</h6>
                            <p class="text-muted mb-1">Topic was created</p>
                            <small class="text-muted">{{ $helpTopic->created_at->format('M d, Y \a\t g:i A') }}</small>
                        </div>
                    </div>
                    
                    @if($helpTopic->updated_at != $helpTopic->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Last Updated</h6>
                                <p class="text-muted mb-1">Topic content was modified</p>
                                <small class="text-muted">{{ $helpTopic->updated_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                    @endif
                    
                    @if($helpTopic->is_published)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Published</h6>
                                <p class="text-muted mb-1">Topic is now visible to users</p>
                                <small class="text-muted">Currently published</small>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="text-center text-muted">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Detailed version history would be implemented in a full system
                    </small>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="card mt-4 border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-danger mb-1">Delete this help topic</h6>
                        <p class="text-muted mb-0 small">
                            Once deleted, this topic cannot be recovered. Users will get 404 errors when accessing it.
                        </p>
                    </div>
                    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash me-2"></i>Delete Topic
                    </button>
                </div>
            </div>
        </div>
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
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>This action cannot be undone!</strong>
                </div>
                
                <p>Are you sure you want to delete the help topic "<strong>{{ $helpTopic->title }}</strong>"?</p>
                
                <div class="bg-light p-3 rounded">
                    <h6>This will:</h6>
                    <ul class="mb-0">
                        <li>Permanently remove the topic from the help section</li>
                        <li>Break any existing bookmarks or links to this topic</li>
                        <li>Remove it from search results</li>
                        <li>Make the URL <code>/help/{{ $helpTopic->slug }}</code> return a 404 error</li>
                    </ul>
                </div>
                
                <div class="mt-3">
                    <label for="confirmText" class="form-label">
                        Type <strong>DELETE</strong> to confirm:
                    </label>
                    <input type="text" class="form-control" id="confirmText" placeholder="Type DELETE">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.help.destroy', $helpTopic->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="deleteButton" disabled>
                        <i class="fas fa-trash me-2"></i>Delete Topic
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -21px;
    top: 20px;
    height: calc(100% - 10px);
    width: 2px;
    background-color: #dee2e6;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 4px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    margin-left: 10px;
}
</style>

<script>
// Live preview functionality
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const contentInput = document.getElementById('content');
    const previewTitle = document.getElementById('preview-title');
    const previewBody = document.getElementById('preview-body');
    
    function updatePreview() {
        const title = titleInput.value.trim();
        const content = contentInput.value.trim();
        
        previewTitle.textContent = title || 'No title';
        
        if (content) {
            // Simple text formatting - convert line breaks to <br>
            previewBody.innerHTML = content.replace(/\n/g, '<br>');
        } else {
            previewBody.textContent = 'No content';
        }
    }
    
    titleInput.addEventListener('input', updatePreview);
    contentInput.addEventListener('input', updatePreview);
});

// Delete confirmation
function confirmDelete() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Enable delete button only when confirmation text is entered
document.getElementById('confirmText').addEventListener('input', function() {
    const deleteButton = document.getElementById('deleteButton');
    deleteButton.disabled = this.value !== 'DELETE';
});
</script>
@endsection