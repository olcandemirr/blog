<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $category_id = $request->input('category');
        
        $posts = Post::query()
            ->with(['user', 'category'])
            ->when($query, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($category_id, function ($query, $category_id) {
                $query->where('category_id', $category_id);
            })
            ->latest()
            ->paginate(12);

        $categories = Category::withCount('posts')->get();

        return view('search.index', compact('posts', 'categories', 'query', 'category_id'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('q');
        
        $suggestions = Post::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'title']);

        return response()->json($suggestions);
    }
} 