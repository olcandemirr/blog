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
                <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-indigo-600">
                            <a href="{{ route('categories.show', $post->category) }}" class="hover:underline">
                                {{ $post->category->name }}
                            </a>
                        </p>
                        <a href="{{ route('posts.show', $post) }}" class="block mt-2">
                            <p class="text-xl font-semibold text-gray-900">{{ $post->title }}</p>
                            <p class="mt-3 text-base text-gray-500">{{ Str::limit($post->content, 150) }}</p>
                        </a>
                    </div>
                    <div class="mt-6 flex items-center">
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
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Categories Section -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">
                Popular Categories
            </h2>
        </div>

        <div class="mt-10">
            <div class="flex flex-wrap justify-center gap-5">
                @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" 
                   class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-700 hover:bg-indigo-200">
                    {{ $category->name }}
                    <span class="ml-2 bg-indigo-200 rounded-full px-2 py-1 text-xs">
                        {{ $category->posts_count }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Recent Posts Section -->
<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">
                Recent Posts
            </h2>
        </div>

        <div class="mt-12 max-w-lg mx-auto grid gap-5 lg:grid-cols-3 lg:max-w-none">
            @foreach($recentPosts as $post)
            <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-indigo-600">
                            {{ $post->category->name }}
                        </p>
                        <a href="{{ route('posts.show', $post) }}" class="block mt-2">
                            <p class="text-xl font-semibold text-gray-900">{{ $post->title }}</p>
                            <p class="mt-3 text-base text-gray-500">{{ Str::limit($post->content, 100) }}</p>
                        </a>
                    </div>
                    <div class="mt-6 flex items-center">
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
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection 