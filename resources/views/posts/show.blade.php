@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Post Content -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="card-title mb-0">{{ $post->title }}</h1>
                        @can('update', $post)
                        <div class="btn-group">
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                            </form>
                        </div>
                        @endcan
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                {{ substr($post->user->name, 0, 1) }}
                            </div>
                        </a>
                        <div>
                            <a href="{{ route('profile.show', $post->user) }}" class="fw-bold text-decoration-none">{{ $post->user->name }}</a>
                            <div class="text-muted small">
                                Posted in <a href="{{ route('categories.show', $post->category) }}" class="text-decoration-none">{{ $post->category->name }}</a> • 
                                {{ $post->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Beğeni butonu ve sayacı -->
                    <div class="d-flex align-items-center mb-4">
                        <form action="{{ route('posts.like', $post) }}" method="POST" id="like-form">
                            @csrf
                            <button type="submit" class="btn {{ $post->isLikedByUser() ? 'btn-danger' : 'btn-outline-danger' }}" id="like-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart{{ $post->isLikedByUser() ? '-fill' : '' }}" viewBox="0 0 16 16">
                                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                </svg>
                                <span id="likes-count">{{ $post->likes()->count() }}</span> {{ Str::plural('Like', $post->likes()->count()) }}
                            </button>
                        </form>
                    </div>
                    
                    @if($post->image_path)
                        <div class="mb-4 text-center">
                            <img src="{{ $post->image_url }}" 
                                 alt="{{ $post->title }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 400px;">
                        </div>
                    @endif
                    
                    <div class="card-text">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-4">Comments</h3>
                    
                    @auth
                    <!-- Comment Form -->
                    <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="form-group">
                            <textarea name="content" 
                                      class="form-control @error('content') is-invalid @enderror" 
                                      rows="3" 
                                      placeholder="Write a comment..."
                                      required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Post Comment</button>
                    </form>
                    @else
                    <div class="alert alert-info">
                        Please <a href="{{ route('login') }}" class="alert-link">login</a> to leave a comment.
                    </div>
                    @endauth

                    <!-- Comments List -->
                    @forelse($post->comments()->with(['user', 'replies.user'])->whereNull('parent_id')->latest()->get() as $comment)
                        <div class="comment mb-4" id="comment-{{ $comment->id }}">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                {{ substr($comment->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $comment->user->name }}</div>
                                                <div class="text-muted small">{{ $comment->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        @can('update', $comment)
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><button class="dropdown-item" onclick="editComment({{ $comment->id }})">Edit</button></li>
                                                    <li>
                                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endcan
                                    </div>
                                    
                                    <div class="mt-2">
                                        <p class="mb-0" id="comment-content-{{ $comment->id }}">{{ $comment->content }}</p>
                                        
                                        <!-- Edit Form -->
                                        <form action="{{ route('comments.update', $comment) }}" 
                                              method="POST" 
                                              class="d-none mt-2" 
                                              id="edit-form-{{ $comment->id }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <textarea name="content" 
                                                          class="form-control" 
                                                          rows="3" 
                                                          required>{{ $comment->content }}</textarea>
                                            </div>
                                            <div class="mt-2">
                                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                <button type="button" class="btn btn-sm btn-secondary" onclick="cancelEdit({{ $comment->id }})">Cancel</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="mt-2">
                                        @auth
                                            <button class="btn btn-sm btn-link p-0" onclick="toggleReplyForm({{ $comment->id }})">Reply</button>
                                        @endauth
                                    </div>

                                    <!-- Reply Form -->
                                    @auth
                                        <form action="{{ route('posts.comments.store', $post) }}" 
                                              method="POST" 
                                              class="mt-3 d-none" 
                                              id="reply-form-{{ $comment->id }}">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <div class="form-group">
                                                <textarea name="content" 
                                                          class="form-control" 
                                                          rows="2" 
                                                          placeholder="Write a reply..."
                                                          required></textarea>
                                            </div>
                                            <div class="mt-2">
                                                <button type="submit" class="btn btn-sm btn-primary">Reply</button>
                                                <button type="button" class="btn btn-sm btn-secondary" onclick="toggleReplyForm({{ $comment->id }})">Cancel</button>
                                            </div>
                                        </form>
                                    @endauth

                                    <!-- Replies -->
                                    @if($comment->replies->count() > 0)
                                        <div class="ms-4 mt-3 border-start ps-3">
                                            @foreach($comment->replies as $reply)
                                                <div class="reply mb-3">
                                                    <div class="d-flex align-items-start">
                                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 12px;">
                                                            {{ substr($reply->user->name, 0, 1) }}
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-bold">{{ $reply->user->name }}</div>
                                                            <div class="text-muted small">{{ $reply->created_at->diffForHumans() }}</div>
                                                            <p class="mb-0 mt-1">{{ $reply->content }}</p>
                                                        </div>
                                                        @can('delete', $reply)
                                                            <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="ms-2">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Are you sure you want to delete this reply?')">Delete</button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted my-4">
                            No comments yet. Be the first to comment!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editComment(commentId) {
    document.getElementById(`comment-content-${commentId}`).classList.add('d-none');
    document.getElementById(`edit-form-${commentId}`).classList.remove('d-none');
}

function cancelEdit(commentId) {
    document.getElementById(`comment-content-${commentId}`).classList.remove('d-none');
    document.getElementById(`edit-form-${commentId}`).classList.add('d-none');
}

function toggleReplyForm(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    form.classList.toggle('d-none');
}

// Like AJAX işlemi
document.addEventListener('DOMContentLoaded', function() {
    const likeForm = document.getElementById('like-form');
    const likeButton = document.getElementById('like-button');
    const likesCount = document.getElementById('likes-count');
    
    if (likeForm) {
        likeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(likeForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Güncellenen beğeni sayısını göster
                    likesCount.textContent = data.likesCount;
                    
                    // Buton stilini güncelle
                    if (data.liked) {
                        likeButton.classList.remove('btn-outline-danger');
                        likeButton.classList.add('btn-danger');
                        likeButton.querySelector('svg').classList.add('bi-heart-fill');
                        likeButton.querySelector('svg').classList.remove('bi-heart');
                    } else {
                        likeButton.classList.add('btn-outline-danger');
                        likeButton.classList.remove('btn-danger');
                        likeButton.querySelector('svg').classList.remove('bi-heart-fill');
                        likeButton.querySelector('svg').classList.add('bi-heart');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
</script>
@endpush
@endsection 