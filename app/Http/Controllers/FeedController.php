<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'category'])
            ->latest()
            ->take(20)
            ->get();

        return response()->view('feeds.index', [
            'posts' => $posts,
            'title' => config('app.name') . ' - All Posts Feed',
            'description' => 'Latest posts from ' . config('app.name'),
        ])->header('Content-Type', 'application/xml');
    }

    public function category(Category $category)
    {
        $posts = Post::with(['user', 'category'])
            ->where('category_id', $category->id)
            ->latest()
            ->take(20)
            ->get();

        return response()->view('feeds.category', [
            'posts' => $posts,
            'category' => $category,
            'title' => config('app.name') . ' - ' . $category->name . ' Feed',
            'description' => 'Latest posts in ' . $category->name . ' from ' . config('app.name'),
        ])->header('Content-Type', 'application/xml');
    }

    public function list()
    {
        $categories = Category::withCount('posts')
            ->orderBy('name')
            ->get();
        
        return view('feeds.list', compact('categories'));
    }
} 