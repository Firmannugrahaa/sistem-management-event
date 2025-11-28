<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorPortfolio;
use App\Models\VendorPortfolioImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorPortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user->hasRole('Vendor')) {
            abort(403);
        }

        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        $portfolios = $vendor->portfolios()->orderBy('created_at', 'desc')->paginate(10);

        return view('vendor.portfolio.index', compact('portfolios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vendor.portfolio.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'project_date' => 'nullable|date',
            'client_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB per image
        ]);

        $portfolio = $vendor->portfolios()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'project_date' => $validated['project_date'],
            'client_name' => $validated['client_name'],
            'location' => $validated['location'],
            'status' => $validated['status'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('portfolio-images', 'public');
                $portfolio->images()->create([
                    'image_path' => $path,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('vendor.portfolios.index')
            ->with('success', 'Portfolio berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        $portfolio = $vendor->portfolios()->with('images')->findOrFail($id);

        return view('vendor.portfolio.edit', compact('portfolio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        $portfolio = $vendor->portfolios()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'project_date' => 'nullable|date',
            'client_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $portfolio->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'project_date' => $validated['project_date'],
            'client_name' => $validated['client_name'],
            'location' => $validated['location'],
            'status' => $validated['status'],
        ]);

        if ($request->hasFile('images')) {
            // Get current max order
            $maxOrder = $portfolio->images()->max('order') ?? -1;
            
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('portfolio-images', 'public');
                $portfolio->images()->create([
                    'image_path' => $path,
                    'order' => $maxOrder + 1 + $index,
                ]);
            }
        }

        return redirect()->route('vendor.portfolios.index')
            ->with('success', 'Portfolio berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        $portfolio = $vendor->portfolios()->findOrFail($id);

        // Delete images from storage
        foreach ($portfolio->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $portfolio->delete();

        return redirect()->route('vendor.portfolios.index')
            ->with('success', 'Portfolio berhasil dihapus!');
    }

    /**
     * Remove a specific image from a portfolio.
     */
    public function destroyImage($id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        
        // Ensure image belongs to a portfolio owned by the vendor
        $image = VendorPortfolioImage::whereHas('portfolio', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->findOrFail($id);

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus!');
    }
}
