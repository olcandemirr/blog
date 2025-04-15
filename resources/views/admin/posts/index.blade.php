@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Posts Management</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Posts</h6>
            <div>
                <form action="{{ route('admin.posts.index') }}" method="GET" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Search for posts..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <form id="bulk-action-form" action="{{ route('admin.posts.bulkDestroy') }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <!-- Category Filters -->
                        <a href="{{ route('admin.posts.index') }}" class="btn {{ request('category') == '' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">All Categories</a>
                        @foreach($categories as $category)
                            <a href="{{ route('admin.posts.index', ['category' => $category->id] + request()->except(['category','page'])) }}" class="btn {{ request('category') == $category->id ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">{{ $category->name }}</a>
                        @endforeach
                    </div>
                    <div>
                         <!-- Bulk Action Button -->
                        <button type="submit" class="btn btn-danger btn-sm" id="bulk-delete-btn" disabled onclick="return confirm('Are you sure you want to delete the selected posts? This action cannot be undone.')">
                            <i class="bi bi-trash"></i> Delete Selected (<span id="selected-count">0</span>)
                        </button>

                        <!-- Sort Dropdown -->
                        <div class="dropdown d-inline-block ms-2">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Sort by: {{ ucfirst(request('sort', 'created_at')) }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.posts.index', ['sort' => 'created_at', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}">Newest</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.posts.index', ['sort' => 'created_at', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}">Oldest</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.posts.index', ['sort' => 'title', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}">Title (A-Z)</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.posts.index', ['sort' => 'title', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}">Title (Z-A)</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.posts.index', ['sort' => 'likes_count', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}">Most Liked</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.posts.index', ['sort' => 'comments_count', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}">Most Commented</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="postsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Likes</th>
                                <th>Comments</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                            <tr>
                                <td><input type="checkbox" name="selected_ids[]" value="{{ $post->id }}" class="row-checkbox"></td>
                                <td>{{ $post->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($post->image_path)
                                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="me-2 rounded" width="40" height="40" style="object-fit: cover;">
                                        @else
                                            <div class="me-2 rounded bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-file-earmark-text text-muted"></i>
                                            </div>
                                        @endif
                                        <div>{{ Str::limit($post->title, 40) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $post->user) }}">
                                        {{ $post->user->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $post->category->name }}
                                    </span>
                                </td>
                                <td>{{ $post->likes_count }}</td>
                                <td>{{ $post->comments_count }}</td>
                                <td>{{ $post->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('posts.show', $post) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline single-delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No posts found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form> <!-- Close bulk action form -->
            <div class="d-flex justify-content-end">
                 {{ $posts->appends(request()->query())->links() }} {{-- Pagination linklerine query string ekle --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Aynı JavaScript kodu user index sayfasındaki gibi buraya da eklenecek --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const selectedCountSpan = document.getElementById('selected-count');
    const bulkActionForm = document.getElementById('bulk-action-form');

    function updateSelectedCount() {
        const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        const count = selectedCheckboxes.length;
        selectedCountSpan.textContent = count;
        bulkDeleteBtn.disabled = count === 0;
    }

    selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateSelectedCount();
    });

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!checkbox.checked) {
                selectAllCheckbox.checked = false;
            } else {
                // Check if all row checkboxes are checked
                const allChecked = Array.from(rowCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            }
            updateSelectedCount();
        });
    });

    // Initial count update on page load
    updateSelectedCount();
});
</script>
@endpush 