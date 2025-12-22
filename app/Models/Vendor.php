<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ServiceType;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_type_id',
        'category',
        'contact_person',
        'phone_number',
        'address',
        'brand_name',
        'logo_path',
        'description',
        'email',
        'whatsapp',
        'location',
        'instagram',
        'tiktok',
        'facebook',
        'operating_hours',
        'is_active',
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'is_active' => 'boolean',
    ];

    // Relasi Many-to-Many: Vendor ini bisa bekerja di banyak event
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->withPivot('agreed_price', 'contract_details', 'status');
            
    }

    // Relasi One-to-One (inverse): Vendor ini dimiliki oleh satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi One-to-One (inverse): Vendor ini memiliki satu ServiceType
    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    // Relasi Many-to-Many: Vendor bisa menyediakan banyak layanan
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'vendor_services')
            ->withPivot('price', 'description', 'is_available')
            ->withTimestamps();
    }

    // Relasi One-to-Many: Vendor memiliki banyak portfolio
    public function portfolios(): HasMany
    {
        return $this->hasMany(VendorPortfolio::class);
    }

    // Get published portfolios only
    public function publishedPortfolios(): HasMany
    {
        return $this->hasMany(VendorPortfolio::class)->where('status', 'published')->ordered();
    }

    // Relasi One-to-Many: Vendor memiliki banyak produk/layanan
    public function products(): HasMany
    {
        return $this->hasMany(VendorProduct::class);
    }

    // Catalog Relationships
    public function catalogCategories(): HasMany
    {
        return $this->hasMany(VendorCatalogCategory::class);
    }

    public function catalogItems(): HasMany
    {
        return $this->hasMany(VendorCatalogItem::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(VendorPackage::class);
    }

    public function getNameAttribute(): string
    {
        return $this->brand_name ?? $this->user->name ?? 'Unnamed Vendor';
    }
}
