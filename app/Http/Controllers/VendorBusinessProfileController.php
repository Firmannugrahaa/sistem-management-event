<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VendorBusinessProfileController extends Controller
{
    /**
     * Display the vendor's business profile form.
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Check if user has Vendor, Owner, or Admin role
        if (!$user->hasRole(['Vendor', 'Owner', 'Admin'])) {
            abort(403, 'Unauthorized access');
        }

        // For Admin/Staff, get the Owner's vendor profile
        $userId = $user->id;
        if ($user->hasRole(['Admin', 'Staff']) && $user->owner_id) {
            $userId = $user->owner_id;
        }

        // Get or create vendor profile
        $vendor = Vendor::firstOrCreate(
            ['user_id' => $userId],
            [
                'contact_person' => $user->name,
                'phone_number' => $user->phone ?? '',
                'is_active' => true,
            ]
        );

        $serviceTypes = ServiceType::all();

        return view('vendor.business-profile.edit', compact('vendor', 'serviceTypes'));
    }

    /**
     * Update the vendor's business profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->hasRole(['Vendor', 'Owner', 'Admin'])) {
            abort(403, 'Unauthorized access');
        }

        // For Admin/Staff, update the Owner's vendor profile
        $userId = $user->id;
        if ($user->hasRole(['Admin', 'Staff']) && $user->owner_id) {
            $userId = $user->owner_id;
        }

        $vendor = Vendor::where('user_id', $userId)->firstOrFail();

        $validated = $request->validate([
            'brand_name' => 'nullable|string|max:255',
            'service_type_id' => 'nullable|exists:service_types,id',
            'description' => 'required|string|max:1000',
            'contact_person' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            
            // Operating hours
            'operating_hours.monday.open' => 'nullable|string',
            'operating_hours.monday.close' => 'nullable|string',
            'operating_hours.monday.is_closed' => 'nullable|boolean',
            'operating_hours.tuesday.open' => 'nullable|string',
            'operating_hours.tuesday.close' => 'nullable|string',
            'operating_hours.tuesday.is_closed' => 'nullable|boolean',
            'operating_hours.wednesday.open' => 'nullable|string',
            'operating_hours.wednesday.close' => 'nullable|string',
            'operating_hours.wednesday.is_closed' => 'nullable|boolean',
            'operating_hours.thursday.open' => 'nullable|string',
            'operating_hours.thursday.close' => 'nullable|string',
            'operating_hours.thursday.is_closed' => 'nullable|boolean',
            'operating_hours.friday.open' => 'nullable|string',
            'operating_hours.friday.close' => 'nullable|string',
            'operating_hours.friday.is_closed' => 'nullable|boolean',
            'operating_hours.saturday.open' => 'nullable|string',
            'operating_hours.saturday.close' => 'nullable|string',
            'operating_hours.saturday.is_closed' => 'nullable|boolean',
            'operating_hours.sunday.open' => 'nullable|string',
            'operating_hours.sunday.close' => 'nullable|string',
            'operating_hours.sunday.is_closed' => 'nullable|boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($vendor->logo_path && Storage::disk('public')->exists($vendor->logo_path)) {
                Storage::disk('public')->delete($vendor->logo_path);
            }

           $logoPath = $request->file('logo')->store('vendor-logos', 'public');
            $validated['logo_path'] = $logoPath;
        }

        // Process operating hours
        if ($request->has('operating_hours')) {
            $validated['operating_hours'] = $request->input('operating_hours');
        }

        // Sanitize social media usernames and URLs
        // Instagram: remove @ and any whitespace
        if (!empty($validated['instagram'])) {
            $validated['instagram'] = trim(ltrim($validated['instagram'], '@'));
        }
        
        // TikTok: remove @ and any whitespace
        if (!empty($validated['tiktok'])) {
            $validated['tiktok'] = trim(ltrim($validated['tiktok'], '@'));
        }
        
        // Facebook: ensure it's a valid URL, if not prepend https://
        if (!empty($validated['facebook'])) {
            $facebook = trim($validated['facebook']);
            // If it doesn't start with http:// or https://, add https://
            if (!preg_match('/^https?:\/\//i', $facebook)) {
                $facebook = 'https://' . $facebook;
            }
            $validated['facebook'] = $facebook;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['show_stock_on_profile'] = $request->has('show_stock_on_profile') ? true : false;

        $vendor->update($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Profil bisnis berhasil diperbarui! Selamat datang di dashboard Anda.');
    }

    /**
     * Display the vendor's public profile.
     */
    public function show($id)
    {
        $vendor = Vendor::with([
            'user', 
            'serviceType', 
            'publishedPortfolios.images', 
            'products', 
            'packages.services',
            'catalogCategories.items.images',
            'catalogItems.category',
            'catalogItems.images'
        ])->findOrFail($id);

        if (!$vendor->is_active) {
            abort(404);
        }

        // Get testimonials from completed events (if columns exist)
        try {
            $testimonials = \App\Models\Event::where('events.user_id', $vendor->user_id)
                ->whereHas('vendors', function($q) use ($vendor) {
                    $q->where('vendors.id', $vendor->id);
                })
                ->where('status', 'completed')
                ->whereNotNull('client_feedback')
                ->where('client_feedback', '!=', '')
                ->with(['user'])
                ->latest()
                ->take(10)
                ->get()
                ->map(function($event) {
                    return [
                        'client_name' => $event->user->name ?? $event->client_name,
                        'event_name' => $event->name,
                        'event_date' => $event->event_date,
                        'feedback' => $event->client_feedback,
                        'rating' => $event->client_rating ?? 5,
                    ];
                });
        } catch (\Exception $e) {
            // If column doesn't exist, return empty collection
            $testimonials = collect([]);
        }

        // Get catalog items (for vendors that have them)
        $catalogItems = $vendor->catalogItems()
            ->with(['images', 'category'])
            ->where('status', 'available')
            ->latest()
            ->get();

        return view('vendor.business-profile.show', compact('vendor', 'testimonials', 'catalogItems'));
    }
}
