<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Auth middleware kaldırıldı, ana sayfa herkes tarafından görüntülenebilmeli
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Öne çıkan yazılar (en çok beğenilen 3 yazı)
        $featuredPosts = Post::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->with(['user', 'category'])
            ->take(3)
            ->get();
        
        // Son yazılar
        $recentPosts = Post::with(['user', 'category'])
            ->latest()
            ->take(6)
            ->get();
        
        // Popüler kategoriler (en çok yazı olan kategoriler)
        $categories = Category::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();
        
        return view('home', compact('featuredPosts', 'recentPosts', 'categories'));
    }
}
