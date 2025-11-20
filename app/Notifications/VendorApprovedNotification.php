<?php

namespace App\Notifications;

use App\Models\Vendor;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorApprovedNotification extends Notification
{
    use Queueable;

    public $vendor;
    public $approver;

    /**
     * Create a new notification instance.
     */
    public function __construct(Vendor $vendor, User $approver)
    {
        $this->vendor = $vendor;
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
            'message' => 'Your vendor "' . $this->vendor->name . '" has been approved by ' . $this->approver->name,
            'vendor_id' => $this->vendor->id,
            'vendor_name' => $this->vendor->name,
            'approver_name' => $this->approver->name,
            'action' => url('/'), // Redirect to dashboard or vendor page
        ];
    }
}
