@extends('layouts.app')

@section('title', 'Help Center')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-primary">Help Center</h1>
            <p class="lead">Find answers to your questions and learn how to use Smartgram effectively</p>
        </div>

        @if($helpTopics->count() > 0)
        <div class="row g-4">
            @foreach($helpTopics as $topic)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            {{ $topic->title }}
                        </h5>
                        <p class="card-text text-muted">
                            {{ Str::limit(strip_tags($topic->content), 120) }}
                        </p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('help.show', $topic->slug) }}" class="btn btn-outline-primary">
                            Read More <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
            <h4>No help topics available</h4>
            <p class="text-muted">Help topics will be added soon to assist you with using Smartgram.</p>
        </div>
        @endif

        <!-- Quick Links -->
        <div class="mt-5 pt-5 border-top">
            <h3 class="mb-4">Quick Links</h3>
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('register') }}" class="text-decoration-none">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-user-plus fa-2x text-primary mb-2"></i>
                                <h6>Getting Started</h6>
                                <small class="text-muted">Create your account</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('search') }}" class="text-decoration-none">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-search fa-2x text-primary mb-2"></i>
                                <h6>Find Content</h6>
                                <small class="text-muted">Search for topics</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('forum.index') }}" class="text-decoration-none">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-comments fa-2x text-primary mb-2"></i>
                                <h6>Community</h6>
                                <small class="text-muted">Join discussions</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('contact') }}" class="text-decoration-none">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                                <h6>Contact Us</h6>
                                <small class="text-muted">Get in touch</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection