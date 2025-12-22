<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorCatalogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VendorCatalogCategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        $categories = $vendor->catalogCategories()->withCount('items')->get();

        return view('vendor.catalog.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $vendor->catalogCategories()->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        $category = $vendor->catalogCategories()->findOrFail($id);
        
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
