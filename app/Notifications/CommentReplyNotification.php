<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reply;

    public function __construct(Comment $reply)
    {
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->reply->user->name . ' replied to your comment')
                    ->line($this->reply->user->name . ' replied to your comment on the post: ' . $this->reply->post->title)
                    ->line('"' . substr($this->reply->content, 0, 50) . (strlen($this->reply->content) > 50 ? '...' : '') . '"')
                    ->action('View Reply', url('/posts/' . $this->reply->post->id . '#comment-' . $this->reply->id))
                    ->line('Thank you for using our blog!');
    }

    public function toArray($notifiable)
    {
        return [
            'reply_id' => $this->reply->id,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'post_id' => $this->reply->post->id,
            'post_title' => $this->reply->post->title,
            'reply_excerpt' => substr($this->reply->content, 0, 50) . (strlen($this->reply->content) > 50 ? '...' : ''),
        ];
    }
} 