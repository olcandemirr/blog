@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gray-900 overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-gray-900 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                        <span class="block">Welcome to</span>
                        <span class="block text-indigo-600">Your Blog Platform</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Share your thoughts, ideas, and stories with the world. Join our community of writers and readers.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        @auth
                            <div class="rounded-md shadow">
                                <a href="{{ route('posts.create') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    Create New Post
                                </a>
                            </div>
                        @else
                            <div class="rounded-md shadow">
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    Get Started
                                </a>
                            </div>
                        @endauth
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="{{ route('posts.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                                View All Posts
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- Add this near the top of the page, perhaps after the hero section -->
<div class="bg-white py-4 border-bottom">
    <div class="container">
        <div class="d-flex justify-content-end">
            <a href="{{ route('feeds.index') }}" class="btn btn-sm btn-outline-danger" target="_blank">
                <i class="bi bi-rss"></i> Subscribe to RSS Feed
            </a>
        </div>
    </div>
</div>

<!-- Popular Categories Section -->
<div class="bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">
                Popular Categories
            </h2>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Browse posts by your favorite topics
            </p>
        </div>

        <div class="mt-8">
            <div class="flex flex-wrap justify-center gap-4">
                @foreach($popularCategories as $category)
                <a href="{{ route('categories.show', $category) }}" 
                   class="mb-2 inline-flex items-center px-5 py-2.5 rounded-full text-sm font-medium 
                          {{ $category->posts_count > 10 ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-indigo-100 text-indigo-700 hover:bg-indigo-200' }}">
                    {{ $category->name }}
                    <span class="ml-2 {{ $category->posts_count > 10 ? 'bg-indigo-800' : 'bg-indigo-200' }} rounded-full px-2 py-1 text-xs">
                        {{ $category->posts_count }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>
        
        <div class="mt-8 text-center">
            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                View All Categories
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Featured Posts Section -->
<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">
                Featured Posts
            </h2>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Check out our latest and most popular blog posts
            </p>
        </div>

        <div class="mt-12 grid gap-5 max-w-lg mx-auto lg:grid-cols-3 lg:max-w-none">
            @foreach($featuredPosts as $post)
            <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                @if($post->image)
                <div class="flex-shrink-0">
                    <img class="h-48 w-full object-cover" src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                </div>
                @endif
                <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-indigo-600">
                            <a href="{{ route('categories.show', $post->category) }}" class="hover:underline">
                                {{ $post->category->name }}
                            </a>
                        </p>
                        <a href="{{ route('posts.show', $post) }}" class="block mt-2">
                            <p class="text-xl font-semibold text-gray-900">{{ $post->title }}</p>
                            <p class="mt-3 text-base text-gray-500">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                        </a>
                    </div>
                    <div class="mt-6 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="sr-only">{{ $post->user->name }}</span>
                                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-bold">{{ substr($post->user->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $post->user->name }}
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500">
                                    <time datetime="{{ $post->created_at->toISOString() }}">
                                        {{ $post->created_at->diffForHumans() }}
                                    </time>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="mr-2">
                                <i class="bi bi-chat-text"></i> {{ $post->comments_count }}
                            </span>
                            <span>
                                <i class="bi bi-heart"></i> {{ $post->likes_count }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Most Popular Posts Section -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">
                Most Popular Posts
            </h2>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Trending content with the most engagement
            </p>
        </div>

        <div class="mt-12 max-w-lg mx-auto grid gap-5 lg:grid-cols-3 lg:max-w-none">
            @foreach($popularPosts as $post)
            <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                @if($post->image)
                <div class="flex-shrink-0">
                    <img class="h-48 w-full object-cover" src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                </div>
                @endif
                <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-medium text-indigo-600">
                                <a href="{{ route('categories.show', $post->category) }}" class="hover:underline">
                                    {{ $post->category->name }}
                                </a>
                            </p>
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                Popular
                            </div>
                        </div>
                        <a href="{{ route('posts.show', $post) }}" class="block mt-2">
                            <p class="text-xl font-semibold text-gray-900">{{ $post->title }}</p>
                            <p class="mt-3 text-base text-gray-500">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                        </a>
                    </div>
                    <div class="mt-6 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="sr-only">{{ $post->user->name }}</span>
                                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-bold">{{ substr($post->user->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $post->user->name }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500 space-x-2">
                            <span class="flex items-center">
                                <i class="bi bi-chat-text mr-1"></i> {{ $post->comments_count }}
                            </span>
                            <span class="flex items-center">
                                <i class="bi bi-heart mr-1"></i> {{ $post->likes_count }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Categories Browser Section -->
<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">
                Browse All Categories
            </h2>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Find content that matches your interests
            </p>
        </div>

        <div class="mt-10 grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
            @foreach($allCategories as $category)
            <a href="{{ route('categories.show', $category) }}" class="group">
                <div class="bg-gray-50 rounded-lg p-6 hover:bg-indigo-50 transition duration-300">
                    <h3 class="text-lg font-medium text-gray-900 group-hover:text-indigo-600">{{ $category->name }}</h3>
                    <p class="mt-2 text-sm text-gray-500">{{ Str::limit($category->description, 60) }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-sm font-medium text-indigo-600">{{ $category->posts_count }} posts</span>
                        <span class="text-indigo-500 group-hover:translate-x-1 transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection 