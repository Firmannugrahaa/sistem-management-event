<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPackageItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_package_id',
        'vendor_catalog_item_id',
        'vendor_package_id',
        'custom_item_name',
        'quantity',
        'unit_price',
        'total_price',
    ];

    // Accessor for item name
    public function getItemNameAttribute()
    {
        return $this->custom_item_name ?? $this->vendorCatalogItem->name ?? 'Item Paket';
    }

    public function package()
    {
        return $this->belongsTo(EventPackage::class, 'event_package_id');
    }

    public function product()
    {
        return $this->belongsTo(VendorProduct::class, 'vendor_product_id');
    }

    public function vendorCatalogItem()
    {
        return $this->belongsTo(VendorCatalogItem::class, 'vendor_catalog_item_id');
    }

    public function vendorPackage()
    {
        return $this->belongsTo(VendorPackage::class, 'vendor_package_id');
    }
}
