<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VendorPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'name',
        'description',
        'price',
        'benefits',
        'thumbnail_path',
        'is_visible',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'benefits' => 'array',
        'is_visible' => 'boolean',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(VendorProduct::class, 'vendor_package_services', 'package_id', 'service_id')
            ->withTimestamps();
    }

    // Calculate total price if services were bought individually
    public function getIndividualPriceAttribute()
    {
        return $this->services->sum('price');
    }

    // Calculate savings
    public function getSavingsAttribute()
    {
        return $this->individual_price - $this->price;
    }

    // Calculate savings percentage
    public function getSavingsPercentageAttribute()
    {
        if ($this->individual_price == 0) return 0;
        return round(($this->savings / $this->individual_price) * 100);
    }
}
