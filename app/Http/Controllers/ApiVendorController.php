<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiVendorController extends Controller
{
    /**
     * Get service details by ID.
     */
    public function getService($id): JsonResponse
    {
        $service = Service::findOrFail($id);

        return response()->json([
            'id' => $service->id,
            'name' => $service->name,
            'description' => $service->description,
            'price' => $service->price,
            'duration' => $service->duration,
            'category' => $service->category,
            'is_available' => $service->is_available
        ]);
    }

    /**
     * Get venue service details for a vendor.
     */
    public function getVendorVenueService($vendorId): JsonResponse
    {
        $vendor = Vendor::with(['services' => function($query) {
            $query->where('category', 'like', '%Venue%')
                  ->orWhereHas('serviceType', function($q) {
                      $q->where('name', 'Venue');
                  });
        }])->findOrFail($vendorId);

        // Find the first service that is related to venue
        $venueService = null;
        foreach ($vendor->services as $service) {
            if (strtolower($service->category) === 'venue' ||
                ($service->serviceType && strtolower($service->serviceType->name) === 'venue')) {
                $venueService = [
                    'name' => $service->pivot->description ?: $service->name,
                    'price' => $service->pivot->price ?: $service->price,
                    'category' => $service->category,
                    'description' => $service->pivot->description ?: $service->description
                ];
                break;
            }
        }

        // If no specific venue service found, use basic vendor info
        if (!$venueService && $vendor->serviceType && $vendor->serviceType->name === 'Venue') {
            $venueService = [
                'name' => $vendor->user?->name ?: $vendor->contact_person . ' Venue',
                'price' => null,
                'category' => 'Venue',
                'description' => $vendor->serviceType->description
            ];
        }

        return response()->json([
            'service' => $venueService
        ]);
    }
}
