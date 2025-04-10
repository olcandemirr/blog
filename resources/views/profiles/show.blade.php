<!-- Profil kartındaki butonlar arasına ekleyelim -->
<div class="d-flex justify-content-around mb-2">
    <div class="text-center">
        <h5 class="mb-0">{{ $user->posts->count() }}</h5>
        <small class="text-muted">Posts</small>
    </div>
    <div class="text-center">
        <h5 class="mb-0">{{ $user->comments->count() }}</h5>
        <small class="text-muted">Comments</small>
    </div>
    <div class="text-center">
        <h5 class="mb-0">{{ $user->likes->count() }}</h5>
        <small class="text-muted">Likes</small>
    </div>
</div>

<!-- Kullanıcı kendi profilini görüntülüyorsa beğendiği gönderileri görmesi için bağlantı ekleyelim -->
@if(Auth::id() === $user->id)
    <div class="mt-3">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
        <a href="{{ route('likes.posts') }}" class="btn btn-outline-danger">Liked Posts</a>
    </div>
@endif 