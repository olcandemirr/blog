<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
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
    }
} 