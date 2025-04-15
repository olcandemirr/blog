<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'posts_count' => Post::count(),
            'comments_count' => Comment::count(),
            'categories_count' => Category::count(),
        ];
        
        // Son 7 gündeki aktiviteler
        $lastWeekStats = [
            'new_users' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'new_posts' => Post::where('created_at', '>=', now()->subDays(7))->count(),
            'new_comments' => Comment::where('created_at', '>=', now()->subDays(7))->count(),
        ];
        
        // En popüler 5 gönderi
        $popularPosts = Post::withCount(['comments', 'likes'])
            ->orderBy('likes_count', 'desc')
            ->orderBy('comments_count', 'desc')
            ->with('user')
            ->limit(5)
            ->get();
        
        // En aktif 5 kullanıcı
        $activeUsers = User::withCount(['posts', 'comments', 'likes'])
            ->orderBy('posts_count', 'desc')
            ->orderBy('comments_count', 'desc')
            ->limit(5)
            ->get();
            
        // Grafik verileri (Son 30 gün)
        $startDate = Carbon::now()->subDays(29);
        $endDate = Carbon::now();
        
        $dates = collect(range(0, 29))->map(function ($i) use ($startDate) {
            return $startDate->copy()->addDays($i)->format('Y-m-d');
        });

        $dailyUsers = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');
            
        $dailyPosts = Post::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $dailyComments = Comment::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');
            
        $chartData = [
            'labels' => $dates,
            'users' => $dates->map(function ($date) use ($dailyUsers) {
                return $dailyUsers->get($date, 0);
            }),
            'posts' => $dates->map(function ($date) use ($dailyPosts) {
                return $dailyPosts->get($date, 0);
            }),
            'comments' => $dates->map(function ($date) use ($dailyComments) {
                return $dailyComments->get($date, 0);
            }),
        ];

        return view('admin.dashboard', compact(
            'stats', 
            'lastWeekStats',
            'popularPosts',
            'activeUsers',
            'chartData' // Grafik verilerini view'e gönderelim
        ));
    }
} 