<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FeedController extends Controller
{
    /**
     * Display the main RSS feed.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with(['user', 'category'])
            ->latest()
            ->take(20)
            ->get();
        
        $siteTitle = Setting::where('key', 'site_title')->first()->value ?? 'Blog Platform';
        $metaDescription = Setting::where('key', 'meta_description')->first()->value ?? 'Latest posts from our blog';
        
        $content = view('feeds.rss', [
            'posts' => $posts,
            'title' => $siteTitle . ' - Latest Posts',
            'description' => $metaDescription,
            'url' => url('/feed'),
        ])->render();
        
        return Response::make($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
    
    /**
     * Display the RSS feed for a specific category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function category(Category $category)
    {
        $posts = Post::with(['user'])
            ->where('category_id', $category->id)
            ->latest()
            ->take(20)
            ->get();
        
        $siteTitle = Setting::where('key', 'site_title')->first()->value ?? 'Blog Platform';
        
        $content = view('feeds.rss', [
            'posts' => $posts,
            'title' => $siteTitle . ' - ' . $category->name,
            'description' => $category->description ?? 'Posts in the ' . $category->name . ' category',
            'url' => url('/feed/category/' . $category->slug),
        ])->render();
        
        return Response::make($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
    
    /**
     * Display a list of available feeds.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $categories = Category::withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();
        
        return view('feeds.list', compact('categories'));
    }
} 