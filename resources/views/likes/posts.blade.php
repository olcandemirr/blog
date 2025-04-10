@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Posts You Liked</h1>
        </div>
    </div>

    @if($likedPosts->isEmpty())
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="text-center py-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-heart text-muted mb-3" viewBox="0 0 16 16">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                    </svg>
                    <h4>No liked posts yet</h4>
                    <p class="text-muted">You haven't liked any posts yet.</p>
                    <a href="{{ route('posts.index') }}" class="btn btn-primary">Browse Posts</a>
                </div>
            </div>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($likedPosts as $post)
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
                            <small class="text-muted float-end">
                                <i class="bi bi-heart-fill text-danger"></i> 
                                {{ $post->likes()->count() }}
                            </small>
                        </div>
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                    </div>
                    
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="rounded-circle me-2" width="30" height="30">
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
            @endforeach
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                {{ $likedPosts->links() }}
            </div>
        </div>
    @endif
</div>
@endsection 