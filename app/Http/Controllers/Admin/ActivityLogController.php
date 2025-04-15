<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'subject']); // İlişkili modelleri yükle

        // Kullanıcıya göre filtrele
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Log adına göre filtrele
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }
        
        // Tarih aralığına göre filtrele
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Açıklama içinde arama
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->latest()->paginate(20)->withQueryString(); // Sorgu parametrelerini koru

        $users = User::orderBy('name')->get(['id', 'name']); // Filtre için kullanıcı listesi
        $logNames = ActivityLog::distinct()->pluck('log_name'); // Filtre için log adları

        return view('admin.activity_logs.index', compact('logs', 'users', 'logNames'));
    }
} 