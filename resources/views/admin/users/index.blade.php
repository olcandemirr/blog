@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Users Management</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Users</h6>
            <div>
                <form action="{{ route('admin.users.index') }}" method="GET" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Search for users..." value="{{ request('search') }}">
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
            <form id="bulk-action-form" action="{{ route('admin.users.bulkDestroy') }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <!-- Filter Buttons -->
                        <a href="{{ route('admin.users.index') }}" class="btn {{ request('filter') == '' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">All</a>
                        <a href="{{ route('admin.users.index', ['filter' => 'admin']) }}" class="btn {{ request('filter') == 'admin' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">Admins</a>
                        <a href="{{ route('admin.users.index', ['filter' => 'user']) }}" class="btn {{ request('filter') == 'user' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">Regular Users</a>
                    </div>
                    <div>
                        <!-- Bulk Action Button -->
                        <button type="submit" class="btn btn-danger btn-sm" id="bulk-delete-btn" disabled onclick="return confirm('Are you sure you want to delete the selected users? This action cannot be undone.')">
                            <i class="bi bi-trash"></i> Delete Selected (<span id="selected-count">0</span>)
                        </button>
                        
                        <!-- Sort Dropdown -->
                        <div class="dropdown d-inline-block ms-2">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Sort by: {{ request('sort', 'created_at') == 'created_at' ? 'Newest' : ucfirst(request('sort', 'created_at')) }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.users.index', ['sort' => 'created_at', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}">Newest</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.index', ['sort' => 'created_at', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}">Oldest</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.index', ['sort' => 'name', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}">Name (A-Z)</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.index', ['sort' => 'name', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}">Name (Z-A)</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Posts</th>
                                <th>Comments</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    @if(auth()->id() !== $user->id) {{-- Admin kendisini seçemesin --}}
                                        <input type="checkbox" name="selected_ids[]" value="{{ $user->id }}" class="row-checkbox">
                                    @endif
                                </td>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" width="32" height="32">
                                            @else
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->is_admin ? 'bg-success' : 'bg-primary' }}">
                                        {{ $user->is_admin ? 'Admin' : 'User' }}
                                    </span>
                                </td>
                                <td>{{ $user->posts_count }}</td>
                                <td>{{ $user->comments_count }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline single-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user? This will also delete all their posts and comments.')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No users found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form> <!-- Close bulk action form -->
            <div class="d-flex justify-content-end">
                {{ $users->appends(request()->query())->links() }} {{-- Pagination linklerine query string ekle --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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