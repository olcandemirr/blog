@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1>All Posts</h1>
            @auth
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Create New Post</a>
            @endauth
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($posts as $post)
        <div class="col">
            <div class="card h-100">
                @if($post->image_path)
                    <img src="{{ $post->image_url }}" 
                         alt="{{ $post->title }}" 
                         class="card-img-top" 
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span class="text-muted">No image</span>
                    </div>
                @endif
                
                <div class="card-body">
                    <div class="mb-2">
                        <a href="{{ route('categories.show', $post->category) }}" class="badge bg-primary text-decoration-none">
                            {{ $post->category->name }}
                        </a>
                    </div>
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                        <span class="text-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                            </svg>
                            {{ $post->likes()->count() }}
                        </span>
                    </div>
                    <p class="card-text">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                </div>
                
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                {{ substr($post->user->name, 0, 1) }}
                            </div>
                            <small class="text-muted">{{ $post->user->name }}</small>
                        </div>
                        <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                    </div>
                    
                    <div class="d-flex mt-3">
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary w-100">Read More</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                No posts found. Be the first to <a href="{{ route('posts.create') }}" class="alert-link">create a post</a>!
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection 