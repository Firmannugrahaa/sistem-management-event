<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index()
    {
        $this->authorize('manage_services');
        
        $services = Service::with('vendors')->paginate(10);
        $serviceTypes = ServiceType::all();

        return view('services.index', compact('services', 'serviceTypes'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $this->authorize('manage_services');
        
        $serviceTypes = ServiceType::all();
        return view('services.create', compact('serviceTypes'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('manage_services');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'category' => 'nullable|string|max:255',
            'is_available' => 'nullable|boolean',
        ]);

        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'category' => $request->category,
            'is_available' => $request->has('is_available'),
        ]);

        return redirect()->route('services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        $this->authorize('manage_services');
        
        $serviceTypes = ServiceType::all();
        return view('services.edit', compact('service', 'serviceTypes'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        $this->authorize('manage_services');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'category' => 'nullable|string|max:255',
            'is_available' => 'nullable|boolean',
        ]);

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'category' => $request->category,
            'is_available' => $request->has('is_available'),
        ]);

        return redirect()->route('services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service)
    {
        $this->authorize('manage_services');
        
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Layanan berhasil dihapus.');
    }
}