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
        'estimated_price',
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
        return $this->vendor ? $this->vendor->name : ($this->external_vendor_name ?? 'Unknown Vendor');
    }
}
