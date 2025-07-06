@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Edit Post</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $post->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Post Type</label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" name="type" required onchange="toggleMediaUpload()">
                            <option value="">Select post type</option>
                            <option value="text" {{ old('type', $post->type) == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="image" {{ old('type', $post->type) == 'image' ? 'selected' : '' }}>Image</option>
                            <option value="video" {{ old('type', $post->type) == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="document" {{ old('type', $post->type) == 'document' ? 'selected' : '' }}>Document</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="8" required>{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($post->media_urls)
                        <div class="mb-3">
                            <label class="form-label">Current Media</label>
                            <div class="row">
                                @foreach($post->media_urls as $mediaUrl)
                                    <div class="col-md-4 mb-3">
                                        @if($post->type === 'image')
                                            <img src="{{ $mediaUrl }}" class="img-fluid rounded" alt="Current media">
                                        @elseif($post->type === 'video')
                                            <video controls class="img-fluid rounded">
                                                <source src="{{ $mediaUrl }}" type="video/mp4">
                                            </video>
                                        @else
                                            <div class="border rounded p-3 text-center">
                                                <i class="fas fa-file-alt fa-2x text-muted mb-2"></i>
                                                <div>
                                                    <a href="{{ $mediaUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        View Document
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-3" id="mediaUpload">
                        <label for="media" class="form-label">
                            @if($post->media_urls)
                                Add More Media Files
                            @else
                                Media Files
                            @endif
                        </label>
                        <input type="file" class="form-control @error('media') is-invalid @enderror" 
                               id="media" name="media[]" multiple accept="image/*,video/*,.pdf,.doc,.docx">
                        <div class="form-text">
                            Maximum file size: 10MB per file. For images: jpg, png, gif. For videos: mp4, avi, mov. For documents: pdf, doc, docx.
                        </div>
                        @error('media')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_published" name="is_published" 
                                   {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">
                                Published
                            </label>
                        </div>
                        <div class="form-text">
                            If unchecked, the post will be saved as draft.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleMediaUpload() {
    const type = document.getElementById('type').value;
    const mediaUpload = document.getElementById('mediaUpload');
    
    if (type === 'image' || type === 'video' || type === 'document') {
        mediaUpload.style.display = 'block';
    } else {
        mediaUpload.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleMediaUpload();
});
</script>
@endsection