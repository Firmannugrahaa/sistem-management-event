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
        'groom_name',
        'bride_name',
        'fill_couple_later',
        'client_email',
        'client_phone',
        'event_date',
        'budget',
        'event_type',
        'message',
        'cpp_name',           // Calon Pengantin Pria
        'cpw_name',           // Calon Pengantin Wanita
        'fill_couple_later',  // Flag: fill couple names later
        'booking_number',     // Unique booking reference number
        'status',
        'detailed_status',
        'priority',
        'assigned_to',
        'vendor_id',
        'event_package_id',
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
        'fill_couple_later' => 'boolean',
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
     * Get the event package selected for this request
     */
    public function eventPackage(): BelongsTo
    {
        return $this->belongsTo(EventPackage::class);
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
     * Get non-partner vendor charges for this request
     */
    public function nonPartnerCharges(): HasMany
    {
        return $this->hasMany(NonPartnerVendorCharge::class);
    }

    /**
     * Get total non-partner charges amount
     */
    public function getTotalNonPartnerChargesAttribute(): float
    {
        return $this->nonPartnerCharges()->sum('charge_amount');
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
            'done' => 'Confirmed', // Display as "Confirmed" instead of "Done"
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
            'ready_to_confirm' => 'bg-amber-100 text-amber-800 border border-amber-200',
            'confirmed' => 'bg-emerald-100 text-emerald-800 font-bold',
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
            'ready_to_confirm' => 'Ready to Confirm',
            'confirmed' => 'Confirmed',
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
     * Calculate total estimated price based on booking source
     * Returns null if no price information available
     */
    public function getTotalPriceAttribute(): ?float
    {
        // Priority 1: If from event package
        if ($this->eventPackage) {
            return (float) $this->eventPackage->final_price;
        }
        
        // Priority 2: If has accepted recommendations
        $acceptedRecommendations = \App\Models\RecommendationItem::whereHas('recommendation', function($q) {
            $q->where('client_request_id', $this->id);
        })->where('status', 'accepted')->get();
        
        if ($acceptedRecommendations->count() > 0) {
            return (float) $acceptedRecommendations->sum('min_price');
        }
        
        // Priority 3: Return null (no price data available yet)
        // Budget is just estimation, not actual price
        return null;
    }

    /**
     * Get price source label for display
     */
    public function getPriceSourceAttribute(): string
    {
        if ($this->eventPackage) {
            return 'Dari Paket';
        }
        
        $acceptedRecommendations = \App\Models\RecommendationItem::whereHas('recommendation', function($q) {
            $q->where('client_request_id', $this->id);
        })->where('status', 'accepted')->count();
        
        if ($acceptedRecommendations > 0) {
            return 'Estimasi Rekomendasi';
        }
        
        return 'Belum ada harga';
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

    /**
     * Get effective status for client display, syncing detailed_status with main status.
     * This handles cases where detailed_status wasn't updated by legacy code.
     */
    public function getEffectiveStatusAttribute()
    {
        // On Process Sync
        if ($this->status === 'on_process') {
            // If detailed status is still 'introductory' stages, force it to 'on_process'
            // But if it's already 'recommendation_sent' or 'approved', keep it.
            if (in_array($this->detailed_status, ['new', 'pending', 'contacted'])) {
                return 'on_process';
            }
        }
        
        // Done Sync
        if ($this->status === 'done') {
            if (!in_array($this->detailed_status, ['done', 'completed'])) {
                return 'done';
            }
        }

        return $this->detailed_status;
    }

    /**
     * Check if booking is ready to be converted to an event
     */
    public function isReadyToConvert(): bool
    {
        $checklist = $this->getReadinessChecklist();
        
        // Check if ALL checklist items are completed
        foreach ($checklist as $item) {
            if (!$item['completed']) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Check if all required data is present
     */
    public function hasCompleteData(): bool
    {
        return $this->hasPackageOrVendor() 
            && $this->hasEventDate()
            && $this->hasCompleteClientDetails();
    }

    /**
     * Check if booking has package or vendor assigned
     */
    public function hasPackageOrVendor(): bool
    {
        return $this->event_package_id !== null 
            || $this->vendor_id !== null
            || $this->recommendations()->where('status', 'accepted')->exists()
            || \App\Models\RecommendationItem::whereHas('recommendation', function($q) {
                $q->where('client_request_id', $this->id);
            })->where('status', 'accepted')->exists();
    }

    /**
     * Check if event date is set
     */
    public function hasEventDate(): bool
    {
        return $this->event_date !== null;
    }

    /**
     * Check if client details are complete
     */
    public function hasCompleteClientDetails(): bool
    {
        $hasBasicInfo = !empty($this->client_name) 
            && !empty($this->client_email) 
            && !empty($this->client_phone);

        // For wedding events, check couple names unless fill_later is set
        if ($this->event_type === 'Wedding' && !$this->fill_couple_later) {
            return $hasBasicInfo 
                && !empty($this->groom_name) 
                && !empty($this->bride_name);
        }

        return $hasBasicInfo;
    }

    /**
     * Get readiness checklist for event conversion
     */
    public function getReadinessChecklist(): array
    {
        return [
            'package_or_vendor' => [
                'label' => 'Paket atau Vendor Dipilih',
                'completed' => $this->hasPackageOrVendor(),
                'description' => 'Client harus memilih paket, vendor, atau menyetujui rekomendasi'
            ],
            'event_date' => [
                'label' => 'Tanggal Event',
                'completed' => $this->hasEventDate(),
                'description' => 'Tanggal pelaksanaan event harus sudah ditentukan'
            ],
            'client_details' => [
                'label' => 'Data Client Lengkap',
                'completed' => $this->hasCompleteClientDetails(),
                'description' => 'Nama, email, telepon (dan nama pasangan untuk wedding)'
            ],
            'budget_set' => [
                'label' => 'Budget / Harga Ditentukan',
                'completed' => $this->budget > 0 || ($this->event_package_id !== null && $this->eventPackage && $this->eventPackage->final_price > 0),
                'description' => 'Budget atau harga paket sudah ditentukan'
            ],
        ];
    }

    /**
     * Get percentage of checklist completion
     */
    public function getReadinessPercentage(): int
    {
        $checklist = $this->getReadinessChecklist();
        $completed = collect($checklist)->where('completed', true)->count();
        $total = count($checklist);
        
        return $total > 0 ? round(($completed / $total) * 100) : 0;
    }
}
