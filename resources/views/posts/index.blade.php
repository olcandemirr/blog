@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Posts</h1>
        @auth
            <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Create New Post</a>
        @endauth
    </div>
</div>

<div class="row">
    @foreach($posts as $post)
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $post->title }}</h5>
                <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                <p class="text-muted">By: {{ $post->user->name }}</p>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info">View</a>
                    @can('update', $post)
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning">Edit</a>
                    @endcan
                    @can('delete', $post)
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection 