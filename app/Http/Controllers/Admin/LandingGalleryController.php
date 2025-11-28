<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LandingGalleryController extends Controller
{
    /**
     * Display a listing of gallery items
     */
    public function index(Request $request)
    {
        $query = LandingGallery::with(['uploader', 'vendor', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->pending();
            } elseif ($request->status === 'approved') {
                $query->approved();
            } elseif ($request->status === 'rejected') {
                $query->where('approval_status', 'rejected');
            }
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->category($request->category);
        }

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $galleries = $query->ordered()->paginate(20);

        return view('admin.landing-gallery.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new gallery item
     */
    public function create()
    {
        return view('admin.landing-gallery.create');
    }

    /**
     * Store a newly created gallery item
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:wedding,birthday,corporate,engagement,other',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {
            // Upload image
            $imagePath = $request->file('image')->store('landing-gallery', 'public');

            // Create gallery item
            $gallery = LandingGallery::create([
                'image_path' => $imagePath,
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'source' => 'admin',
                'uploaded_by' => auth()->id(),
                'is_featured' => $request->boolean('is_featured'),
                'display_order' => $request->display_order ?? 0,
                'is_active' => true,
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.landing-gallery.index')
                ->with('success', 'Foto berhasil ditambahkan ke galeri landing page!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded image if exists
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan foto: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified gallery item
     */
    public function edit(LandingGallery $landingGallery)
    {
        return view('admin.landing-gallery.edit', compact('landingGallery'));
    }

    /**
     * Update the specified gallery item
     */
    public function update(Request $request, LandingGallery $landingGallery)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:wedding,birthday,corporate,engagement,other',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active'),
                'display_order' => $request->display_order ?? $landingGallery->display_order,
            ];

            // Handle image upload if new image provided
            if ($request->hasFile('image')) {
                // Delete old image
                if ($landingGallery->image_path && Storage::disk('public')->exists($landingGallery->image_path)) {
                    Storage::disk('public')->delete($landingGallery->image_path);
                }

                // Upload new image
                $data['image_path'] = $request->file('image')->store('landing-gallery', 'public');
            }

            $landingGallery->update($data);

            DB::commit();

            return redirect()
                ->route('admin.landing-gallery.index')
                ->with('success', 'Galeri berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui galeri: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified gallery item
     */
    public function destroy(LandingGallery $landingGallery)
    {
        DB::beginTransaction();
        try {
            // Delete image file
            if ($landingGallery->image_path && Storage::disk('public')->exists($landingGallery->image_path)) {
                Storage::disk('public')->delete($landingGallery->image_path);
            }

            $landingGallery->delete();

            DB::commit();

            return redirect()
                ->route('admin.landing-gallery.index')
                ->with('success', 'Galeri berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Gagal menghapus galeri: ' . $e->getMessage());
        }
    }

    /**
     * Approve pending gallery item
     */
    public function approve(LandingGallery $landingGallery)
    {
        if ($landingGallery->approval_status !== 'pending') {
            return back()->with('error', 'Item ini bukan pending approval.');
        }

        $landingGallery->approve();

        return back()->with('success', 'Galeri berhasil diapprove!');
    }

    /**
     * Reject pending gallery item
     */
    public function reject(Request $request, LandingGallery $landingGallery)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($landingGallery->approval_status !== 'pending') {
            return back()->with('error', 'Item ini bukan pending approval.');
        }

        $landingGallery->reject($request->rejection_reason);

        return back()->with('success', 'Galeri berhasil direject!');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(LandingGallery $landingGallery)
    {
        $landingGallery->toggleFeatured();

        $status = $landingGallery->is_featured ? 'ditandai sebagai featured' : 'dihapus dari featured';

        return back()->with('success', "Galeri berhasil {$status}!");
    }

    /**
     * Toggle active status
     */
    public function toggleActive(LandingGallery $landingGallery)
    {
        $landingGallery->toggleActive();

        $status = $landingGallery->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Galeri berhasil {$status}!");
    }

    /**
     * Bulk approve
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:landing_gallery,id',
        ]);

        $count = LandingGallery::whereIn('id', $request->ids)
            ->where('approval_status', 'pending')
            ->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

        return back()->with('success', "{$count} item berhasil diapprove!");
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:landing_gallery,id',
        ]);

        DB::beginTransaction();
        try {
            $galleries = LandingGallery::whereIn('id', $request->ids)->get();

            foreach ($galleries as $gallery) {
                // Delete image file
                if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                    Storage::disk('public')->delete($gallery->image_path);
                }
                $gallery->delete();
            }

            DB::commit();

            return back()->with('success', count($galleries) . ' item berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus item: ' . $e->getMessage());
        }
    }
}
