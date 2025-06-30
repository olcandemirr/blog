@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-primary position-relative overflow-hidden p-5 mb-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h1 class="display-4 fw-bold text-white mb-3">
                    Welcome to {{ config('app.name', 'Laravel Blog') }}
                </h1>
                <p class="lead text-white-50 mb-4">
                    Share your thoughts, ideas, and stories with the world. Join our community of writers and readers.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    @auth
                        <a href="{{ route('posts.create') }}" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-pen-to-square me-2"></i>Create New Post
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-user-plus me-2"></i>Get Started
                        </a>
                    @endauth
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-list-ul me-2"></i>View All Posts
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://placehold.co/600x400/3b82f6/FFFFFF?text=Blog+Platform" alt="Blog Platform" class="img-fluid rounded shadow-lg" style="max-height: 400px;">
            </div>
        </div>
    </div>
    <div class="position-absolute bottom-0 end-0 d-none d-lg-block">
        <svg width="350" height="350" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" style="opacity: 0.1;">
            <path fill="#FFFFFF" d="M41.3,-70.9C54.4,-64.3,66.5,-55.3,73.8,-42.9C81.1,-30.6,83.5,-15.3,83.2,-0.2C82.9,14.9,79.8,29.9,72.3,42.3C64.8,54.8,52.8,64.8,39.5,70.8C26.1,76.8,11.5,78.9,-1.9,81.9C-15.3,84.8,-27.7,88.6,-39.9,85.5C-52.1,82.3,-64.1,72.1,-71.5,59.1C-78.8,46.1,-81.6,30.3,-82.5,15.2C-83.4,0.1,-82.4,-14.3,-77.7,-27.4C-73,-40.5,-64.5,-52.3,-53.1,-60.1C-41.6,-67.9,-27.1,-71.7,-13.5,-71.6C0.1,-71.5,13.7,-67.6,28.3,-77.5C42.9,-87.3,28.3,-77.5,41.3,-70.9Z" transform="translate(100 100)" />
        </svg>
    </div>
</div>

<!-- RSS Feed Subscribe -->
<div class="container mb-5">
    <div class="d-flex justify-content-end">
        <a href="{{ route('feeds.list') }}" class="btn btn-outline-primary">
            <i class="fas fa-rss me-2"></i>Subscribe to RSS Feeds
        </a>
    </div>
</div>

<!-- Popular Categories Section -->
<div class="container mb-5">
    <div class="text-center mb-4">
        <h2 class="display-5 fw-bold">Popular Categories</h2>
        <p class="lead text-muted">Browse posts by your favorite topics</p>
    </div>

    <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
        @foreach($popularCategories as $category)
        <a href="{{ route('categories.show', $category) }}" 
           class="badge rounded-pill fs-6 text-decoration-none py-2 px-3 
                  {{ $category->posts_count > 10 ? 'bg-primary text-white' : 'bg-light text-primary' }}">
            {{ $category->name }}
            <span class="badge {{ $category->posts_count > 10 ? 'bg-white text-primary' : 'bg-primary text-white' }} rounded-pill ms-2">
                {{ $category->posts_count }}
            </span>
        </a>
        @endforeach
    </div>
    
    <div class="text-center">
        <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
            View All Categories <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
</div>

<!-- Featured Posts Section -->
<div class="bg-light py-5 mb-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="display-5 fw-bold">Featured Posts</h2>
            <p class="lead text-muted">Check out our latest and most popular blog posts</p>
        </div>

        <div class="row g-4">
            @foreach($featuredPosts as $post)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    @if($post->image_path)
                    <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                    @else
                    <div class="bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-image fa-3x text-secondary opacity-50"></i>
                    </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <a href="{{ route('categories.show', $post->category) }}" class="badge bg-primary text-decoration-none">
                                {{ $post->category->name }}
                            </a>
                            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                        </div>
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                @if($post->user->avatar_path)
                                <img src="{{ asset('storage/' . $post->user->avatar_path) }}" alt="{{ $post->user->name }}" class="rounded-circle me-2" width="30" height="30">
                                @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                                @endif
                                <span class="small">{{ $post->user->name }}</span>
                            </div>
                            <div>
                                <span class="me-2" title="Comments"><i class="fas fa-comment text-secondary"></i> {{ $post->comments_count ?? 0 }}</span>
                                <span title="Likes"><i class="fas fa-heart text-danger"></i> {{ $post->likes_count ?? 0 }}</span>
                            </div>
                        </div>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-primary w-100 mt-3">Read More</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Most Popular Posts Section -->
<div class="container mb-5">
    <div class="text-center mb-4">
        <h2 class="display-5 fw-bold">Most Popular Posts</h2>
        <p class="lead text-muted">Trending content with the most engagement</p>
    </div>

    <div class="row g-4">
        @foreach($popularPosts as $post)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('categories.show', $post->category) }}" class="badge bg-primary text-decoration-none">
                            {{ $post->category->name }}
                        </a>
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-chart-line me-1"></i> Popular
                        </span>
                    </div>
                </div>
                @if($post->image_path)
                <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                @else
                <div class="bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="fas fa-image fa-3x text-secondary opacity-50"></i>
                </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            @if($post->user->avatar_path)
                            <img src="{{ asset('storage/' . $post->user->avatar_path) }}" alt="{{ $post->user->name }}" class="rounded-circle me-2" width="30" height="30">
                            @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            @endif
                            <span class="small">{{ $post->user->name }}</span>
                        </div>
                        <div>
                            <span class="me-2" title="Comments"><i class="fas fa-comment text-secondary"></i> {{ $post->comments_count ?? 0 }}</span>
                            <span title="Likes"><i class="fas fa-heart text-danger"></i> {{ $post->likes_count ?? 0 }}</span>
                        </div>
                    </div>
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary w-100 mt-3">Read More</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Categories Browser Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="display-5 fw-bold">Browse All Categories</h2>
            <p class="lead text-muted">Find content that matches your interests</p>
        </div>

        <div class="row g-4">
            @foreach($allCategories as $category)
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm transition-all hover-lift">
                        <div class="card-body">
                            <h3 class="h5 card-title text-primary">{{ $category->name }}</h3>
                            <p class="card-text text-muted small">{{ Str::limit($category->description ?? 'Explore posts in this category', 60) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-light text-primary">{{ $category->posts_count ?? 0 }} posts</span>
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card bg-primary text-white p-4 p-md-5 border-0 shadow">
                <div class="row align-items-center">
                    <div class="col-md-8 mb-4 mb-md-0">
                        <h2 class="h1 fw-bold">Start Writing Today</h2>
                        <p class="lead mb-0">Join our community and share your stories, ideas, and expertise with readers around the world.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        @auth
                            <a href="{{ route('posts.create') }}" class="btn btn-light btn-lg">Create Post</a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg">Sign Up</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .hover-lift {
        transition: all 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
    }
    .transition-all {
        transition: all 0.2s ease;
    }
</style>
@endsection 