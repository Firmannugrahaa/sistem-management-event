<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceTypeController extends Controller
{
    /**
     * Display a listing of service types.
     */
    public function index()
    {
        $serviceTypes = ServiceType::withCount('vendors')
            ->orderBy('name')
            ->get();
            
        return view('service-types.index', compact('serviceTypes'));
    }

    /**
     * Show the form for creating a new service type.
     */
    public function create()
    {
        return view('service-types.create');
    }

    /**
     * Store a newly created service type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_types,name',
            'description' => 'nullable|string|max:1000',
        ]);

        $serviceType = ServiceType::create($validated);

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Service type created successfully',
                'serviceType' => $serviceType
            ]);
        }

        return redirect()->route('service-types.index')
            ->with('success', 'Service type "' . $serviceType->name . '" created successfully.');
    }

    /**
     * Show the form for editing the specified service type.
     */
    public function edit(ServiceType $serviceType)
    {
        return view('service-types.edit', compact('serviceType'));
    }

    /**
     * Update the specified service type.
     */
    public function update(Request $request, ServiceType $serviceType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_types,name,' . $serviceType->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $serviceType->update($validated);

        return redirect()->route('service-types.index')
            ->with('success', 'Service type "' . $serviceType->name . '" updated successfully.');
    }

    /**
     * Remove the specified service type.
     */
    public function destroy(ServiceType $serviceType)
    {
        // Check if service type has vendors
        if ($serviceType->vendors()->count() > 0) {
            return redirect()->route('service-types.index')
                ->with('error', 'Cannot delete service type with associated vendors.');
        }

        $name = $serviceType->name;
        $serviceType->delete();

        return redirect()->route('service-types.index')
            ->with('success', 'Service type "' . $name . '" deleted successfully.');
    }

    /**
     * Quick create service type via AJAX (for inline creation in forms)
     */
    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_types,name',
            'description' => 'nullable|string|max:500',
        ]);

        $serviceType = ServiceType::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Service type "' . $serviceType->name . '" created successfully!',
            'data' => [
                'id' => $serviceType->id,
                'name' => $serviceType->name,
                'description' => $serviceType->description,
            ]
        ]);
    }

    /**
     * Get all service types as JSON (for dynamic selects)
     */
    public function list()
    {
        $serviceTypes = ServiceType::orderBy('name')->get(['id', 'name', 'description']);
        
        return response()->json([
            'success' => true,
            'data' => $serviceTypes
        ]);
    }
}
