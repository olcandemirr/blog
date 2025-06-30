<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.meta-tags')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel Blog') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #6b7280;
            --dark: #1f2937;
            --light: #f9fafb;
            --accent: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--gray-700);
            background-color: var(--light);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--gray-800);
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 0.75rem 1rem;
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }
        
        .btn {
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .card-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }
        
        .search-container {
            position: relative;
            width: 300px;
        }
        
        .search-input {
            width: 100%;
            padding: 0.65rem 2.5rem 0.65rem 1rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.375rem;
            background-color: var(--light);
            transition: all 0.2s;
        }
        
        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
            outline: none;
        }
        
        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }
        
        .dropdown-menu {
            border: none;
            border-radius: 0.375rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
        }
        
        .dropdown-item {
            padding: 0.5rem 1.25rem;
            font-weight: 500;
        }
        
        .dropdown-item:hover {
            background-color: var(--gray-100);
        }
        
        .alert {
            border: none;
            border-radius: 0.375rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .badge {
            padding: 0.35em 0.65em;
            font-weight: 600;
            font-size: 0.75em;
        }
        
        footer {
            background-color: var(--dark);
            color: var(--light);
            padding: 3rem 0;
            margin-top: 4rem;
        }
        
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(25%, -25%);
        }
        
        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--light);
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
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ route('home') }}">
                <i class="fas fa-pen-fancy me-2"></i>{{ config('app.name', 'Laravel Blog') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('posts.index') ? 'active fw-bold' : '' }}" href="{{ route('posts.index') }}">
                            <i class="fas fa-file-alt me-1"></i> Posts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.index') ? 'active fw-bold' : '' }}" href="{{ route('categories.index') }}">
                            <i class="fas fa-folder me-1"></i> Categories
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('posts.create') ? 'active fw-bold' : '' }}" href="{{ route('posts.create') }}">
                                <i class="fas fa-plus-circle me-1"></i> Create Post
                            </a>
                        </li>
                    @endauth
                </ul>
                
                <div class="search-container me-3">
                    <form action="{{ route('search') }}" method="GET">
                        <input type="search" 
                               name="q" 
                               class="search-input"
                               placeholder="Search posts..."
                               value="{{ request('q') }}">
                        <span class="search-icon">
                            <i class="fas fa-search"></i>
                        </span>
                    </form>
                </div>

                @auth
                    <div class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell fa-lg"></i>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                                    {{ Auth::user()->unreadNotifications->count() > 99 ? '99+' : Auth::user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 320px;" aria-labelledby="notificationsDropdown">
                            <li>
                                <div class="d-flex justify-content-between align-items-center px-3 py-2 bg-light">
                                    <h6 class="m-0 fw-bold">Notifications</h6>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-link p-0 text-decoration-none">Mark all read</button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                            
                            @if(Auth::user()->notifications->isEmpty())
                                <li><span class="dropdown-item text-muted text-center py-3">No notifications</span></li>
                            @else
                                @foreach(Auth::user()->notifications->take(5) as $notification)
                                    <li>
                                        <a class="dropdown-item py-2 {{ $notification->read_at ? '' : 'bg-light' }}" href="{{ 
                                            $notification->type == 'App\Notifications\NewCommentNotification' 
                                                ? route('posts.show', $notification->data['post_id']) . '#comment-' . $notification->data['comment_id'] 
                                                : ($notification->type == 'App\Notifications\CommentReplyNotification' 
                                                    ? route('posts.show', $notification->data['post_id']) . '#comment-' . $notification->data['reply_id']
                                                    : route('posts.show', $notification->data['post_id']))
                                        }}">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="bg-primary rounded-circle p-2 text-white">
                                                        @if($notification->type == 'App\Notifications\NewCommentNotification')
                                                            <i class="fas fa-comment fa-sm"></i>
                                                        @elseif($notification->type == 'App\Notifications\CommentReplyNotification')
                                                            <i class="fas fa-reply fa-sm"></i>
                                                        @elseif($notification->type == 'App\Notifications\PostLikedNotification')
                                                            <i class="fas fa-heart fa-sm"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p class="mb-0 small">
                                                        @if($notification->type == 'App\Notifications\NewCommentNotification')
                                                            <strong>{{ $notification->data['user_name'] }}</strong> commented on your post
                                                        @elseif($notification->type == 'App\Notifications\CommentReplyNotification')
                                                            <strong>{{ $notification->data['user_name'] }}</strong> replied to your comment
                                                        @elseif($notification->type == 'App\Notifications\PostLikedNotification')
                                                            <strong>{{ $notification->data['user_name'] }}</strong> liked your post
                                                        @endif
                                                    </p>
                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                                
                                <li><hr class="dropdown-divider my-0"></li>
                                <li class="text-center">
                                    <a href="{{ route('notifications.index') }}" class="dropdown-item py-2 text-primary">View All Notifications</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endauth

                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i> Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                @if(Auth::user()->avatar_path)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar_path) }}" alt="{{ Auth::user()->name }}" class="avatar me-2">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 2.5rem; height: 2.5rem;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="{{ route('profile.show') }}" class="dropdown-item">
                                        <i class="fas fa-user me-2"></i> My Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                        <i class="fas fa-user-edit me-2"></i> Edit Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile.posts') }}" class="dropdown-item">
                                        <i class="fas fa-file-alt me-2"></i> My Posts
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('likes.posts') }}" class="dropdown-item">
                                        <i class="fas fa-heart me-2"></i> Liked Posts
                                    </a>
                                </li>
                                @if(Auth::user()->is_admin)
                                    <li>
                                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                            <i class="fas fa-tachometer-alt me-2"></i> Admin Dashboard
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
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
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                </div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-white mb-3">{{ config('app.name', 'Laravel Blog') }}</h5>
                    <p class="text-gray-400">A modern blog platform built with Laravel, featuring categories, comments, likes, and more.</p>
                    <div class="d-flex mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="text-white mb-3">Navigation</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-gray-400 text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="{{ route('posts.index') }}" class="text-gray-400 text-decoration-none">Posts</a></li>
                        <li class="mb-2"><a href="{{ route('categories.index') }}" class="text-gray-400 text-decoration-none">Categories</a></li>
                        <li><a href="{{ route('search') }}" class="text-gray-400 text-decoration-none">Search</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h6 class="text-white mb-3">Resources</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('feeds.list') }}" class="text-gray-400 text-decoration-none">RSS Feeds</a></li>
                        <li class="mb-2"><a href="#" class="text-gray-400 text-decoration-none">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-gray-400 text-decoration-none">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 text-decoration-none">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white mb-3">Subscribe</h6>
                    <p class="text-gray-400">Subscribe to our newsletter for updates</p>
                    <form>
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your email">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="mt-4 mb-3 border-gray-700">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start text-gray-400">
                    <small>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel Blog') }}. All rights reserved.</small>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <small class="text-gray-400">Made with <i class="fas fa-heart text-danger"></i> using Laravel</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
    @yield('scripts')
</body>
</html> 
</html> 