@extends('layouts.app')

@section('title', 'Welcome to Smartgram')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Hero Section -->
        <div class="card mb-5">
            <div class="card-body text-center py-5">
                <h1 class="display-4 fw-bold text-primary mb-4">
                    <i class="fas fa-graduation-cap me-3"></i>
                    Welcome to Smartgram
                </h1>
                <p class="lead mb-4">
                    A social media platform designed as an e-social learning hub for knowledge sharing.
                    Connect with learners, mentors, and content creators worldwide.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-user-plus me-2"></i>Get Started
                    </a>
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-lg px-4">
                        <i class="fas fa-eye me-2"></i>Explore Content
                    </a>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center mb-5">Why Choose Smartgram?</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <div class="text-primary mb-3">
                                    <i class="fas fa-users fa-3x"></i>
                                </div>
                                <h5 class="card-title">Social Learning</h5>
                                <p class="card-text">
                                    Connect with learners, mentors, and content creators to build meaningful learning relationships.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <div class="text-primary mb-3">
                                    <i class="fas fa-book-open fa-3x"></i>
                                </div>
                                <h5 class="card-title">Quality Content</h5>
                                <p class="card-text">
                                    Share and discover high-quality educational content across various categories and topics.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <div class="text-primary mb-3">
                                    <i class="fas fa-comments fa-3x"></i>
                                </div>
                                <h5 class="card-title">Interactive Discussion</h5>
                                <p class="card-text">
                                    Engage in meaningful discussions through comments, forums, and direct messaging.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($featuredPosts) && $featuredPosts->count() > 0)
        <!-- Featured Posts Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Featured Content</h3>
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-primary">
                        View All Posts
                    </a>
                </div>
                <div class="row g-4">
                    @foreach($featuredPosts as $post)
                    <div class="col-md-4">
                        <div class="card h-100">
                            @if($post->media_urls && count($post->media_urls) > 0)
                            <img src="{{ $post->media_urls[0] }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <span class="badge bg-primary mb-2">{{ $post->category->name }}</span>
                                <h5 class="card-title">{{ Str::limit($post->title, 50) }}</h5>
                                <p class="card-text text-muted">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-muted">by {{ $post->user->name }}</small>
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-outline-primary w-100">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if(isset($categories) && $categories->count() > 0)
        <!-- Categories Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="mb-4">Popular Categories</h3>
                <div class="row g-3">
                    @foreach($categories as $category)
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('search') }}?category={{ $category->id }}" class="text-decoration-none">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px; background-color: {{ $category->color }};">
                                        <i class="fas fa-folder text-white fa-lg"></i>
                                    </div>
                                    <h6 class="card-title">{{ $category->name }}</h6>
                                    <small class="text-muted">{{ $category->posts_count }} posts</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Call to Action -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center py-5">
                        <h3 class="mb-3">Ready to Start Learning?</h3>
                        <p class="mb-4">
                            Join thousands of learners and educators who are already part of the Smartgram community.
                            Start your learning journey today!
                        </p>
                        @guest
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Join Now
                            </a>
                            <a href="{{ route('help.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-question-circle me-2"></i>Learn More
                            </a>
                        </div>
                        @else
                        <a href="{{ route('posts.create') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-plus me-2"></i>Create Your First Post
                        </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection