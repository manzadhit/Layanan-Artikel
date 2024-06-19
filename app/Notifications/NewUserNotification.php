<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class NewUserNotification extends Notification
{
    use Queueable;

    protected $newUser;

    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
    }

    public function via($notifiable)
    {
        return ['database']; // You can also add 'mail' or other channels
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('A new user has registered.')
            ->action('View User', url('/users/' . $this->newUser->id))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'A new user has registered: ' . $this->newUser->name,
            'user_id' => $this->newUser->id,
        ];
    }
}
