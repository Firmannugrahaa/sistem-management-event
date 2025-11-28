<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorCatalogItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'description',
        'price',
        'status',
        'quantity',
        'show_stock',
        'show_on_landing',
        'attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'attributes' => 'array', // Automatically cast JSON to array
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VendorCatalogCategory::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(VendorCatalogImage::class, 'catalog_item_id')->orderBy('order');
    }

    // Helper to get the first image or a placeholder
    public function getCoverImageAttribute()
    {
        return $this->images->first();
    }
}
