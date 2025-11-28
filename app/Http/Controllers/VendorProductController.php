<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        // Check permission, with fallback for Owner/Admin roles
        if (!$user->can('manage_services') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403, 'Unauthorized action.');
        }

        $vendor = null;

        if ($user->hasRole('Vendor') || $user->hasRole('Owner')) {
            $vendor = $user->vendor;
        } elseif ($user->hasRole('Admin') || $user->hasRole('Staff')) {
            // Admin/Staff manage their Owner's vendor profile
            $owner = \App\Models\User::find($user->owner_id);
            if ($owner) {
                $vendor = $owner->vendor;
            }
        }

        if (!$vendor) {
            // If no vendor profile exists, redirect to create one or show error
            // For Owner/Admin/Staff, redirect to business profile creation
            if ($user->hasRole(['Owner', 'Admin', 'Staff'])) {
                 return redirect()->route('vendor.business-profile.edit')
                    ->with('warning', 'Profil bisnis belum lengkap. Silakan lengkapi terlebih dahulu.');
            }
            abort(404, 'Vendor profile not found.');
        }

        $products = $vendor->products()->orderBy('created_at', 'desc')->paginate(10);

        return view('vendor.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->can('manage_services') && !Auth::user()->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }
        return view('vendor.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->can('manage_services') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
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
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:100',
        ]);

        $vendor->products()->create($validated);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        if (!$user->can('manage_services') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
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

        $product = $vendor->products()->findOrFail($id);

        return view('vendor.product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->can('manage_services') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
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

        $product = $vendor->products()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:100',
        ]);

        $product->update($validated);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Layanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user->can('manage_services') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
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

        $product = $vendor->products()->findOrFail($id);

        $product->delete();

        return redirect()->route('vendor.products.index')
            ->with('success', 'Layanan berhasil dihapus!');
    }
}
