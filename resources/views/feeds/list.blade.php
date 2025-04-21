@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1>RSS Feeds</h1>
            <p class="lead">Subscribe to our RSS feeds to stay updated with the latest content.</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5>Available Feeds</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">All Posts</h5>
                                <a href="{{ route('feeds.index') }}" class="btn btn-outline-danger" target="_blank">
                                    <i class="bi bi-rss"></i> Subscribe
                                </a>
                            </div>
                            <p class="mb-1">Get all the latest posts published on our platform.</p>
                            <small class="text-muted">Updated with every new post</small>
                        </div>
                        
                        @foreach($categories as $category)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $category->name }}</h5>
                                <a href="{{ route('feeds.category', $category) }}" class="btn btn-outline-danger" target="_blank">
                                    <i class="bi bi-rss"></i> Subscribe
                                </a>
                            </div>
                            <p class="mb-1">{{ $category->description }}</p>
                            <small class="text-muted">{{ $category->posts_count }} posts in this category</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5>About RSS Feeds</h5>
                </div>
                <div class="card-body">
                    <p>RSS (Really Simple Syndication) allows you to subscribe to updates from websites you care about.</p>
                    
                    <h6 class="mt-3">How to use RSS feeds:</h6>
                    <ol>
                        <li>Choose an RSS reader app or service</li>
                        <li>Copy the feed URL from one of our feeds</li>
                        <li>Add the feed URL to your RSS reader</li>
                        <li>Get automatic updates when new content is published</li>
                    </ol>
                    
                    <h6 class="mt-3">Popular RSS Readers:</h6>
                    <ul>
                        <li>Feedly</li>
                        <li>Inoreader</li>
                        <li>NewsBlur</li>
                        <li>The Old Reader</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 