<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPortfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'title',
        'description',
        'category',
        'project_date',
        'client_name',
        'location',
        'status',
        'order',
    ];

    protected $casts = [
        'project_date' => 'date',
    ];

    /**
     * Get the vendor that owns the portfolio.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the images for the portfolio.
     */
    public function images()
    {
        return $this->hasMany(VendorPortfolioImage::class, 'portfolio_id')->orderBy('order');
    }

    /**
     * Get the first image (cover image).
     */
    public function coverImage()
    {
        return $this->hasOne(VendorPortfolioImage::class, 'portfolio_id')->orderBy('order');
    }

    /**
     * Scope to get only published portfolios.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to order by custom order field.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }
}
