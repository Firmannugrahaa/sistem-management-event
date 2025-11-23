<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $venues = Venue::latest()->paginate(10);
        return view('venues.index', compact('venues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Venue::class);

        return view('venues.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Venue::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        Venue::create($validated);

        return redirect()->route('venues.index')->with('success', 'Venue berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venue $venue)
    {
        // For guests (not logged in), redirect to login
        if (!auth()->check()) {
            session(['intended_url' => request()->url()]); // Store intended URL
            return redirect()->route('login')->with('message', 'Silakan login terlebih dahulu untuk melihat detail venue.');
        }

        // For non-Client users, redirect to profile edit
        $user = auth()->user();
        if (!$user->hasRole('Client')) {
            return redirect()->route('profile.edit')->with('error', 'Hanya user dengan role Client yang dapat mengakses detail venue untuk booking.');
        }

        // For Client users, show venue details page
        return view('venues.show', compact('venue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venue $venue)
    {
        $this->authorize('update', $venue);

        return view('venues.edit', compact('venue'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venue $venue)
    {
        $this->authorize('update', $venue);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $venue->update($validated);

        return redirect()->route('venues.index')->with('success', 'Venue berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venue $venue)
    {
        $this->authorize('delete', $venue);

        $venue->delete();

        return redirect()->route('venues.index')->with('success', 'Venue berhasil dihapus');
    }
}
