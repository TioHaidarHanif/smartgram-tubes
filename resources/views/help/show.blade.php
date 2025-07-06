@extends('layouts.app')

@section('title', $helpTopic->title)

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('help.index') }}">Help</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $helpTopic->title }}</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <h1 class="card-title mb-4">{{ $helpTopic->title }}</h1>
                <div class="content">
                    {!! nl2br(e($helpTopic->content)) !!}
                </div>
            </div>
        </div>

        @if($relatedTopics->count() > 0)
        <div class="mt-4">
            <h4>Related Help Topics</h4>
            <div class="list-group">
                @foreach($relatedTopics as $topic)
                <a href="{{ route('help.show', $topic->slug) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">{{ $topic->title }}</h6>
                        <small><i class="fas fa-arrow-right"></i></small>
                    </div>
                    <p class="mb-1 text-muted">{{ Str::limit(strip_tags($topic->content), 100) }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-4 text-center">
            <a href="{{ route('help.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Help Center
            </a>
        </div>
    </div>
</div>
@endsection