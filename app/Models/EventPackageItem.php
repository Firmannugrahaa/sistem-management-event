<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPackageItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_package_id',
        'vendor_product_id',
        'custom_item_name',
        'quantity',
    ];

    public function package()
    {
        return $this->belongsTo(EventPackage::class, 'event_package_id');
    }

    public function product()
    {
        return $this->belongsTo(VendorProduct::class, 'vendor_product_id');
    }

    public function vendorProduct()
    {
        return $this->belongsTo(VendorProduct::class, 'vendor_product_id');
    }
}
