<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $categories = Category::withCount('posts')->get();
        
        return view('admin.categories.index', compact('categories'));
    }
    
    public function stats()
    {
        // Get categories with post and comment counts
        $categories = Category::withCount(['posts', 'posts as comments_count' => function ($query) {
            $query->withCount('comments')->select(DB::raw('SUM(comments_count)'));
        }])->get();
        
        // Get most active categories (by post creation date)
        $mostActiveCategories = Category::withCount(['posts' => function ($query) {
            $query->where('created_at', '>=', now()->subDays(30));
        }])
        ->orderByDesc('posts_count')
        ->take(5)
        ->get();
        
        // Get engagement metrics by category (comments, likes)
        $categoryEngagement = Category::select('categories.id', 'categories.name')
            ->leftJoin('posts', 'categories.id', '=', 'posts.category_id')
            ->leftJoin('comments', 'posts.id', '=', 'comments.post_id')
            ->leftJoin('likes', 'posts.id', '=', 'likes.post_id')
            ->groupBy('categories.id', 'categories.name')
            ->selectRaw('
                COUNT(DISTINCT posts.id) as posts_count,
                COUNT(DISTINCT comments.id) as comments_count,
                COUNT(DISTINCT likes.id) as likes_count,
                CASE WHEN COUNT(DISTINCT posts.id) > 0 
                    THEN (COUNT(DISTINCT comments.id) + COUNT(DISTINCT likes.id)) / COUNT(DISTINCT posts.id) 
                    ELSE 0 
                END as engagement_rate
            ')
            ->orderByDesc('engagement_rate')
            ->get();
        
        // Get posts with no category
        $uncategorizedCount = Post::whereNull('category_id')->count();
        
        // Get monthly post statistics for the last 12 months
        $monthlyStats = Category::select('categories.name')
            ->join('posts', 'categories.id', '=', 'posts.category_id')
            ->where('posts.created_at', '>=', now()->subMonths(12))
            ->groupBy('categories.name')
            ->selectRaw('
                categories.name,
                DATE_FORMAT(posts.created_at, "%Y-%m") as month,
                COUNT(*) as posts_count
            ')
            ->orderBy('month')
            ->get()
            ->groupBy('month');
            
        // Prepare chart data
        $chartLabels = [];
        $chartData = [];
        $chartColors = [
            '#4E73DF', '#1CC88A', '#36B9CC', '#F6C23E', '#E74A3B', 
            '#5A5C69', '#858796', '#6610F2', '#6F42C1', '#E83E8C'
        ];
        
        // Get unique category names
        $categoryNames = $categoryEngagement->pluck('name')->toArray();
        
        // Process monthly data for chart
        foreach ($monthlyStats as $month => $data) {
            $chartLabels[] = $month;
            
            // Create a map of category => count
            $monthData = [];
            foreach ($data as $item) {
                $monthData[$item->name] = $item->posts_count;
            }
            
            // Ensure all categories have a value for each month
            $monthDatasets = [];
            foreach ($categoryNames as $index => $categoryName) {
                $monthDatasets[] = [
                    'label' => $categoryName,
                    'data' => isset($monthData[$categoryName]) ? $monthData[$categoryName] : 0,
                    'backgroundColor' => $chartColors[$index % count($chartColors)]
                ];
            }
            
            $chartData[] = $monthDatasets;
        }
        
        return view('admin.categories.stats', compact(
            'categories', 
            'mostActiveCategories', 
            'categoryEngagement', 
            'uncategorizedCount',
            'chartLabels',
            'chartData',
            'categoryNames'
        ));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['slug'] = Str::slug($request->name);
        
        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['slug'] = Str::slug($request->name);
        
        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Update posts that use this category to set category_id to null
        $category->posts()->update(['category_id' => null]);
        
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
} 