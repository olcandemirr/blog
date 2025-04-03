@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h1>Search Results</h1>
        @if($query)
            <p class="text-muted">Showing results for "{{ $query }}"</p>
        @endif
    </div>

    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('search') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <div class="position-relative">
                        <input type="text" 
                               name="q" 
                               value="{{ $query }}" 
                               class="form-control"
                               placeholder="Search posts..."
                               id="search-input"
                               autocomplete="off">
                        <div id="search-suggestions" class="position-absolute w-100 mt-1 bg-white border rounded shadow-sm d-none" style="z-index: 1000;">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->posts_count }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <svg width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results -->
    @if($posts->isEmpty())
        <div class="text-center py-5">
            <svg width="64" height="64" fill="currentColor" class="bi bi-search text-muted mb-3" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
            <h3>No posts found</h3>
            <p class="text-muted">Try adjusting your search or filter to find what you're looking for.</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($posts as $post)
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <a href="{{ route('categories.show', $post->category) }}" class="badge bg-primary text-decoration-none">
                                {{ $post->category->name }}
                            </a>
                            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                        </div>
                        <h5 class="card-title">
                            @if($query)
                                {!! preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark>$1</mark>', e($post->title)) !!}
                            @else
                                {{ $post->title }}
                            @endif
                        </h5>
                        <p class="card-text">
                            @if($query)
                                {!! preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark>$1</mark>', e(Str::limit($post->content, 150))) !!}
                            @else
                                {{ Str::limit($post->content, 150) }}
                            @endif
                        </p>
                        <div class="d-flex align-items-center mt-3">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                {{ substr($post->user->name, 0, 1) }}
                            </div>
                            <div class="ms-2">
                                <div class="fw-bold">{{ $post->user->name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $posts->withQueryString()->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionsContainer = document.getElementById('search-suggestions');
    let debounceTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (query.length < 2) {
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.classList.add('d-none');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        suggestionsContainer.innerHTML = data.map(post => `
                            <a href="/posts/${post.id}" class="d-block text-decoration-none text-dark p-2 hover-bg-light">
                                ${post.title}
                            </a>
                        `).join('');
                        suggestionsContainer.classList.remove('d-none');
                    } else {
                        suggestionsContainer.classList.add('d-none');
                    }
                });
        }, 300);
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
            suggestionsContainer.classList.add('d-none');
        }
    });
});
</script>
@endpush 