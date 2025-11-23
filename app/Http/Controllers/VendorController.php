<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
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
        $vendors = Vendor::latest()->paginate(10);
        return view('vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Vendor::class);
        return view('vendors.create');
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

        return redirect()->route('vendors.index')->with('success', 'Vendor berhasil ditambahkan.');
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
        return view('vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $this->authorize('update', $vendor);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:vendors,user_id,' . $vendor->id, // Allow same vendor to keep its user_id
            'service_type_id' => 'required|exists:service_types,id',
            'category' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $vendor->update($validated);

        return redirect()->route('vendors.index')->with('success', 'Vendor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $this->authorize('delete', $vendor);
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor berhasil dihapus.');
    }
}
