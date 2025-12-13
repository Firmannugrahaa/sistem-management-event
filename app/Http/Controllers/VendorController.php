<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\ServiceType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('team-vendor.index', ['view' => 'vendor']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('team.vendors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Vendor::class);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:vendors,user_id', // Ensure user_id is provided and unique
            'service_type_id' => 'required|exists:service_types,id',
            'category' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        Vendor::create($validated);

        return redirect()->route('team-vendor.index', ['view' => 'vendor'])->with('success', 'Vendor berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        // For guests (not logged in), redirect to login
        if (!auth()->check()) {
            session(['intended_url' => request()->url()]); // Store intended URL
            return redirect()->route('login')->with('message', 'Silakan login terlebih dahulu untuk melihat detail vendor.');
        }

        // For non-Client users, redirect to profile edit
        $user = auth()->user();
        if (!$user->hasRole('Client')) {
            return redirect()->route('profile.edit')->with('error', 'Hanya user dengan role Client yang dapat mengakses detail vendor untuk booking.');
        }

        // In real application, this would come from reviews/ratings system
        $vendorRatings = [
            ['user' => 'Budi Santoso', 'rating' => 5, 'comment' => 'Layanan sangat profesional dan berkualitas tinggi. Akan menggunakan jasa mereka lagi di masa depan.', 'date' => '2024-11-15'],
            ['user' => 'Siti Nurhaliza', 'rating' => 4, 'comment' => 'Pekerjaan bagus dan tepat waktu, hanya sedikit masalah komunikasi awal.', 'date' => '2024-10-22'],
            ['user' => 'Ahmad Fauzi', 'rating' => 5, 'comment' => 'Sangat puas dengan layanan yang diberikan. Kualitas produk luar biasa!', 'date' => '2024-09-30'],
        ];

        $averageRating = collect($vendorRatings)->avg('rating');
        $totalReviews = count($vendorRatings);

        return view('vendors.show', compact('vendor', 'vendorRatings', 'averageRating', 'totalReviews'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        $this->authorize('update', $vendor);
        $serviceTypes = ServiceType::all();
        return view('vendors.edit', compact('vendor', 'serviceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $this->authorize('update', $vendor);
        
        $user = $vendor->user;

        $request->merge([
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
        ]);

        $validated = $request->validate([
            // User fields
            'business_name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],

            // Vendor fields
            'service_type_id' => 'required|exists:service_types,id',
            'contact_person' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        // Update User
        $user->update([
            'name' => $request->business_name,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        // Update Vendor
        $serviceType = ServiceType::find($request->service_type_id);
        
        $vendor->update([
            'service_type_id' => $request->service_type_id,
            'category' => $serviceType->name,
            'contact_person' => $request->contact_person,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return redirect()->route('team-vendor.index', ['view' => 'vendor'])->with('success', 'Vendor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $this->authorize('delete', $vendor);
        $vendor->delete();
        return redirect()->route('team-vendor.index', ['view' => 'vendor'])->with('success', 'Vendor berhasil dihapus.');
    }
}
