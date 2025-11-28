<?php

namespace App\Notifications;

use App\Models\ClientRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $clientRequest;
    protected $assignedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(ClientRequest $clientRequest)
    {
        $this->clientRequest = $clientRequest;
        $this->assignedBy = auth()->user();
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('client-requests.show', $this->clientRequest);
        
        return (new MailMessage)
            ->subject('New Lead Assigned to You')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new client request has been assigned to you.')
            ->line('**Client:** ' . $this->clientRequest->client_name)
            ->line('**Event Type:** ' . $this->clientRequest->event_type)
            ->line('**Event Date:** ' . $this->clientRequest->event_date->format('d M Y'))
            ->line('**Priority:** ' . ucfirst($this->clientRequest->priority))
            ->line('**Assigned by:** ' . $this->assignedBy->name)
            ->action('View Lead Details', $url)
            ->line('Please follow up within 24 hours.');
    }

    /**
     * Get the array representation of the notification (for database).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'lead_assigned',
            'client_request_id' => $this->clientRequest->id,
            'client_name' => $this->clientRequest->client_name,
            'event_type' => $this->clientRequest->event_type,
            'priority' => $this->clientRequest->priority,
            'assigned_by' => $this->assignedBy->name,
            'assigned_by_id' => $this->assignedBy->id,
            'message' => "New lead #{$this->clientRequest->id} ({$this->clientRequest->client_name}) assigned to you by {$this->assignedBy->name}",
        ];
    }
}
