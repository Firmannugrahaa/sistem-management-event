<?php

namespace App\Notifications;

use App\Models\Vendor;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorCreatedNotification extends Notification
{
    use Queueable;

    public $newVendor;
    public $adminCreator;

    /**
     * Create a new notification instance.
     */
    public function __construct(Vendor $newVendor, User $adminCreator)
    {
        $this->newVendor = $newVendor;
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
            'message' => 'New vendor "' . $this->newVendor->name . '" needs approval',
            'vendor_id' => $this->newVendor->id,
            'vendor_name' => $this->newVendor->name,
            'admin_name' => $this->adminCreator->name,
            'action' => route('vendors.index'), // Redirect to vendor management page
        ];
    }
}
