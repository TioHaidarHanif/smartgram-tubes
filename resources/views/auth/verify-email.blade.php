@extends('layouts.app')

@section('title', 'Verify Your Email Address')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-0">
                    <i class="fas fa-envelope-open text-primary me-2"></i>
                    Verify Your Email Address
                </h4>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="fas fa-envelope fa-4x text-muted mb-3"></i>
                    <h5>Check Your Email</h5>
                    <p class="text-muted">
                        Before proceeding, please check your email for a verification link.
                        If you didn't receive the email, we can send you another one.
                    </p>
                </div>

                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        A fresh verification link has been sent to your email address.
                    </div>
                @endif

                <div class="d-grid gap-3">
                    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-paper-plane me-2"></i>
                            Resend Verification Email
                        </button>
                    </form>

                    <div class="text-center">
                        <p class="text-muted mb-2">Already verified?</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>
                            Go to Dashboard
                        </a>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="mt-5 pt-4 border-top">
                    <h6 class="text-muted mb-3">Need Help?</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-start">
                                <h6 class="text-primary"><i class="fas fa-search me-2"></i>Check Spam Folder</h6>
                                <p class="text-muted small">
                                    Sometimes verification emails end up in your spam or junk folder.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-start">
                                <h6 class="text-primary"><i class="fas fa-clock me-2"></i>Wait a Few Minutes</h6>
                                <p class="text-muted small">
                                    It may take a few minutes for the email to arrive in your inbox.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-start">
                                <h6 class="text-primary"><i class="fas fa-edit me-2"></i>Check Email Address</h6>
                                <p class="text-muted small">
                                    Make sure you entered the correct email address when registering.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-start">
                                <h6 class="text-primary"><i class="fas fa-envelope me-2"></i>Contact Support</h6>
                                <p class="text-muted small">
                                    If you're still having trouble, <a href="{{ route('contact') }}">contact our support team</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Benefits of Verification -->
                <div class="mt-4 pt-4 border-top">
                    <h6 class="text-muted mb-3">Why Verify Your Email?</h6>
                    <div class="row text-start">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <i class="fas fa-shield-alt text-success me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Account Security</h6>
                                    <small class="text-muted">Protect your account from unauthorized access</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <i class="fas fa-bell text-info me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Notifications</h6>
                                    <small class="text-muted">Receive important updates and notifications</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <i class="fas fa-key text-warning me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Password Recovery</h6>
                                    <small class="text-muted">Reset your password if you forget it</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <i class="fas fa-users text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Full Access</h6>
                                    <small class="text-muted">Access all features of the platform</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alternative Actions -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="card-title">Alternative Actions</h6>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                    <a href="{{ route('help.index') }}" class="btn btn-outline-info">
                        <i class="fas fa-question-circle me-2"></i>Help
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh page status every 30 seconds to check if email was verified
setInterval(function() {
    fetch('/email/verify/status', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.verified) {
            window.location.href = '{{ route("dashboard") }}';
        }
    })
    .catch(error => {
        // Silently fail - user can manually refresh or click dashboard link
        console.log('Status check failed:', error);
    });
}, 30000);

// Show loading state when resending email
document.querySelector('form').addEventListener('submit', function(e) {
    const button = e.target.querySelector('button');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
    
    // Re-enable button after 5 seconds (in case form submission fails)
    setTimeout(function() {
        button.disabled = false;
        button.innerHTML = originalText;
    }, 5000);
});
</script>
@endsection