@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Categories</h1>
            @auth
                <a href="{{ route('categories.create') }}" class="btn btn-primary">Create New Category</a>
            @endauth
        </div>
    </div>

    <div class="row">
        @foreach($categories as $category)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $category->name }}</h5>
                    <p class="card-text">{{ $category->description }}</p>
                    <p class="text-muted">{{ $category->posts_count }} posts</p>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-info">View Posts</a>
                        @auth
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection 