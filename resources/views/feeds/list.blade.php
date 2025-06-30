@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ __('RSS Feeds') }}</h4>
                </div>

                <div class="card-body">
                    <p>Subscribe to our RSS feeds to stay updated with the latest content.</p>
                    
                    <h5 class="mt-4">Main Feed</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-rss text-warning" viewBox="0 0 16 16">
                                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                <path d="M5.5 12a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm-3-8.5a1 1 0 0 1 1-1c5.523 0 10 4.477 10 10a1 1 0 1 1-2 0 8 8 0 0 0-8-8 1 1 0 0 1-1-1zm0 4a1 1 0 0 1 1-1 6 6 0 0 1 6 6 1 1 0 1 1-2 0 4 4 0 0 0-4-4 1 1 0 0 1-1-1z"/>
                            </svg>
                        </div>
                        <div>
                            <a href="{{ route('feeds.index') }}" class="text-decoration-none">All Posts Feed</a>
                            <p class="text-muted small mb-0">Latest posts from all categories</p>
                        </div>
                    </div>
                    
                    @if($categories->count() > 0)
                        <h5 class="mt-4">Category Feeds</h5>
                        @foreach($categories as $category)
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-rss text-warning" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                        <path d="M5.5 12a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm-3-8.5a1 1 0 0 1 1-1c5.523 0 10 4.477 10 10a1 1 0 1 1-2 0 8 8 0 0 0-8-8 1 1 0 0 1-1-1zm0 4a1 1 0 0 1 1-1 6 6 0 0 1 6 6 1 1 0 1 1-2 0 4 4 0 0 0-4-4 1 1 0 0 1-1-1z"/>
                                    </svg>
                                </div>
                                <div>
                                    <a href="{{ route('feeds.category', $category->slug) }}" class="text-decoration-none">{{ $category->name }} Feed</a>
                                    <p class="text-muted small mb-0">{{ $category->posts_count }} posts</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    
                    <h5 class="mt-4">How to Use RSS Feeds</h5>
                    <p>You can subscribe to these feeds using any RSS reader. Here are some popular options:</p>
                    <ul>
                        <li><a href="https://feedly.com" target="_blank" rel="noopener noreferrer">Feedly</a></li>
                        <li><a href="https://www.inoreader.com" target="_blank" rel="noopener noreferrer">Inoreader</a></li>
                        <li><a href="https://newsblur.com" target="_blank" rel="noopener noreferrer">NewsBlur</a></li>
                        <li>Browser extensions for Firefox, Chrome, or Safari</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 