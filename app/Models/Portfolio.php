<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'client',
        'project_date',
        'category',
        'location',
        'status',
        'order',
    ];

    protected $casts = [
        'project_date' => 'date',
    ];

    /**
     * Get the images for the portfolio.
     */
    public function images()
    {
        return $this->hasMany(PortfolioImage::class)->orderBy('order');
    }

    /**
     * Get the first image (cover image) from images relationship if image col is empty, or prefer one or the other.
     * Logic: Use 'image' column as cover if present, otherwise use first image from 'images'.
     */
    public function getCoverImageAttribute()
    {
        if ($this->image) {
            return $this->image;
        }
        return $this->images->first()?->image_path;
    }
}