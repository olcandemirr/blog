<?php

namespace App\Notifications;

use App\Models\Like;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostLikedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->like->user->name . ' liked your post')
                    ->line($this->like->user->name . ' liked your post: ' . $this->like->post->title)
                    ->action('View Post', url('/posts/' . $this->like->post->id))
                    ->line('Thank you for using our blog!');
    }

    public function toArray($notifiable)
    {
        return [
            'like_id' => $this->like->id,
            'user_id' => $this->like->user->id,
            'user_name' => $this->like->user->name,
            'post_id' => $this->like->post->id,
            'post_title' => $this->like->post->title,
        ];
    }
} 