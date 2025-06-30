@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Profile') }}</span>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary">{{ __('Edit Profile') }}</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->email }}</p>
                            
                            @if($user->bio)
                                <p>{{ $user->bio }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.show') }}">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('profile.posts') }}">Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.comments') }}">Comments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('likes.posts') }}">Liked Posts</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <h5 class="card-title">My Posts</h5>
                    
                    @if($posts->count() > 0)
                        <div class="list-group mt-3">
                            @foreach($posts as $post)
                                <a href="{{ route('posts.show', $post) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $post->title }}</h5>
                                        <small>{{ $post->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ Str::limit($post->content, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <span class="badge bg-primary">{{ $post->category->name }}</span>
                                            <span class="ms-2"><i class="bi bi-chat"></i> {{ $post->comments_count }}</span>
                                            <span class="ms-2"><i class="bi bi-heart"></i> {{ $post->likes_count }}</span>
                                        </small>
                                        <div>
                                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="alert alert-info mt-3">
                            You haven't created any posts yet.
                        </div>
                        <a href="{{ route('posts.create') }}" class="btn btn-primary mt-2">Create New Post</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 