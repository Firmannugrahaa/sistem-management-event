<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRejectedNotification extends Notification
{
    use Queueable;

    public $user;
    public $rejector;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, User $rejector)
    {
        $this->user = $user;
        $this->rejector = $rejector;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your account has been rejected by ' . $this->rejector->name,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'rejector_name' => $this->rejector->name,
            'action' => url('/'), // Redirect to homepage
        ];
    }
}
