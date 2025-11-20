<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification
{
    use Queueable;

    public $newUser;
    public $adminCreator;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $newUser, User $adminCreator)
    {
        $this->newUser = $newUser;
        $this->adminCreator = $adminCreator;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Using database notifications for simplicity
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'New user "' . $this->newUser->name . '" needs approval',
            'user_id' => $this->newUser->id,
            'user_name' => $this->newUser->name,
            'admin_name' => $this->adminCreator->name,
            'action' => route('superuser.users.index'), // Redirect to user management page
        ];
    }
}
