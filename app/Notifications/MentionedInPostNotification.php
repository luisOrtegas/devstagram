<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MentionedInPostNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Post $post,
        private User $author
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'post_mention',
            'actor_name' => $this->author->name,
            'actor_username' => $this->author->username,
            'actor_image' => $this->author->imagen,
            'post_id' => $this->post->id,
            'post_title' => $this->post->titulo,
            'message' => "{$this->author->name} te mencionó en una publicación.",
        ];
    }
}
