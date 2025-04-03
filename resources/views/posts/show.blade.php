@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>{{ $post->title }}</h1>
        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back to Posts</a>
            <div>
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <p class="card-text">{{ $post->content }}</p>
            </div>
        </div>
    </div>
</div>
@endsection 