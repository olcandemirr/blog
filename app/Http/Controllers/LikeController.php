<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Notifications\PostLikedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Post $post)
    {
        $user = Auth::user();
        
        $existing_like = Like::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();
        
        if ($existing_like) {
            // Like varsa kaldır
            $existing_like->delete();
            $message = 'Post unliked successfully.';
            $liked = false;
        } else {
            // Like yoksa ekle
            $like = new Like([
                'user_id' => $user->id,
                'post_id' => $post->id
            ]);
            $like->save();
            
            // Bildirim gönder (kendi postunu beğendiyse bildirim gönderme)
            if ($post->user_id != $user->id) {
                $post->user->notify(new PostLikedNotification($like));
            }
            
            $message = 'Post liked successfully.';
            $liked = true;
        }
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likesCount' => $post->likes()->count()
            ]);
        }
        
        return back()->with('success', $message);
    }
    
    public function likedPosts()
    {
        $likedPosts = Auth::user()->likedPosts()->with(['user', 'category'])->latest()->paginate(12);
        
        return view('likes.posts', compact('likedPosts'));
    }
} 