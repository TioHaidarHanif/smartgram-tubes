@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-primary">Contact Us</h1>
            <p class="lead">Get in touch with the Smartgram team</p>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3>Send us a message</h3>
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Get in Touch</h5>
                        <p class="text-muted">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                        
                        <div class="mt-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-envelope text-primary me-3"></i>
                                <span>support@smartgram.com</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-question-circle text-primary me-3"></i>
                                <a href="{{ route('help.index') }}">Help Center</a>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-comments text-primary me-3"></i>
                                <a href="{{ route('forum.index') }}">Community Forum</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection