@extends('layouts.app')

@section('title', 'Admin - Create Help Topic')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Create New Help Topic</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.help.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Topic Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required
                               placeholder="Enter a clear and descriptive title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            This will be displayed as the main heading and used to generate the URL slug.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="12" required
                                  placeholder="Enter the help topic content...">{{ old('content') }}</textarea>
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
                                       id="order" name="order" value="{{ old('order', 1) }}" required min="1">
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
                                           {{ old('is_published') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        Publish immediately
                                    </label>
                                </div>
                                <div class="form-text">
                                    If unchecked, the topic will be saved as a draft and can be published later.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Writing Guidelines -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Writing Guidelines</h6>
                        <ul class="mb-0 small">
                            <li>Use clear, simple language that's easy to understand</li>
                            <li>Break complex topics into step-by-step instructions</li>
                            <li>Include examples where helpful</li>
                            <li>Use consistent formatting and terminology</li>
                            <li>Test your instructions to ensure they work correctly</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.help') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Help Topics
                        </a>
                        <div class="d-flex gap-2">
                            <button type="submit" name="action" value="save_draft" class="btn btn-outline-primary">
                                <i class="fas fa-save me-2"></i>Save as Draft
                            </button>
                            <button type="submit" name="action" value="publish" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i>Save & Publish
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
                    <h5 id="preview-title" class="text-muted">Enter a title to see preview...</h5>
                    <div id="preview-body" class="text-muted">Enter content to see preview...</div>
                </div>
            </div>
        </div>

        <!-- Help Topic Template Examples -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Common Help Topic Templates</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">How-to Guide Template</h6>
                        <pre class="small text-muted bg-light p-3 rounded"><code>How to [Action]

Overview:
Brief description of what this guide covers.

Prerequisites:
- List any requirements
- Account setup needed
- Permissions required

Steps:
1. First step with clear instructions
2. Second step with details
3. Continue with numbered steps

Tips:
- Helpful hint 1
- Helpful hint 2

Troubleshooting:
If you encounter [problem], try [solution].</code></pre>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">FAQ Template</h6>
                        <pre class="small text-muted bg-light p-3 rounded"><code>Frequently Asked Questions about [Topic]

Q: Common question 1?
A: Clear, direct answer with any necessary details.

Q: Common question 2?
A: Another helpful answer.

Q: Common question 3?
A: Detailed response with steps if needed.

Still have questions?
If you can't find what you're looking for, contact our support team at [contact info].</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
        
        previewTitle.textContent = title || 'Enter a title to see preview...';
        previewTitle.className = title ? '' : 'text-muted';
        
        if (content) {
            // Simple text formatting - convert line breaks to <br>
            previewBody.innerHTML = content.replace(/\n/g, '<br>');
            previewBody.className = '';
        } else {
            previewBody.textContent = 'Enter content to see preview...';
            previewBody.className = 'text-muted';
        }
    }
    
    titleInput.addEventListener('input', updatePreview);
    contentInput.addEventListener('input', updatePreview);
    
    // Initial preview update
    updatePreview();
});

// Form submission handling
document.querySelector('form').addEventListener('submit', function(e) {
    const action = e.submitter.value;
    
    if (action === 'publish') {
        // Check the publish checkbox when publishing
        document.getElementById('is_published').checked = true;
    } else if (action === 'save_draft') {
        // Uncheck the publish checkbox when saving as draft
        document.getElementById('is_published').checked = false;
    }
});

// Template insertion
function insertTemplate(template) {
    const contentTextarea = document.getElementById('content');
    const templates = {
        'howto': `How to [Action]

Overview:
Brief description of what this guide covers.

Prerequisites:
- List any requirements
- Account setup needed
- Permissions required

Steps:
1. First step with clear instructions
2. Second step with details
3. Continue with numbered steps

Tips:
- Helpful hint 1
- Helpful hint 2

Troubleshooting:
If you encounter [problem], try [solution].`,
        
        'faq': `Frequently Asked Questions about [Topic]

Q: Common question 1?
A: Clear, direct answer with any necessary details.

Q: Common question 2?
A: Another helpful answer.

Q: Common question 3?
A: Detailed response with steps if needed.

Still have questions?
If you can't find what you're looking for, contact our support team.`
    };
    
    if (templates[template]) {
        contentTextarea.value = templates[template];
        // Trigger preview update
        contentTextarea.dispatchEvent(new Event('input'));
    }
}

// Add template buttons
document.addEventListener('DOMContentLoaded', function() {
    const templateSection = document.querySelector('.card:last-child .card-body');
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'mt-3 text-center';
    buttonContainer.innerHTML = `
        <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="insertTemplate('howto')">
            Use How-to Template
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTemplate('faq')">
            Use FAQ Template
        </button>
    `;
    templateSection.appendChild(buttonContainer);
});
</script>
@endsection