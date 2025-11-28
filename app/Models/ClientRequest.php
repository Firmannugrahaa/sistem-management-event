<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'client_name',
        'client_email',
        'client_phone',
        'event_date',
        'budget',
        'event_type',
        'message',
        'status',
        'detailed_status',
        'priority',
        'assigned_to',
        'vendor_id',
        'request_source',
        'notes',
        'responded_at',
        'last_contacted_at',
        'follow_up_count',
        'deleted_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'budget' => 'decimal:2',
        'responded_at' => 'datetime',
        'last_contacted_at' => 'datetime',
        'follow_up_count' => 'integer',
    ];

    protected $hidden = [
        // Hide sensitive data in API responses if needed
    ];

    /**
     * Get the client user who made the request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the staff member assigned to this request
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Alias for assignee() - for backward compatibility
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }


    /**
     * Get the vendor associated with this request
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the event created from this request
     */
    public function event()
    {
        return $this->hasOne(Event::class, 'client_request_id');
    }

    /**
     * Get recommendations for this request
     */
    public function recommendations(): HasMany
    {
        return $this->hasMany(LeadRecommendation::class)->orderBy('created_at', 'desc');
    }

    /**
     * Check if request has been converted to event
     */
    public function isConverted(): bool
    {
        return $this->event()->exists();
    }

    /**
     * Get the user who deleted this request
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }


    /**
     * Scope for filtering by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by assigned user
     */
    public function scopeAssignedUser($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'on_process' => 'bg-blue-100 text-blue-800',
            'done' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get formatted status text
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'on_process' => 'On Process',
            'done' => 'Done',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get detailed status badge color
     */
    public function getDetailedStatusBadgeColorAttribute(): string
    {
        return match($this->detailed_status) {
            'new' => 'bg-blue-100 text-blue-800',
            'contacted' => 'bg-indigo-100 text-indigo-800',
            'need_recommendation' => 'bg-purple-100 text-purple-800',
            'recommendation_sent' => 'bg-pink-100 text-pink-800',
            'waiting_client_response' => 'bg-yellow-100 text-yellow-800',
            'revision_requested' => 'bg-orange-100 text-orange-800',
            'quotation_sent' => 'bg-cyan-100 text-cyan-800',
            'waiting_approval' => 'bg-amber-100 text-amber-800',
            'approved' => 'bg-green-100 text-green-800',
            'converted_to_event' => 'bg-emerald-100 text-emerald-800',
            'rejected' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'on_hold' => 'bg-slate-100 text-slate-800',
            'lost' => 'bg-stone-100 text-stone-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get detailed status text
     */
    public function getDetailedStatusTextAttribute(): string
    {
        return match($this->detailed_status) {
            'new' => 'New Lead',
            'contacted' => 'Contacted',
            'need_recommendation' => 'Need Recommendation',
            'recommendation_sent' => 'Recommendation Sent',
            'waiting_client_response' => 'Waiting Client Response',
            'revision_requested' => 'Revision Requested',
            'quotation_sent' => 'Quotation Sent',
            'waiting_approval' => 'Waiting Approval',
            'approved' => 'Approved',
            'converted_to_event' => 'Converted to Event',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            'on_hold' => 'On Hold',
            'lost' => 'Lost',
            default => ucfirst(str_replace('_', ' ', $this->detailed_status)),
        };
    }

    /**
     * Get priority badge color
     */
    public function getPriorityBadgeColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'bg-red-100 text-red-800 border border-red-300',
            'high' => 'bg-orange-100 text-orange-800',
            'medium' => 'bg-blue-100 text-blue-800',
            'low' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get priority text
     */
    public function getPriorityTextAttribute(): string
    {
        return ucfirst($this->priority);
    }

    /**
     * Check if lead is overdue for contact
     * SLA: Should be contacted within 24 hours
     */
    public function isOverdueForContact(): bool
    {
        if ($this->detailed_status !== 'new') {
            return false;
        }

        return $this->created_at->diffInHours(now()) > 24;
    }

    /**
     * Check if waiting for client response too long
     * SLA: Client should respond within 72 hours
     */
    public function isOverdueForClientResponse(): bool
    {
        if (!in_array($this->detailed_status, ['waiting_client_response', 'waiting_approval'])) {
            return false;
        }

        if (!$this->last_contacted_at) {
            return false;
        }

        return $this->last_contacted_at->diffInHours(now()) > 72;
    }
}
