<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserApprovedNotification extends Notification
{
    use Queueable;

    public $user;
    public $approver;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, User $approver)
    {
        $this->user = $user;
        $this->approver = $approver;
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
            'message' => 'Your account has been approved by ' . $this->approver->name,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'approver_name' => $this->approver->name,
            'action' => url('/login'), // Redirect to login page
        ];
    }
}
