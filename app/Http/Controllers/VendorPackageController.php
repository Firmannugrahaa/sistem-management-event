<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorPackageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->can('manage_packages') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403, 'Unauthorized action.');
        }

        $vendor = null;
        if ($user->hasRole('Vendor') || $user->hasRole('Owner')) {
            $vendor = $user->vendor;
        } elseif ($user->hasRole('Admin') || $user->hasRole('Staff')) {
            $owner = \App\Models\User::find($user->owner_id);
            if ($owner) {
                $vendor = $owner->vendor;
            }
        }

        $packages = $vendor ? $vendor->packages()->paginate(10) : collect();
        
        return view('vendor.packages.index', compact('packages', 'vendor'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->can('manage_packages') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $vendor = null;
        if ($user->hasRole('Vendor') || $user->hasRole('Owner')) {
            $vendor = $user->vendor;
        } elseif ($user->hasRole('Admin') || $user->hasRole('Staff')) {
            $owner = \App\Models\User::find($user->owner_id);
            if ($owner) {
                $vendor = $owner->vendor;
            }
        }

        if (!$vendor) {
            abort(404, 'Vendor profile not found.');
        }
        
        // Get all services for this vendor
        $services = $vendor->products;
        
        return view('vendor.packages.create', compact('vendor', 'services'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->can('manage_packages') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $vendor = null;
        if ($user->hasRole('Vendor') || $user->hasRole('Owner')) {
            $vendor = $user->vendor;
        } elseif ($user->hasRole('Admin') || $user->hasRole('Staff')) {
            $owner = \App\Models\User::find($user->owner_id);
            if ($owner) {
                $vendor = $owner->vendor;
            }
        }

        if (!$vendor) {
            abort(404, 'Vendor profile not found.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:vendor_products,id',
            'benefits' => 'nullable|array',
            'benefits.*' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_visible' => 'boolean',
        ]);

        // Verify all selected services belong to this vendor
        $serviceIds = $request->services;
        $validServices = $vendor->products()->whereIn('id', $serviceIds)->pluck('id')->toArray();
        
        if (count($validServices) !== count($serviceIds)) {
            return back()->withErrors(['services' => 'Some selected services do not belong to you.'])->withInput();
        }

        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('vendor-packages', 'public');
        }

        // Filter out empty benefits
        $benefits = array_filter($request->benefits ?? [], function($benefit) {
            return !empty(trim($benefit));
        });

        $package = $vendor->packages()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'benefits' => array_values($benefits),
            'thumbnail_path' => $thumbnailPath,
            'is_visible' => $request->has('is_visible'),
        ]);

        // Attach services
        $package->services()->attach($serviceIds);

        return redirect()->route('vendor.packages.index')
            ->with('success', 'Paket berhasil dibuat.');
    }

    public function edit(VendorPackage $package)
    {
        $user = Auth::user();
        if (!$user->can('manage_packages') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $vendor = null;
        if ($user->hasRole('Vendor') || $user->hasRole('Owner')) {
            $vendor = $user->vendor;
        } elseif ($user->hasRole('Admin') || $user->hasRole('Staff')) {
            $owner = \App\Models\User::find($user->owner_id);
            if ($owner) {
                $vendor = $owner->vendor;
            }
        }

        if (!$vendor) {
            abort(404, 'Vendor profile not found.');
        }
        
        // Ensure package belongs to this vendor
        if ($package->vendor_id !== $vendor->id) {
            abort(403);
        }
        
        $services = $vendor->products;
        $package->load('services');
        
        return view('vendor.packages.edit', compact('package', 'vendor', 'services'));
    }

    public function update(Request $request, VendorPackage $package)
    {
        $user = Auth::user();
        if (!$user->can('manage_packages') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $vendor = null;
        if ($user->hasRole('Vendor') || $user->hasRole('Owner')) {
            $vendor = $user->vendor;
        } elseif ($user->hasRole('Admin') || $user->hasRole('Staff')) {
            $owner = \App\Models\User::find($user->owner_id);
            if ($owner) {
                $vendor = $owner->vendor;
            }
        }

        if (!$vendor) {
            abort(404, 'Vendor profile not found.');
        }
        
        // Ensure package belongs to this vendor
        if ($package->vendor_id !== $vendor->id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:vendor_products,id',
            'benefits' => 'nullable|array',
            'benefits.*' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_visible' => 'boolean',
        ]);

        // Verify all selected services belong to this vendor
        $serviceIds = $request->services;
        $validServices = $vendor->products()->whereIn('id', $serviceIds)->pluck('id')->toArray();
        
        if (count($validServices) !== count($serviceIds)) {
            return back()->withErrors(['services' => 'Some selected services do not belong to you.'])->withInput();
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($package->thumbnail_path) {
                Storage::disk('public')->delete($package->thumbnail_path);
            }
            $thumbnailPath = $request->file('thumbnail')->store('vendor-packages', 'public');
            $package->thumbnail_path = $thumbnailPath;
        }

        // Filter out empty benefits
        $benefits = array_filter($request->benefits ?? [], function($benefit) {
            return !empty(trim($benefit));
        });

        $package->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'benefits' => array_values($benefits),
            'is_visible' => $request->has('is_visible'),
        ]);

        // Sync services
        $package->services()->sync($serviceIds);

        return redirect()->route('vendor.packages.index')
            ->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(VendorPackage $package)
    {
        $user = Auth::user();
        if (!$user->can('manage_packages') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $vendor = null;
        if ($user->hasRole('Vendor') || $user->hasRole('Owner')) {
            $vendor = $user->vendor;
        } elseif ($user->hasRole('Admin') || $user->hasRole('Staff')) {
            $owner = \App\Models\User::find($user->owner_id);
            if ($owner) {
                $vendor = $owner->vendor;
            }
        }

        if (!$vendor) {
            abort(404, 'Vendor profile not found.');
        }
        
        // Ensure package belongs to this vendor
        if ($package->vendor_id !== $vendor->id) {
            abort(403);
        }
        
        // Delete thumbnail
        if ($package->thumbnail_path) {
            Storage::disk('public')->delete($package->thumbnail_path);
        }
        
        $package->delete();
        
        return redirect()->route('vendor.packages.index')
            ->with('success', 'Paket berhasil dihapus.');
    }
}
