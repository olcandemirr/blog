@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Profile') }}</span>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary">{{ __('Edit Profile') }}</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->email }}</p>
                            
                            @if($user->bio)
                                <p>{{ $user->bio }}</p>
                            @endif
                            
                            @if($user->website)
                                <p><a href="{{ $user->website }}" target="_blank" rel="noopener noreferrer">{{ $user->website }}</a></p>
                            @endif
                            
                            <div class="mt-3">
                                <div class="d-flex">
                                    <div class="me-4">
                                        <strong>{{ $postsCount }}</strong>
                                        <div>Posts</div>
                                    </div>
                                    <div>
                                        <strong>{{ $commentsCount }}</strong>
                                        <div>Comments</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('profile.show') }}">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.posts') }}">Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.comments') }}">Comments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('likes.posts') }}">Liked Posts</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Account Information</h5>
                    <p class="card-text">Member since: {{ $user->created_at->format('F Y') }}</p>
                    
                    <hr>
                    <h5 class="mb-3">Delete Account</h5>
                    <p class="text-danger">Warning: This action cannot be undone. All your data will be permanently deleted.</p>
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        Delete My Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Please enter your password to confirm account deletion:</p>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 