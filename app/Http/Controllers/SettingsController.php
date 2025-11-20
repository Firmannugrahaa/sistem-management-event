<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index()
    {
        // Authorization is handled by gate policy in routes and navigation
        $settings = CompanySetting::first(); // Get the first (and likely only) settings record

        // If no settings exist, create default ones
        if (!$settings) {
            $settings = CompanySetting::create([
                'company_name' => 'Nama Perusahaan',
                'company_phone' => 'Nomor telepon tidak tersedia',
                'company_email' => 'Email tidak tersedia',
                'company_address' => 'Alamat tidak tersedia',
                'tax_number' => 'NPWP 00.000.000.0-000.000',
                'bank_account_name' => 'Nama Rekening Perusahaan',
                'bank_account_number' => '1234567890',
                'bank_name' => 'Bank Central Asia (BCA)',
            ]);
        }

        // Get provinces for the dropdown - only select necessary fields to improve performance
        $provinces = Province::orderBy('name')->select('id', 'name')->get();
        
        // Get related cities, districts, and villages if settings exist - only select necessary fields
        $cities = collect();
        $districts = collect();
        $villages = collect();
        
        if ($settings && $settings->province_id) {
            $cities = City::where('province_id', $settings->province_id)->orderBy('name')->select('id', 'name')->get();
        }
        
        if ($settings && $settings->city_id) {
            $districts = District::where('city_id', $settings->city_id)->orderBy('name')->select('id', 'name')->get();
        }
        
        if ($settings && $settings->district_id) {
            $villages = Village::where('district_id', $settings->district_id)->orderBy('name')->select('id', 'name')->get();
        }

        return view('settings.index', compact('settings', 'provinces', 'cities', 'districts', 'villages'));
    }

    /**
     * Update the settings in storage.
     */
    public function update(Request $request)
    {
        // Authorization is handled by gate policy in routes
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_phone' => 'required|string|max:20',
            'company_email' => 'required|email|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'province_id' => 'nullable|exists:provinces,id',
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'village_id' => 'nullable|exists:villages,id',
            'postal_code' => 'nullable|string|max:10',
            'street_name' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'company_number' => 'nullable|string|max:50',
            'address_details' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
        ]);

        $settings = CompanySetting::first();
        
        // Generate full address from structured fields if company_address is empty
        $addressParts = [];
        
        if ($request->filled('street_name')) {
            $addressParts[] = $request->street_name;
        }
        if ($request->filled('building')) {
            $addressParts[] = $request->building;
        }
        if ($request->filled('company_number')) {
            $addressParts[] = 'No. ' . $request->company_number;
        }
        if ($request->filled('address_details')) {
            $addressParts[] = $request->address_details;
        }
        
        // Add location information - select only the name field to improve performance
        $village = $request->village_id ? \App\Models\Village::select('name')->find($request->village_id) : null;
        $district = $request->district_id ? \App\Models\District::select('name')->find($request->district_id) : null;
        $city = $request->city_id ? \App\Models\City::select('name')->find($request->city_id) : null;
        $province = $request->province_id ? \App\Models\Province::select('name')->find($request->province_id) : null;
        
        if ($village) {
            $addressParts[] = $village->name;
        }
        if ($district) {
            $addressParts[] = $district->name;
        }
        if ($city) {
            $addressParts[] = $city->name;
        }
        if ($province) {
            $addressParts[] = $province->name;
        }
        if ($request->filled('postal_code')) {
            $addressParts[] = 'Kode Pos: ' . $request->postal_code;
        }
        
        // Generate the structured address
        $generatedAddress = implode(', ', $addressParts);
        
        // Handle company logo upload
        if ($request->hasFile('company_logo_path')) {
            // Delete old logo if exists
            if ($settings && $settings->company_logo_path) {
                $oldLogoPath = storage_path('app/public/' . $settings->company_logo_path);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
            }

            // Store new logo
            $logoPath = $request->file('company_logo_path')->store('logos', 'public');
            $request->merge(['company_logo_path' => $logoPath]);
        }

        // Prioritize manual company address, if not provided then generate from structured fields
        if (empty($request->company_address) && !empty($generatedAddress)) {
            // Only generate address if user hasn't provided a manual address
            $request->merge(['company_address' => $generatedAddress]);
        }

        if ($settings) {
            $settings->update($request->only([
                'company_name',
                'company_phone',
                'company_email',
                'company_address',
                'company_logo_path',
                'province_id',
                'city_id',
                'district_id',
                'village_id',
                'postal_code',
                'street_name',
                'building',
                'company_number',
                'address_details',
                'tax_number',
                'bank_account_name',
                'bank_account_number',
                'bank_name'
            ]));
        } else {
            CompanySetting::create($request->only([
                'company_name',
                'company_phone',
                'company_email',
                'company_address',
                'company_logo_path',
                'province_id',
                'city_id',
                'district_id',
                'village_id',
                'postal_code',
                'street_name',
                'building',
                'company_number',
                'address_details',
                'tax_number',
                'bank_account_name',
                'bank_account_number',
                'bank_name'
            ]));
        }

        return redirect()->route('superuser.settings.index')->with('success', 'Pengaturan perusahaan berhasil diperbarui.');
    }
}