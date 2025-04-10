@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notifications</h5>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-primary">Mark All as Read</button>
                        </form>
                    @endif
                </div>
                <div class="card-body">
                    @if($notifications->isEmpty())
                        <div class="text-center py-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-bell text-muted mb-3" viewBox="0 0 16 16">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                            </svg>
                            <h4>No notifications</h4>
                            <p class="text-muted">You don't have any notifications yet.</p>
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'list-group-item-primary' }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <h6 class="mb-1">
                                            @if(!$notification->read_at)
                                                <span class="badge bg-primary me-2">New</span>
                                            @endif
                                            
                                            @if($notification->type == 'App\Notifications\NewCommentNotification')
                                                <strong>{{ $notification->data['user_name'] }}</strong> commented on your post
                                            @elseif($notification->type == 'App\Notifications\CommentReplyNotification')
                                                <strong>{{ $notification->data['user_name'] }}</strong> replied to your comment
                                            @elseif($notification->type == 'App\Notifications\PostLikedNotification')
                                                <strong>{{ $notification->data['user_name'] }}</strong> liked your post
                                            @endif
                                        </h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    
                                    <p class="mb-1">
                                        @if($notification->type == 'App\Notifications\NewCommentNotification')
                                            <em>"{{ $notification->data['comment_excerpt'] }}"</em> 
                                            on <strong>{{ $notification->data['post_title'] }}</strong>
                                        @elseif($notification->type == 'App\Notifications\CommentReplyNotification')
                                            <em>"{{ $notification->data['reply_excerpt'] }}"</em>
                                        @elseif($notification->type == 'App\Notifications\PostLikedNotification')
                                            Your post <strong>{{ $notification->data['post_title'] }}</strong>
                                        @endif
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div>
                                            @if($notification->type == 'App\Notifications\NewCommentNotification')
                                                <a href="{{ route('posts.show', $notification->data['post_id']) }}#comment-{{ $notification->data['comment_id'] }}" class="btn btn-sm btn-primary">
                                                    View
                                                </a>
                                            @elseif($notification->type == 'App\Notifications\CommentReplyNotification')
                                                <a href="{{ route('posts.show', $notification->data['post_id']) }}#comment-{{ $notification->data['reply_id'] }}" class="btn btn-sm btn-primary">
                                                    View
                                                </a>
                                            @elseif($notification->type == 'App\Notifications\PostLikedNotification')
                                                <a href="{{ route('posts.show', $notification->data['post_id']) }}" class="btn btn-sm btn-primary">
                                                    View
                                                </a>
                                            @endif
                                            
                                            @if(!$notification->read_at)
                                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">Mark as Read</button>
                                                </form>
                                            @endif
                                        </div>
                                        
                                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 