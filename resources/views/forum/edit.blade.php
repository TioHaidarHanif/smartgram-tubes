@extends('layouts.app')

@section('title', 'Edit Discussion')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Edit Discussion</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('forum.update', $forumPost->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Discussion Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $forumPost->title) }}" required
                               placeholder="Enter a clear and descriptive title for your discussion">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Choose a title that clearly describes what you want to discuss.</div>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Select a category for your discussion</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ old('category_id', $forumPost->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                    @if($category->description)
                                        - {{ $category->description }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Discussion Content</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="8" required
                                  placeholder="Describe your question, share your knowledge, or start the conversation...">{{ old('content', $forumPost->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Provide detailed information about your topic. Include context, examples, or specific questions to help others understand and respond effectively.
                        </div>
                    </div>

                    <!-- Original Post Info -->
                    <div class="alert alert-light border">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Discussion Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Created:</strong> {{ $forumPost->created_at->format('M d, Y \a\t g:i A') }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Replies:</strong> {{ $forumPost->comments->count() }}
                                </small>
                            </div>
                            <div class="col-md-6 mt-2">
                                <small class="text-muted">
                                    <strong>Views:</strong> {{ $forumPost->views ?? 0 }}
                                </small>
                            </div>
                            @if($forumPost->updated_at != $forumPost->created_at)
                                <div class="col-md-6 mt-2">
                                    <small class="text-muted">
                                        <strong>Last Updated:</strong> {{ $forumPost->updated_at->format('M d, Y \a\t g:i A') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Edit Guidelines -->
                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Editing Guidelines</h6>
                        <ul class="mb-0 small">
                            <li>Be mindful that others may have already responded to your original content</li>
                            <li>Major changes to the discussion topic should be clearly noted</li>
                            <li>Consider adding an "Edit:" note if you're changing the context significantly</li>
                            <li>Maintain the original intent of the discussion to preserve reply context</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('forum.show', $forumPost->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Discussion
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview of Current Discussion -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Current Discussion Preview</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    @if($forumPost->is_sticky)
                        <span class="badge bg-warning text-dark me-2">Pinned</span>
                    @endif
                    @if($forumPost->category)
                        <span class="badge bg-secondary me-2">{{ $forumPost->category->name }}</span>
                    @endif
                </div>
                
                <h5 class="mb-3">{{ $forumPost->title }}</h5>
                
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $forumPost->user->avatar ?? 'https://via.placeholder.com/32' }}" 
                         class="rounded-circle me-3" width="32" height="32" alt="Avatar">
                    <div>
                        <h6 class="mb-0">{{ $forumPost->user->name }}</h6>
                        <small class="text-muted">{{ $forumPost->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                
                <div class="content-preview text-muted">
                    {{ Str::limit($forumPost->content, 200) }}
                </div>
                
                <div class="d-flex gap-3 mt-3">
                    <small class="text-muted">
                        <i class="fas fa-comment"></i> {{ $forumPost->comments->count() }} replies
                    </small>
                    <small class="text-muted">
                        <i class="fas fa-eye"></i> {{ $forumPost->views ?? 0 }} views
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection