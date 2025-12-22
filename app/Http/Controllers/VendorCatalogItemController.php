<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorCatalogItem;
use App\Models\VendorCatalogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorCatalogItemController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        
        $items = $vendor->catalogItems()
            ->with(['category', 'images'])
            ->latest()
            ->paginate(12);

        return view('vendor.catalog.items.index', compact('items'));
    }

    public function create()
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        $categories = $vendor->catalogCategories;

        return view('vendor.catalog.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:vendor_catalog_categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,booked,not_available',
            'description' => 'nullable|string',
            'attributes_keys' => 'nullable|array',
            'attributes_values' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Process Dynamic Attributes
        $attributes = [];
        if ($request->has('attributes_keys') && $request->has('attributes_values')) {
            foreach ($request->attributes_keys as $index => $key) {
                if (!empty($key) && isset($request->attributes_values[$index])) {
                    $attributes[$key] = $request->attributes_values[$index];
                }
            }
        }

        $item = $vendor->catalogItems()->create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'] ?? null,
            'price' => $validated['price'],
            'status' => $validated['status'],
            'description' => $validated['description'] ?? null,
            'attributes' => !empty($attributes) ? $attributes : null,
            'show_stock'  => $request->has('show_stock'),
            'show_on_landing' => $request->has('show_on_landing'),
        ]);

        // Handle Image Uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('vendor-catalogs', 'public');
                $item->images()->create([
                    'image_path' => $path,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('vendor.catalog.items.index')
            ->with('success', 'Produk berhasil ditambahkan ke katalog!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        $item = $vendor->catalogItems()->with('images')->findOrFail($id);
        $categories = $vendor->catalogCategories;

        return view('vendor.catalog.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        $item = $vendor->catalogItems()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:vendor_catalog_categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,booked,not_available',
            'description' => 'nullable|string',
            'attributes_keys' => 'nullable|array',
            'attributes_values' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Process Dynamic Attributes
        $attributes = [];
        if ($request->has('attributes_keys') && $request->has('attributes_values')) {
            foreach ($request->attributes_keys as $index => $key) {
                if (!empty($key) && isset($request->attributes_values[$index])) {
                    $attributes[$key] = $request->attributes_values[$index];
                }
            }
        }

        $item->update([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'] ?? null,
            'price' => $validated['price'],
            'status' => $validated['status'],
            'description' => $validated['description'] ?? null,
            'attributes' => !empty($attributes) ? $attributes : null,
            'show_stock'  => $request->has('show_stock'), 
            'show_on_landing' => $request->has('show_on_landing'),
        ]);

        // Handle New Image Uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('vendor-catalogs', 'public');
                $item->images()->create([
                    'image_path' => $path,
                    'order' => $item->images()->count() + $index,
                ]);
            }
        }

        return redirect()->route('vendor.catalog.items.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        $item = $vendor->catalogItems()->findOrFail($id);

        // Delete images from storage
        foreach ($item->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $item->delete();

        return redirect()->route('vendor.catalog.items.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    public function destroyImage($id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        
        // Find image through item to ensure ownership
        $image = \App\Models\VendorCatalogImage::whereHas('item', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->findOrFail($id);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Foto berhasil dihapus!');
    }
}
