<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPortfolioImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'image_path',
        'caption',
        'order',
    ];

    /**
     * Get the portfolio that owns the image.
     */
    public function portfolio()
    {
        return $this->belongsTo(VendorPortfolio::class, 'portfolio_id');
    }
}
