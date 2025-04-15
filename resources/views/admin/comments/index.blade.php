@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Comments Management</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Comments</h6>
            <div>
                <form action="{{ route('admin.comments.index') }}" method="GET" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Search in comments..." value="{{ request('search') }}">
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
            <form id="bulk-action-form" action="{{ route('admin.comments.bulkDestroy') }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <!-- Type Filters -->
                        <a href="{{ route('admin.comments.index') }}" class="btn {{ request('type') == '' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">All Comments</a>
                        <a href="{{ route('admin.comments.index', ['type' => 'parent'] + request()->except(['type','page'])) }}" class="btn {{ request('type') == 'parent' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">Parent Comments</a>
                        <a href="{{ route('admin.comments.index', ['type' => 'reply'] + request()->except(['type','page'])) }}" class="btn {{ request('type') == 'reply' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">Replies</a>
                    </div>
                    <div>
                        <!-- Bulk Action Button -->
                        <button type="submit" class="btn btn-danger btn-sm" id="bulk-delete-btn" disabled onclick="return confirm('Are you sure you want to delete the selected comments? This action cannot be undone.')">
                            <i class="bi bi-trash"></i> Delete Selected (<span id="selected-count">0</span>)
                        </button>

                        <!-- Sort Dropdown -->
                        <div class="dropdown d-inline-block ms-2">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Sort by: {{ ucfirst(request('sort', 'created_at')) }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.comments.index', ['sort' => 'created_at', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}">Newest</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.comments.index', ['sort' => 'created_at', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}">Oldest</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>ID</th>
                                <th>Content</th>
                                <th>Author</th>
                                <th>Post</th>
                                <th>Type</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($comments as $comment)
                            <tr>
                                <td><input type="checkbox" name="selected_ids[]" value="{{ $comment->id }}" class="row-checkbox"></td>
                                <td>{{ $comment->id }}</td>
                                <td>{{ Str::limit($comment->content, 100) }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $comment->user) }}">
                                        {{ $comment->user->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('posts.show', $comment->post) }}" target="_blank">
                                        {{ Str::limit($comment->post->title, 30) }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge {{ $comment->parent_id ? 'bg-info' : 'bg-primary' }}">
                                        {{ $comment->parent_id ? 'Reply' : 'Comment' }}
                                    </span>
                                </td>
                                <td>{{ $comment->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $comment->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline single-delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Comment Modal -->
                            <div class="modal fade" id="editCommentModal{{ $comment->id }}" tabindex="-1" aria-labelledby="editCommentModalLabel{{ $comment->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.comments.update', $comment) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCommentModalLabel{{ $comment->id }}">Edit Comment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="content{{ $comment->id }}" class="form-label">Content</label>
                                                    <textarea class="form-control" id="content{{ $comment->id }}" name="content" rows="3" required>{{ $comment->content }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update Comment</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No comments found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form> <!-- Close bulk action form -->
            <div class="d-flex justify-content-end">
                 {{ $comments->appends(request()->query())->links() }} {{-- Pagination linklerine query string ekle --}}
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