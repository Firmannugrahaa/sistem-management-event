<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'venue_id',
        'client_request_id',
        'event_name',
        'description',
        'start_time',
        'end_time',
        'status',
        'manual_status_override',
        'client_name',
        'client_phone',
        'client_email',
        'client_address',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function clientRequest(): BelongsTo
    {
        return $this->belongsTo(ClientRequest::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }
    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class)
            ->withPivot('agreed_price', 'contract_details', 'status');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function crews(): HasMany
    {
        return $this->hasMany(EventCrew::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(EventTask::class);
    }

    public function vendorItems(): HasMany
    {
        return $this->hasMany(EventVendorItem::class);
    }

    /**
     * Get non-partner vendor charges for this event
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
     * Check if this event is from a package booking
     */
    public function isPackageBooking(): bool
    {
        return $this->clientRequest && $this->clientRequest->event_package_id !== null;
    }

    /**
     * Check if this event is from a custom booking
     */
    public function isCustomBooking(): bool
    {
        return !$this->isPackageBooking();
    }

    /**
     * Get the event package (if package booking)
     */
    public function getEventPackageAttribute()
    {
        return $this->clientRequest?->eventPackage;
    }

    /**
     * Get computed event status (smart auto or manual)
     */
    public function getComputedStatusAttribute(): string
    {
        // If manual override is enabled, use the manual status
        if ($this->manual_status_override && $this->status) {
            return $this->status;
        }

        // Otherwise, auto-calculate based on date and payment
        return $this->calculateAutoStatus();
    }

    /**
     * Calculate automatic status based on event date and payment
     */
    protected function calculateAutoStatus(): string
    {
        $today = now()->startOfDay();
        $eventDate = $this->start_time->startOfDay();

        // Event in the future
        if ($eventDate->greaterThan($today)) {
            return 'Planning';
        }

        // Event is today  
        if ($eventDate->equalTo($today)) {
            return 'Ongoing';
        }

        // Event in the past
        if ($eventDate->lessThan($today)) {
            // Check invoice payment status
            if ($this->invoice && $this->invoice->status === 'Paid') {
                return 'Completed';
            }
            return 'Planning'; // Finished but payment pending
        }

        return 'Planning'; // Default
    }

    /**
     * Get status badge color class
     */
    public function getStatusBadgeColorAttribute(): string
    {
        $status = $this->computed_status;

        return match($status) {
            'Planning' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            'Confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'Ongoing' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            'Completed' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'Cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Check if the event is locked (Completed or Cancelled).
     * Locked events cannot be modified.
     */
    public function getIsLockedAttribute(): bool
    {
        return in_array($this->status, ['Completed', 'Cancelled']);
    }

    /**
     * Get reviews for this event
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
