@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <!-- Post Content -->
            <div class="card border-0 shadow-sm mb-4">
                @if($post->image_path)
                    <img src="{{ asset('storage/' . $post->image_path) }}" 
                         alt="{{ $post->title }}" 
                         class="card-img-top" 
                         style="max-height: 500px; object-fit: cover;">
                @endif
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('categories.show', $post->category) }}" class="badge bg-primary text-decoration-none">
                            {{ $post->category->name }}
                        </a>
                        <div class="text-muted small">
                            <i class="fas fa-calendar-alt me-1"></i> {{ $post->created_at->format('M d, Y') }}
                        </div>
                    </div>
                    
                    <h1 class="card-title fw-bold mb-4">{{ $post->title }}</h1>
                    
                    <div class="d-flex align-items-center mb-4">
                        <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none d-flex align-items-center">
                            @if($post->user->avatar_path)
                                <img src="{{ asset('storage/' . $post->user->avatar_path) }}" alt="{{ $post->user->name }}" class="rounded-circle me-2" width="40" height="40">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold text-dark">{{ $post->user->name }}</div>
                                <div class="text-muted small">{{ $post->user->bio ? Str::limit($post->user->bio, 40) : 'Author' }}</div>
                            </div>
                        </a>
                        
                        <div class="ms-auto">
                            @can('update', $post)
                            <div class="btn-group">
                                <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this post?')">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                            @endcan
                        </div>
                    </div>
                    
                    <div class="card-text mb-4 post-content">
                        {!! $post->content !!}
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <div class="d-flex align-items-center">
                            <form action="{{ route('posts.like', $post) }}" method="POST" id="like-form" class="me-3">
                                @csrf
                                <button type="submit" class="btn {{ $post->isLikedByUser() ? 'btn-danger' : 'btn-outline-danger' }}" id="like-button">
                                    <i class="fas fa-heart{{ $post->isLikedByUser() ? '' : '-o' }} me-1"></i>
                                    <span id="likes-count">{{ $post->likes()->count() }}</span>
                                </button>
                            </form>
                            
                            <div>
                                <i class="fas fa-comment text-primary me-1"></i>
                                <span>{{ $post->comments()->count() }} {{ Str::plural('Comment', $post->comments()->count()) }}</span>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <a href="#" class="btn btn-sm btn-outline-secondary me-2" onclick="shareOnFacebook(); return false;">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-secondary me-2" onclick="shareOnTwitter(); return false;">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard(); return false;">
                                <i class="fas fa-link"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title m-0 fw-bold">
                        <i class="fas fa-comments me-2 text-primary"></i>
                        Comments ({{ $post->comments()->count() }})
                    </h3>
                </div>
                <div class="card-body p-4">
                    @auth
                    <!-- Comment Form -->
                    <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="d-flex mb-3">
                            @if(Auth::user()->avatar_path)
                                <img src="{{ asset('storage/' . Auth::user()->avatar_path) }}" alt="{{ Auth::user()->name }}" class="rounded-circle me-2" width="40" height="40">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <textarea name="content" 
                                        class="form-control @error('content') is-invalid @enderror" 
                                        rows="3" 
                                        placeholder="Write a comment..."
                                        required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Post Comment
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            Please <a href="{{ route('login') }}" class="alert-link">login</a> to leave a comment.
                        </div>
                    </div>
                    @endauth

                    <!-- Comments List -->
                    @forelse($post->comments()->with(['user', 'replies.user'])->whereNull('parent_id')->latest()->get() as $comment)
                        <div class="comment mb-4" id="comment-{{ $comment->id }}">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <a href="{{ route('profile.show', $comment->user) }}" class="text-decoration-none">
                                            @if($comment->user->avatar_path)
                                                <img src="{{ asset('storage/' . $comment->user->avatar_path) }}" alt="{{ $comment->user->name }}" class="rounded-circle me-2" width="40" height="40">
                                            @else
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </a>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <a href="{{ route('profile.show', $comment->user) }}" class="fw-bold text-decoration-none text-dark">{{ $comment->user->name }}</a>
                                                    <div class="text-muted small">{{ $comment->created_at->diffForHumans() }}</div>
                                                </div>
                                                @can('update', $comment)
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><button class="dropdown-item" onclick="editComment({{ $comment->id }})"><i class="fas fa-edit me-2"></i>Edit</button></li>
                                                            <li>
                                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                                        <i class="fas fa-trash-alt me-2"></i>Delete
                                                                    </button>
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
                                                    <button class="btn btn-sm btn-link p-0 text-primary" onclick="toggleReplyForm({{ $comment->id }})">
                                                        <i class="fas fa-reply me-1"></i> Reply
                                                    </button>
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
                                                        <div class="reply mb-3" id="comment-{{ $reply->id }}">
                                                            <div class="d-flex">
                                                                <a href="{{ route('profile.show', $reply->user) }}" class="text-decoration-none">
                                                                    @if($reply->user->avatar_path)
                                                                        <img src="{{ asset('storage/' . $reply->user->avatar_path) }}" alt="{{ $reply->user->name }}" class="rounded-circle me-2" width="30" height="30">
                                                                    @else
                                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                                                            {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                                        </div>
                                                                    @endif
                                                                </a>
                                                                <div class="flex-grow-1">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <a href="{{ route('profile.show', $reply->user) }}" class="fw-bold text-decoration-none text-dark">{{ $reply->user->name }}</a>
                                                                            <div class="text-muted small">{{ $reply->created_at->diffForHumans() }}</div>
                                                                        </div>
                                                                        @can('delete', $reply)
                                                                            <form action="{{ route('comments.destroy', $reply) }}" method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Are you sure you want to delete this reply?')">
                                                                                    <i class="fas fa-times"></i>
                                                                                </button>
                                                                            </form>
                                                                        @endcan
                                                                    </div>
                                                                    <p class="mb-0 mt-1">{{ $reply->content }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted my-4">
                            <i class="fas fa-comment-slash fa-3x mb-3"></i>
                            <p>No comments yet. Be the first to comment!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <!-- Author Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <h5 class="card-title fw-bold mb-3">About the Author</h5>
                    <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none">
                        @if($post->user->avatar_path)
                            <img src="{{ asset('storage/' . $post->user->avatar_path) }}" alt="{{ $post->user->name }}" class="rounded-circle mb-3" width="80" height="80">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <h5 class="mb-1 text-dark">{{ $post->user->name }}</h5>
                    </a>
                    <p class="text-muted small">
                        {{ $post->user->bio ?? 'No bio available' }}
                    </p>
                    <div class="d-flex justify-content-center">
                        @if($post->user->website)
                            <a href="{{ $post->user->website }}" class="btn btn-sm btn-outline-secondary me-2" target="_blank">
                                <i class="fas fa-globe me-1"></i> Website
                            </a>
                        @endif
                        <a href="{{ route('profile.show', $post->user) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-user me-1"></i> View Profile
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Related Posts -->
            @php
                $relatedPosts = \App\Models\Post::where('category_id', $post->category_id)
                    ->where('id', '!=', $post->id)
                    ->latest()
                    ->take(3)
                    ->get();
            @endphp
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 fw-bold">Related Posts</h5>
                </div>
                <div class="card-body p-0">
                    @if($relatedPosts->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($relatedPosts as $relatedPost)
                                <li class="list-group-item p-3">
                                    <a href="{{ route('posts.show', $relatedPost) }}" class="text-decoration-none">
                                        <div class="row g-0">
                                            @if($relatedPost->image_path)
                                                <div class="col-3">
                                                    <img src="{{ asset('storage/' . $relatedPost->image_path) }}" alt="{{ $relatedPost->title }}" class="img-fluid rounded" style="height: 60px; object-fit: cover;">
                                                </div>
                                                <div class="col-9 ps-3">
                                            @else
                                                <div class="col-12">
                                            @endif
                                                <h6 class="mb-1 text-dark">{{ Str::limit($relatedPost->title, 50) }}</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">{{ $relatedPost->created_at->format('M d, Y') }}</small>
                                                    <small class="text-muted">
                                                        <i class="fas fa-comment me-1"></i> {{ $relatedPost->comments()->count() }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted p-4">
                            No related posts found.
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="{{ route('categories.show', $post->category) }}" class="btn btn-outline-primary btn-sm">
                        More in {{ $post->category->name }} <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            
            <!-- Categories -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 fw-bold">Categories</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(\App\Models\Category::withCount('posts')->get() as $category)
                            <a href="{{ route('categories.show', $category) }}" class="badge bg-light text-dark text-decoration-none p-2">
                                {{ $category->name }} ({{ $category->posts_count }})
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .post-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }
    
    .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
    }
    
    .post-content h2, .post-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }
    
    .post-content blockquote {
        border-left: 4px solid var(--primary);
        padding-left: 1rem;
        font-style: italic;
        color: var(--gray-600);
        margin: 1.5rem 0;
    }
</style>
@endsection

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
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    likesCount.textContent = data.count;
                    
                    if (data.liked) {
                        likeButton.classList.remove('btn-outline-danger');
                        likeButton.classList.add('btn-danger');
                        likeButton.querySelector('i').classList.remove('fa-heart-o');
                        likeButton.querySelector('i').classList.add('fa-heart');
                    } else {
                        likeButton.classList.remove('btn-danger');
                        likeButton.classList.add('btn-outline-danger');
                        likeButton.querySelector('i').classList.remove('fa-heart');
                        likeButton.querySelector('i').classList.add('fa-heart-o');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});

// Sosyal paylaşım fonksiyonları
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('{{ $post->title }}');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
}

function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link copied to clipboard!');
    });
}
</script>
@endpush 