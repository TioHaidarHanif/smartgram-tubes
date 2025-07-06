@extends('layouts.app')

@section('title', 'Create Forum Discussion')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Start a New Discussion</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('forum.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Discussion Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required
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
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        <div class="form-text">Choose the most appropriate category to help others find your discussion.</div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Discussion Content</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="8" required
                                  placeholder="Describe your question, share your knowledge, or start the conversation...">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Provide detailed information about your topic. Include context, examples, or specific questions to help others understand and respond effectively.
                        </div>
                    </div>

                    <!-- Forum Guidelines Reminder -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Discussion Guidelines</h6>
                        <ul class="mb-0 small">
                            <li>Be respectful and constructive in your language</li>
                            <li>Provide enough context for others to understand your topic</li>
                            <li>Search existing discussions before posting to avoid duplicates</li>
                            <li>Choose the right category to help others find your discussion</li>
                            <li>Follow up on responses and engage with the community</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('forum.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Forum
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Start Discussion
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tips for Better Discussions -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Tips for Better Discussions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="fas fa-lightbulb me-2"></i>Be Specific</h6>
                        <p class="text-muted small">Include relevant details, error messages, or examples to help others understand your situation.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="fas fa-search me-2"></i>Search First</h6>
                        <p class="text-muted small">Check if someone has already discussed your topic to avoid duplicate conversations.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="fas fa-users me-2"></i>Engage</h6>
                        <p class="text-muted small">Respond to comments and questions to keep the conversation productive and helpful.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="fas fa-heart me-2"></i>Be Kind</h6>
                        <p class="text-muted small">Remember there's a real person behind each response. Be respectful and constructive.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection