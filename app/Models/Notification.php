<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'data',
        'is_read'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship: Notification belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Scope: Only unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: Only read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Get icon based on notification type
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            'status_update' => '🔄',
            'event_created' => '📅',
            'recommendation_sent' => '💡',
            'staff_assigned' => '👤',
            'message_received' => '💬',
            default => '🔔'
        };
    }

    /**
     * Get color class based on notification type
     */
    public function getColorClassAttribute()
    {
        return match($this->type) {
            'status_update' => 'bg-blue-50 text-blue-600',
            'event_created' => 'bg-green-50 text-green-600',
            'recommendation_sent' => 'bg-purple-50 text-purple-600',
            'staff_assigned' => 'bg-yellow-50 text-yellow-600',
            'message_received' => 'bg-pink-50 text-pink-600',
            default => 'bg-gray-50 text-gray-600'
        };
    }
}
