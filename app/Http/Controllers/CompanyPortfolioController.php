<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyPortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portfolios = Portfolio::orderBy('order', 'asc')->orderBy('created_at', 'desc')->paginate(10);
        return view('company.portfolios.index', compact('portfolios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company.portfolios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

        $portfolio = Portfolio::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'category' => $validated['category'],
            'project_date' => $validated['project_date'],
            'client' => $validated['client_name'], // Map client_name to client
            'location' => $validated['location'],
            'status' => $validated['status'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('company-portfolio-images', 'public');
                
                // If it's the first image, also set it as main image
                if ($index === 0) {
                    $portfolio->update(['image' => $path]);
                }

                $portfolio->images()->create([
                    'image_path' => $path,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('company.portfolios.index')
            ->with('success', 'Portfolio berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Portfolio $portfolio)
    {
        return view('company.portfolios.edit', compact('portfolio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portfolio $portfolio)
    {
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
            'description' => $validated['description'] ?? '',
            'category' => $validated['category'],
            'project_date' => $validated['project_date'],
            'client' => $validated['client_name'],
            'location' => $validated['location'],
            'status' => $validated['status'],
        ]);

        if ($request->hasFile('images')) {
            $maxOrder = $portfolio->images()->max('order') ?? -1;
            
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('company-portfolio-images', 'public');
                
                // If no main image, set this one
                if (!$portfolio->image) {
                    $portfolio->update(['image' => $path]);
                }

                $portfolio->images()->create([
                    'image_path' => $path,
                    'order' => $maxOrder + 1 + $index,
                ]);
            }
        }

        return redirect()->route('company.portfolios.index')
            ->with('success', 'Portfolio berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio)
    {
        // Delete images from storage
        if ($portfolio->image && Storage::disk('public')->exists($portfolio->image)) {
             // Only delete if it's not used by images table (duplicates?)
             // Actually images table paths are distinct usually.
             // But if I saved same path to both, I should be careful.
             // I'll delete individual images first.
        }

        foreach ($portfolio->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }
        
        // Also delete main image if exists and distinct
        if ($portfolio->image && Storage::disk('public')->exists($portfolio->image)) {
             try {
                Storage::disk('public')->delete($portfolio->image);
             } catch (\Exception $e) {
                 // Ignore if already deleted
             }
        }

        $portfolio->delete();

        return redirect()->route('company.portfolios.index')
            ->with('success', 'Portfolio berhasil dihapus!');
    }

    /**
     * Remove a specific image from a portfolio.
     */
    public function toggleGalleryStatus($id)
    {
        $image = PortfolioImage::findOrFail($id);
        $image->is_featured_in_gallery = !$image->is_featured_in_gallery;
        $image->save();

        return response()->json([
            'success' => true,
            'is_featured' => $image->is_featured_in_gallery,
            'message' => $image->is_featured_in_gallery ? 'Gambar ditambahkan ke galeri.' : 'Gambar dihapus dari galeri.'
        ]);
    }

    public function destroyImage($id)
    {
        $image = PortfolioImage::findOrFail($id);
        $portfolio = $image->portfolio;

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // If this was the main "image" column, clear it or pick next
        if ($portfolio->image === $image->image_path) {
            $portfolio->update(['image' => null]);
            // Try to set next image as main
            $nextImage = $portfolio->images()->where('id', '!=', $id)->first();
            if ($nextImage) {
                 $portfolio->update(['image' => $nextImage->image_path]);
            }
        }

        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus!');
    }
}
