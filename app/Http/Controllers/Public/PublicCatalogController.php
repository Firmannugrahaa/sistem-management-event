<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\VendorCatalogItem;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    public function show($id)
    {
        $item = VendorCatalogItem::with(['vendor.user', 'images', 'category'])
            ->where('id', $id)
            ->firstOrFail();

        // Ensure the item is active/available if needed, or just show it
        // Check if vendor is active
        if (!$item->vendor->is_active) {
            abort(404);
        }

        return view('public.catalog.show', compact('item'));
    }
}
