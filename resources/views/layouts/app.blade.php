<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.meta-tags')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .search-container {
            position: relative;
            width: 300px;
        }
        .search-input {
            width: 100%;
            padding: 8px 35px 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
    </style>
    <!-- RSS Feed Links -->
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} - All Posts" href="{{ route('feeds.index') }}" />
    
    @if(isset($category) && $category instanceof \App\Models\Category)
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} - {{ $category->name }}" href="{{ route('feeds.category', $category) }}" />
    @endif
    
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Laravel Blog</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.index') }}">Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">Categories</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('posts.create') }}">Create Post</a>
                        </li>
                    @endauth
                </ul>
                
                <div class="search-container">
                    <form action="{{ route('search') }}" method="GET">
                        <input type="search" 
                               name="q" 
                               class="search-input"
                               placeholder="Search posts..."
                               value="{{ request('q') }}">
                        <span class="search-icon">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </span>
                    </form>
                </div>

                @auth
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                            </svg>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ Auth::user()->unreadNotifications->count() > 99 ? '99+' : Auth::user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 320px;" aria-labelledby="notificationsDropdown">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            
                            @if(Auth::user()->notifications->isEmpty())
                                <li><span class="dropdown-item text-muted">No notifications</span></li>
                            @else
                                @foreach(Auth::user()->notifications->take(5) as $notification)
                                    <li>
                                        <a class="dropdown-item {{ $notification->read_at ? '' : 'bg-light' }}" href="{{ 
                                            $notification->type == 'App\Notifications\NewCommentNotification' 
                                                ? route('posts.show', $notification->data['post_id']) . '#comment-' . $notification->data['comment_id'] 
                                                : route('posts.show', $notification->data['post_id']) . '#comment-' . $notification->data['reply_id'] 
                                        }}">
                                            <div class="d-flex w-100 justify-content-between">
                                                <small class="mb-1">
                                                    @if($notification->type == 'App\Notifications\NewCommentNotification')
                                                        <strong>{{ $notification->data['user_name'] }}</strong> commented on your post
                                                    @elseif($notification->type == 'App\Notifications\CommentReplyNotification')
                                                        <strong>{{ $notification->data['user_name'] }}</strong> replied to your comment
                                                    @endif
                                                </small>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                                
                                <li><hr class="dropdown-divider"></li>
                                <li class="text-center">
                                    <a href="{{ route('notifications.index') }}" class="dropdown-item">View All</a>
                                </li>
                                
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <li><hr class="dropdown-divider"></li>
                                    <li class="text-center">
                                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="dropdown-item text-primary">Mark All as Read</button>
                                        </form>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </li>
                @endauth

                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="{{ route('profile.edit') }}" class="dropdown-item">Profile</a>
                                </li>
                                <li>
                                    <a href="{{ route('profile.posts') }}" class="dropdown-item">My Posts</a>
                                </li>
                                <li>
                                    <a href="{{ route('likes.posts') }}" class="dropdown-item">Liked Posts</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @yield('scripts')
</body>
</html> 