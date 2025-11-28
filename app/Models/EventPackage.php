<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'base_price',
        'discount_percentage',
        'markup_percentage',
        'final_price',
        'duration',
        'thumbnail_path',
        'image_url',
        'features',
        'is_active',
        'is_featured',
        'pricing_method',
        'created_by',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'markup_percentage' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(EventPackageItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Auto-calculate base price from items
    public function calculateBasePrice()
    {
        return $this->items()->sum('total_price') ?? 0;
    }

    // Calculate final price based on pricing method
    public function calculateFinalPrice()
    {
        $base = $this->base_price;
        
        if ($this->pricing_method === 'manual') {
            return $this->final_price; // Use manually set price
        }
        
        // For auto and hybrid methods
        $discount = ($base * $this->discount_percentage) / 100;
        $markup = ($base * $this->markup_percentage) / 100;
        
        return $base - $discount + $markup;
    }

    // Update prices automatically
    public function updatePrices()
    {
        if ($this->pricing_method !== 'manual') {
            $this->base_price = $this->calculateBasePrice();
        }
        
        $this->final_price = $this->calculateFinalPrice();
        $this->save();
    }

    // Get savings amount
    public function getSavingsAttribute()
    {
        if ($this->discount_percentage > 0) {
            return ($this->base_price * $this->discount_percentage) / 100;
        }
        return 0;
    }

    // Get discount percentage text
    public function getDiscountTextAttribute()
    {
        if ($this->discount_percentage > 0) {
            return "HEMAT {$this->discount_percentage}%";
        }
        return null;
    }

    // Format price for display
    public function getFormattedBasePriceAttribute()
    {
        return 'Rp ' . number_format($this->base_price, 0, ',', '.');
    }

    public function getFormattedFinalPriceAttribute()
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }

    public function getFormattedSavingsAttribute()
    {
        return 'Rp ' . number_format($this->savings, 0, ',', '.');
    }

    // Get package image
    public function getDisplayImageAttribute()
    {
        return $this->image_url ?? $this->thumbnail_path ?? 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1200&h=800&q=80';
    }
    
    // Starting price text
    public function getStartingPriceTextAttribute()
    {
        return 'Mulai dari ' . $this->formatted_final_price;
    }
}
