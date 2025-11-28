<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorProduct;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyProductController extends Controller
{
    /**
     * Get or create the internal vendor profile for Owner/Admin
     */
    private function getInternalVendor()
    {
        $user = Auth::user();
        
        // For Admin/Staff, use Owner's vendor
        $userId = $user->id;
        if ($user->hasRole(['Admin', 'Staff']) && $user->owner_id) {
            $userId = $user->owner_id;
        }
        
        // Get or create internal vendor profile
        $vendor = Vendor::firstOrCreate(
            ['user_id' => $userId],
            [
                'brand_name' => 'Internal Services',
                'category' => 'Event Organizer',
                'contact_person' => $user->name,
                'phone_number' => $user->phone ?? '08123456789',
                'address' => 'Headquarters',
                'description' => 'Internal company services',
                'is_active' => true,
                'service_type_id' => ServiceType::first()->id ?? null,
            ]
        );
        
        return $vendor;
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403, 'Unauthorized action.');
        }

        $vendor = $this->getInternalVendor();
        $products = $vendor->products()->orderBy('created_at', 'desc')->paginate(10);

        return view('vendor.product.index', compact('products'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $vendor = $this->getInternalVendor();

        return view('vendor.product.create', compact('vendor'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $vendor = $this->getInternalVendor();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vendor-products', 'public');
        }

        $vendor->products()->create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'duration' => $validated['duration'],
            'capacity' => $validated['capacity'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('company.products.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(VendorProduct $product)
    {
        $user = Auth::user();
        if (!$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $vendor = $this->getInternalVendor();

        // Ensure product belongs to this vendor
        if ($product->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('vendor.product.edit', compact('product', 'vendor'));
    }

    public function update(Request $request, VendorProduct $product)
    {
        $user = Auth::user();
        if (!$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $vendor = $this->getInternalVendor();

        // Ensure product belongs to this vendor
        if ($product->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('vendor-products', 'public');
        }

        $product->update($validated);

        return redirect()->route('company.products.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(VendorProduct $product)
    {
        $user = Auth::user();
        if (!$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $vendor = $this->getInternalVendor();

        // Ensure product belongs to this vendor
        if ($product->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image if exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('company.products.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}
