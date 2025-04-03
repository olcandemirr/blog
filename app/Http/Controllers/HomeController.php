<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPosts = Post::with(['user', 'category'])
            ->latest()
            ->take(6)
            ->get();
            
        $categories = Category::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(5)
            ->get();

        $recentPosts = Post::with(['user', 'category'])
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('featuredPosts', 'categories', 'recentPosts'));
    }
} 