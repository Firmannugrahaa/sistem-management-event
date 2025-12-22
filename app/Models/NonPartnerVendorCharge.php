<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NonPartnerVendorCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_request_id',
        'event_id',
        'service_type',
        'vendor_name',
        'vendor_contact',
        'notes',
        'charge_amount',
    ];

    protected $casts = [
        'charge_amount' => 'decimal:2',
    ];

    /**
     * Get the client request this charge belongs to
     */
    public function clientRequest(): BelongsTo
    {
        return $this->belongsTo(ClientRequest::class);
    }

    /**
     * Get the event this charge belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get formatted charge amount
     */
    public function getFormattedChargeAttribute(): string
    {
        return 'Rp ' . number_format($this->charge_amount, 0, ',', '.');
    }
}
