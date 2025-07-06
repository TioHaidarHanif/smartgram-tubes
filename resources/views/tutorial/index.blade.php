@extends('layouts.app')

@section('title', 'Tutorial - Learn Smartgram')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Tutorial Header -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h1 class="display-5 fw-bold text-primary mb-3">
                        <i class="fas fa-graduation-cap me-3"></i>Welcome to Smartgram!
                    </h1>
                    <p class="lead mb-4">
                        Let's take a quick tour to help you get started with our social learning platform.
                    </p>
                    
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Tutorial Progress</span>
                            <span class="text-muted" id="progressText">0 of {{ $tutorialSteps->count() }} completed</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: 0%" id="progressBar"></div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 justify-content-center">
                        <button class="btn btn-primary" onclick="startTutorial()">
                            <i class="fas fa-play me-2"></i>Start Tutorial
                        </button>
                        <button class="btn btn-outline-secondary" onclick="skipAllTutorials()">
                            <i class="fas fa-forward me-2"></i>Skip All
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tutorial Steps -->
            <div class="tutorial-steps">
                @foreach($tutorialSteps as $index => $step)
                    <div class="card mb-4 tutorial-step {{ in_array($step->id, $completedSteps) ? 'completed' : '' }}" 
                         data-step="{{ $step->id }}" 
                         data-order="{{ $step->order_index }}"
                         style="{{ $index > 0 ? 'display: none;' : '' }}">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <span class="badge bg-primary me-2">Step {{ $step->order_index }}</span>
                                        {{ $step->title }}
                                    </h5>
                                </div>
                                <div class="step-status">
                                    @if(in_array($step->id, $completedSteps))
                                        <i class="fas fa-check-circle text-success fa-lg"></i>
                                    @else
                                        <i class="fas fa-circle text-muted fa-lg"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="mb-3">{{ $step->description }}</p>
                                    
                                    @if($step->content)
                                        <div class="tutorial-content mb-4">
                                            {!! nl2br(e($step->content)) !!}
                                        </div>
                                    @endif
                                    
                                    @if($step->action_button_text && $step->action_button_url)
                                        <div class="mb-3">
                                            <a href="{{ $step->action_button_url }}" 
                                               target="{{ $step->action_button_url[0] === '/' ? '_self' : '_blank' }}"
                                               class="btn btn-success">
                                                <i class="fas fa-external-link-alt me-2"></i>{{ $step->action_button_text }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    @if($step->image_url)
                                        <img src="{{ $step->image_url }}" 
                                             class="img-fluid rounded shadow-sm" 
                                             alt="{{ $step->title }}">
                                    @else
                                        <!-- Default illustrations for different step types -->
                                        <div class="text-center p-4 bg-light rounded">
                                            @switch($step->order_index)
                                                @case(1)
                                                    <i class="fas fa-user-plus fa-4x text-primary mb-3"></i>
                                                    @break
                                                @case(2)
                                                    <i class="fas fa-edit fa-4x text-success mb-3"></i>
                                                    @break
                                                @case(3)
                                                    <i class="fas fa-users fa-4x text-info mb-3"></i>
                                                    @break
                                                @case(4)
                                                    <i class="fas fa-comments fa-4x text-warning mb-3"></i>
                                                    @break
                                                @default
                                                    <i class="fas fa-lightbulb fa-4x text-secondary mb-3"></i>
                                            @endswitch
                                            <p class="text-muted small">Illustration placeholder</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Step Actions -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <div>
                                    @if($index > 0)
                                        <button class="btn btn-outline-secondary" onclick="previousStep()">
                                            <i class="fas fa-arrow-left me-2"></i>Previous
                                        </button>
                                    @endif
                                </div>
                                
                                <div class="d-flex gap-2">
                                    @if($step->is_skippable)
                                        <button class="btn btn-outline-warning" onclick="skipStep({{ $step->id }})">
                                            <i class="fas fa-forward me-2"></i>Skip
                                        </button>
                                    @endif
                                    
                                    @if($index < $tutorialSteps->count() - 1)
                                        <button class="btn btn-primary" onclick="completeStep({{ $step->id }})">
                                            <i class="fas fa-check me-2"></i>Complete & Next
                                        </button>
                                    @else
                                        <button class="btn btn-success" onclick="finishTutorial({{ $step->id }})">
                                            <i class="fas fa-flag-checkered me-2"></i>Finish Tutorial
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tutorial Completion -->
            <div class="card mb-4 tutorial-completion" style="display: none;">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-trophy fa-4x text-warning mb-3"></i>
                        <h2 class="text-success">Congratulations!</h2>
                        <p class="lead">You've completed the Smartgram tutorial!</p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-edit fa-2x text-primary mb-2"></i>
                                    <h6>Start Creating</h6>
                                    <p class="small text-muted">Share your knowledge with posts</p>
                                    <a href="{{ route('posts.create') }}" class="btn btn-sm btn-primary">Create Post</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x text-success mb-2"></i>
                                    <h6>Join Community</h6>
                                    <p class="small text-muted">Connect with other learners</p>
                                    <a href="{{ route('search') }}" class="btn btn-sm btn-success">Find People</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-comments fa-2x text-info mb-2"></i>
                                    <h6>Start Discussions</h6>
                                    <p class="small text-muted">Ask questions in the forum</p>
                                    <a href="{{ route('forum.index') }}" class="btn btn-sm btn-info">Visit Forum</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('dashboard') }}" class="btn btn-lg btn-primary">
                        <i class="fas fa-home me-2"></i>Go to Dashboard
                    </a>
                </div>
            </div>

            <!-- Tutorial Navigation -->
            <div class="card tutorial-navigation" style="position: sticky; bottom: 20px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="tutorial-steps-indicator">
                            @foreach($tutorialSteps as $index => $step)
                                <span class="step-dot {{ $index === 0 ? 'active' : '' }} {{ in_array($step->id, $completedSteps) ? 'completed' : '' }}" 
                                      data-step="{{ $index }}"></span>
                            @endforeach
                        </div>
                        
                        <div class="tutorial-controls">
                            <button class="btn btn-outline-danger btn-sm" onclick="exitTutorial()">
                                <i class="fas fa-times me-2"></i>Exit Tutorial
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tutorial-step.completed {
    border-left: 4px solid #28a745;
}

.step-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #dee2e6;
    margin-right: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.step-dot.active {
    background-color: #007bff;
    transform: scale(1.2);
}

.step-dot.completed {
    background-color: #28a745;
}

.tutorial-navigation {
    background-color: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.tutorial-content {
    line-height: 1.6;
}

@media (max-width: 768px) {
    .tutorial-navigation {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        border-radius: 0;
        margin: 0;
    }
    
    .step-dot {
        width: 8px;
        height: 8px;
        margin-right: 4px;
    }
}
</style>

<script>
let currentStepIndex = 0;
const totalSteps = {{ $tutorialSteps->count() }};
const completedSteps = @json($completedSteps);
const tutorialSteps = @json($tutorialSteps);

document.addEventListener('DOMContentLoaded', function() {
    updateProgressBar();
    
    // Check if user has completed some steps and show the appropriate step
    const firstIncompleteStep = findFirstIncompleteStep();
    if (firstIncompleteStep !== -1) {
        showStep(firstIncompleteStep);
    }
});

function findFirstIncompleteStep() {
    for (let i = 0; i < tutorialSteps.length; i++) {
        if (!completedSteps.includes(tutorialSteps[i].id)) {
            return i;
        }
    }
    return tutorialSteps.length; // All completed
}

function startTutorial() {
    const firstIncompleteStep = findFirstIncompleteStep();
    if (firstIncompleteStep === tutorialSteps.length) {
        showTutorialCompletion();
    } else {
        showStep(firstIncompleteStep);
    }
}

function showStep(stepIndex) {
    // Hide all steps
    document.querySelectorAll('.tutorial-step').forEach(step => {
        step.style.display = 'none';
    });
    
    // Show current step
    const steps = document.querySelectorAll('.tutorial-step');
    if (steps[stepIndex]) {
        steps[stepIndex].style.display = 'block';
        currentStepIndex = stepIndex;
        
        // Update step indicators
        updateStepIndicators();
        
        // Scroll to step
        steps[stepIndex].scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function updateStepIndicators() {
    document.querySelectorAll('.step-dot').forEach((dot, index) => {
        dot.classList.remove('active');
        if (index === currentStepIndex) {
            dot.classList.add('active');
        }
    });
}

function completeStep(stepId) {
    fetch('/tutorial/complete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ step_id: stepId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mark step as completed
            const currentStep = document.querySelector(`[data-step="${stepId}"]`);
            currentStep.classList.add('completed');
            
            // Update step indicator
            const currentDot = document.querySelector(`.step-dot[data-step="${currentStepIndex}"]`);
            currentDot.classList.add('completed');
            
            // Add to completed steps array
            if (!completedSteps.includes(stepId)) {
                completedSteps.push(stepId);
            }
            
            updateProgressBar();
            nextStep();
        }
    })
    .catch(error => {
        console.error('Error completing step:', error);
        alert('Error completing step. Please try again.');
    });
}

function skipStep(stepId) {
    fetch('/tutorial/skip', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ step_id: stepId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add to completed steps array (skipped counts as completed)
            if (!completedSteps.includes(stepId)) {
                completedSteps.push(stepId);
            }
            
            updateProgressBar();
            nextStep();
        } else {
            alert(data.error || 'This step cannot be skipped.');
        }
    })
    .catch(error => {
        console.error('Error skipping step:', error);
    });
}

function nextStep() {
    if (currentStepIndex < totalSteps - 1) {
        showStep(currentStepIndex + 1);
    } else {
        showTutorialCompletion();
    }
}

function previousStep() {
    if (currentStepIndex > 0) {
        showStep(currentStepIndex - 1);
    }
}

function finishTutorial(stepId) {
    completeStep(stepId);
}

function showTutorialCompletion() {
    // Hide all steps and navigation
    document.querySelectorAll('.tutorial-step').forEach(step => {
        step.style.display = 'none';
    });
    document.querySelector('.tutorial-navigation').style.display = 'none';
    
    // Show completion screen
    document.querySelector('.tutorial-completion').style.display = 'block';
    
    // Scroll to completion
    document.querySelector('.tutorial-completion').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
}

function updateProgressBar() {
    const progress = (completedSteps.length / totalSteps) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
    document.getElementById('progressText').textContent = `${completedSteps.length} of ${totalSteps} completed`;
}

function skipAllTutorials() {
    if (confirm('Are you sure you want to skip all tutorial steps? You can always access this tutorial later from the help section.')) {
        // Mark all skippable steps as skipped
        const skipPromises = tutorialSteps.map(step => {
            if (step.is_skippable && !completedSteps.includes(step.id)) {
                return fetch('/tutorial/skip', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ step_id: step.id })
                });
            }
            return Promise.resolve();
        });
        
        Promise.all(skipPromises).then(() => {
            window.location.href = '{{ route("dashboard") }}';
        });
    }
}

function exitTutorial() {
    if (confirm('Are you sure you want to exit the tutorial? Your progress will be saved.')) {
        window.location.href = '{{ route("dashboard") }}';
    }
}

// Allow clicking on step dots to navigate
document.querySelectorAll('.step-dot').forEach((dot, index) => {
    dot.addEventListener('click', function() {
        showStep(index);
    });
});
</script>
@endsection