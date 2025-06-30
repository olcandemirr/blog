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
                            <a class="nav-link" href="{{ route('profile.posts') }}">Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('profile.comments') }}">Comments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('likes.posts') }}">Liked Posts</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <h5 class="card-title">My Comments</h5>
                    
                    @if($comments->count() > 0)
                        <div class="list-group mt-3">
                            @foreach($comments as $comment)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            On post: <a href="{{ route('posts.show', $comment->post) }}">{{ $comment->post->title }}</a>
                                        </h6>
                                        <small>{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $comment->content }}</p>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-sm btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $comment->id }}">Edit</button>
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Edit Comment Modal -->
                                <div class="modal fade" id="editCommentModal{{ $comment->id }}" tabindex="-1" aria-labelledby="editCommentModalLabel{{ $comment->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCommentModalLabel{{ $comment->id }}">Edit Comment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('comments.update', $comment) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="content{{ $comment->id }}" class="form-label">Comment</label>
                                                        <textarea class="form-control" id="content{{ $comment->id }}" name="content" rows="3" required>{{ $comment->content }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            {{ $comments->links() }}
                        </div>
                    @else
                        <div class="alert alert-info mt-3">
                            You haven't made any comments yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 