<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecommendationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_recommendation_id',
        'vendor_id',
        'external_vendor_name',
        'category',
        'service_name',
        'recommendation_type',
        'estimated_price',
        'status',
        'rejection_reason',
        'notes',
        'order'
    ];

    protected $casts = [
        'estimated_price' => 'decimal:2',
    ];

    public function recommendation(): BelongsTo
    {
        return $this->belongsTo(LeadRecommendation::class, 'lead_recommendation_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    // Helper to get vendor name (internal or external)
    public function getVendorNameAttribute(): string
    {
        if ($this->vendor) {
            return $this->vendor->name ?? 'Unknown Vendor';
        }
        return $this->external_vendor_name ?? 'Unknown Vendor';
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-gray-100 text-gray-800',
            'accepted' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getRecommendationTypeBadgeColorAttribute(): string
    {
        return match($this->recommendation_type) {
            'primary' => 'bg-blue-100 text-blue-800',
            'alternative' => 'bg-purple-100 text-purple-800',
            'upgrade' => 'bg-amber-100 text-amber-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
