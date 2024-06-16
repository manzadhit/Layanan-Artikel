<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PostSaved extends Notification
{
    use Queueable;

    protected $saver;
    protected $post;

    public function __construct($saver, $post)
    {
        $this->saver = $saver;
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'saver_id' => $this->saver->id,
            'saver_name' => $this->saver->name,
            'post_id' => $this->post->id,
            'post_slug' => $this->post->slug,
            'post_title' => $this->post->title,
        ];
    }
}
