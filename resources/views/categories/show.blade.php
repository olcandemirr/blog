<div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>{{ $category->name }}</h1>
            <p class="text-muted">{{ $category->description }}</p>
        </div>
        <div>
            @auth
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning me-2">Edit Category</a>
            @endauth
            <a href="{{ route('feeds.category', $category) }}" class="btn btn-outline-danger" target="_blank">
                <i class="bi bi-rss"></i> Subscribe to RSS
            </a>
        </div>
    </div>
</div> 