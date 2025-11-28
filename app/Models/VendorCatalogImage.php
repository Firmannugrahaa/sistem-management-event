<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorCatalogImage extends Model
{
    use HasFactory;

    protected $fillable = ['catalog_item_id', 'image_path', 'order'];

    public function item(): BelongsTo
    {
        return $this->belongsTo(VendorCatalogItem::class, 'catalog_item_id');
    }
}
