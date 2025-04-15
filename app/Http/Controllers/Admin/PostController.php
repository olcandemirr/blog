<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:posts,id',
        ]);

        $posts = Post::whereIn('id', $request->selected_ids)->get();
        $deletedCount = 0;

        foreach ($posts as $post) {
            // Post görselini silme
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $post->delete(); // Post silinince ilişkili yorumlar, likelar da silinir (cascade)
            $deletedCount++;
        }

        return redirect()->route('admin.posts.index')
            ->with('success', $deletedCount . ' post(s) deleted successfully.');
    }
} 