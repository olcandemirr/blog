<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|min:3',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = new Comment([
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);
        
        $comment->user_id = auth()->id();
        $comment->post_id = $post->id;
        $comment->save();

        return back()->with('success', 'Comment added successfully.');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|min:3'
        ]);

        $comment->update([
            'content' => $request->content
        ]);

        return back()->with('success', 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
} 