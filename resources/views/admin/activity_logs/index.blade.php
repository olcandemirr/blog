@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Activity Logs</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
             <form action="{{ route('admin.activity_logs.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="search" class="form-label form-label-sm">Search Description</label>
                        <input type="text" name="search" id="search" class="form-control form-control-sm" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="user_id" class="form-label form-label-sm">User</label>
                        <select name="user_id" id="user_id" class="form-select form-select-sm">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="log_name" class="form-label form-label-sm">Log Type</label>
                        <select name="log_name" id="log_name" class="form-select form-select-sm">
                            <option value="">All Types</option>
                            @foreach($logNames as $name)
                                <option value="{{ $name }}" {{ request('log_name') == $name ? 'selected' : '' }}>
                                    {{ ucfirst($name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="start_date" class="form-label form-label-sm">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label form-label-sm">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Subject</th>
                            <th>IP Address</th>
                            <th>Timestamp</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->user->name ?? 'System/Guest' }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($log->log_name) }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td>
                                @if($log->subject)
                                    {{ class_basename($log->subject_type) }}: {{ $log->subject->id ?? 'N/A' }} 
                                    {{-- Link to subject if possible, e.g., post title --}}
                                    @if($log->subject_type == App\Models\Post::class && $log->subject)
                                        (<a href="{{ route('posts.show', $log->subject) }}" target="_blank">{{ Str::limit($log->subject->title, 20) }}</a>)
                                    @elseif($log->subject_type == App\Models\User::class && $log->subject)
                                         (<a href="{{ route('admin.users.edit', $log->subject) }}">{{ $log->subject->name }}</a>)
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $log->properties['ip_address'] ?? '-' }}</td>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                @if($log->properties && $log->properties->count() > 0)
                                <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $log->id }}" aria-expanded="false" aria-controls="collapse{{ $log->id }}">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @if($log->properties && $log->properties->count() > 0)
                        <tr class="collapse" id="collapse{{ $log->id }}">
                             <td colspan="8">
                                <pre class="bg-light p-2 rounded small"><code>{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No activity logs found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="d-flex justify-content-end">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 