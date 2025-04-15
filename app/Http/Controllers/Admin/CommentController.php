<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:comments,id',
        ]);

        $deletedCount = Comment::destroy($request->selected_ids);

        return redirect()->route('admin.comments.index')
            ->with('success', $deletedCount . ' comment(s) deleted successfully.');
    }
} 