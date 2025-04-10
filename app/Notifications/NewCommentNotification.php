<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Comment on Your Post: ' . $this->comment->post->title)
                    ->line($this->comment->user->name . ' commented on your post.')
                    ->line('"' . substr($this->comment->content, 0, 50) . (strlen($this->comment->content) > 50 ? '...' : '') . '"')
                    ->action('View Comment', url('/posts/' . $this->comment->post->id . '#comment-' . $this->comment->id))
                    ->line('Thank you for using our blog!');
    }

    public function toArray($notifiable)
    {
        return [
            'comment_id' => $this->comment->id,
            'user_id' => $this->comment->user->id,
            'user_name' => $this->comment->user->name,
            'post_id' => $this->comment->post->id,
            'post_title' => $this->comment->post->title,
            'comment_excerpt' => substr($this->comment->content, 0, 50) . (strlen($this->comment->content) > 50 ? '...' : ''),
        ];
    }
} 