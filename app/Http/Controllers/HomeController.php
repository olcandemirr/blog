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
<<<<<<< HEAD
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
=======
        // Featured posts (latest 6 posts)
        $featuredPosts = Post::with(['user', 'category'])
            ->withCount(['comments', 'likes'])
            ->latest()
            ->take(6)
            ->get();
            
        // Popular categories (based on post count)
        $popularCategories = Category::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(8)
            ->get();
            
        // Get all categories for a complete category listing
        $allCategories = Category::withCount('posts')
            ->orderBy('name')
            ->get();

        // Recent posts (latest 3 posts)
        $recentPosts = Post::with(['user', 'category'])
            ->withCount(['comments', 'likes'])
            ->latest()
            ->take(3)
            ->get();
            
        // Most popular posts (based on comments and likes)
        $popularPosts = Post::with(['user', 'category'])
            ->withCount(['comments', 'likes'])
            ->orderByDesc(\DB::raw('comments_count + likes_count'))
            ->take(3)
            ->get();

        return view('home', compact(
            'featuredPosts', 
            'popularCategories', 
            'allCategories',
            'recentPosts',
            'popularPosts'
        ));
>>>>>>> 8a6da4b03aeac32a4b758e6312add93c0e689c94
    }
}
