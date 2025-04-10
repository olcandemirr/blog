<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewCommentNotification;
use App\Notifications\CommentReplyNotification;
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

        // Send notifications
        if ($request->parent_id) {
            // This is a reply to a comment
            $parentComment = Comment::find($request->parent_id);
            
            // Don't notify if user is replying to their own comment
            if ($parentComment->user_id != auth()->id()) {
                $parentComment->user->notify(new CommentReplyNotification($comment));
            }
        } else {
            // This is a comment on a post
            // Don't notify if user is commenting on their own post
            if ($post->user_id != auth()->id()) {
                $post->user->notify(new NewCommentNotification($comment));
            }
        }

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