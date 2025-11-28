<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorServiceController extends Controller
{
    /**
     * Display a listing of the vendor's services.
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda bukan vendor.');
        }

        $vendorServices = $vendor->services()->with('vendors')->get();
        $availableServices = Service::where('is_available', true)->get();

        // Get service types for filtering
        $serviceTypes = ServiceType::all();

        return view('vendor-dashboard.services.index', compact('vendorServices', 'availableServices', 'serviceTypes', 'vendor'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda bukan vendor.');
        }

        $services = Service::where('is_available', true)->get();
        return view('vendor-dashboard.services.create', compact('vendor', 'services'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda bukan vendor.');
        }

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        // Check if vendor already has this service
        $exists = $vendor->services()->where('service_id', $request->service_id)->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Layanan ini sudah ditambahkan sebelumnya.');
        }

        $vendor->services()->attach($request->service_id, [
            'price' => $request->price ?? null,
            'description' => $request->description ?? null,
            'is_available' => true,
        ]);

        return redirect()->route('vendor.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit($id)
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda bukan vendor.');
        }

        $vendorService = $vendor->services()->where('vendor_services.id', $id)->with('vendors')->firstOrFail();
        $service = Service::findOrFail($vendorService->id);

        return view('vendor-dashboard.services.edit', compact('vendorService', 'service', 'vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda bukan vendor.');
        }

        $request->validate([
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'is_available' => 'nullable|boolean',
        ]);

        $vendor->services()->updateExistingPivot($id, [
            'price' => $request->price ?? null,
            'description' => $request->description ?? null,
            'is_available' => $request->is_available ?? true,
        ]);

        return redirect()->route('vendor.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda bukan vendor.');
        }

        $vendor->services()->detach($id);

        return redirect()->route('vendor.services.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
