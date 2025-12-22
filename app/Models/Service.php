<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'image',
        'category',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'duration' => 'integer',
    ];

    protected $attributes = [
        'is_available' => true,
    ];

    // Relasi Many-to-Many: Layanan bisa disediakan oleh banyak vendor
    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_services')
                    ->withPivot('price', 'description', 'is_available')
                    ->withTimestamps();
    }
}