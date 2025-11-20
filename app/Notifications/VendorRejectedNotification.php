<?php

namespace App\Notifications;

use App\Models\Vendor;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorRejectedNotification extends Notification
{
    use Queueable;

    public $vendor;
    public $rejector;

    /**
     * Create a new notification instance.
     */
    public function __construct(Vendor $vendor, User $rejector)
    {
        $this->vendor = $vendor;
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
            'message' => 'Your vendor "' . $this->vendor->name . '" has been rejected by ' . $this->rejector->name,
            'vendor_id' => $this->vendor->id,
            'vendor_name' => $this->vendor->name,
            'rejector_name' => $this->rejector->name,
            'action' => url('/'), // Redirect to dashboard or vendor page
        ];
    }
}
