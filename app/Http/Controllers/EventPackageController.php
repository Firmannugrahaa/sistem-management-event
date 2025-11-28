<?php

namespace App\Http\Controllers;

use App\Models\EventPackage;
use App\Models\EventPackageItem;
use App\Models\VendorProduct;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventPackageController extends Controller
{
    /**
     * Display a listing of event packages (Admin/Owner only)
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user->can('manage_event_packages') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403, 'Unauthorized action.');
        }

        $packages = EventPackage::with('items.vendorProduct.vendor')
            ->latest()
            ->paginate(10);

        return view('event-packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new event package
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user->can('manage_event_packages') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        // Get all vendor products from all vendors
        $vendorProducts = VendorProduct::with('vendor')->get();

        return view('event-packages.create', compact('vendorProducts'));
    }

    /**
     * Store a newly created event package
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->can('manage_event_packages') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'markup_percentage' => 'nullable|numeric|min:0|max:100',
            'duration' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'pricing_method' => 'required|in:manual,auto,hybrid',
            'features' => 'nullable|array',
            'items' => 'nullable|array',
            'items.*.vendor_product_id' => 'required|exists:vendor_products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $thumbnailPath = null;
            $imageUrl = $validated['image_url'] ?? null;
            
            if ($request->hasFile('image')) {
                $thumbnailPath = $request->file('image')->store('event-packages', 'public');
            }

            // Calculate final price
            $basePrice = $validated['base_price'];
            $discountPct = $validated['discount_percentage'] ?? 0;
            $markupPct = $validated['markup_percentage'] ?? 0;
            
            $discount = ($basePrice * $discountPct) / 100;
            $markup = ($basePrice * $markupPct) / 100;
            $finalPrice = $basePrice - $discount + $markup;

            $package = EventPackage::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'],
                'base_price' => $basePrice,
                'discount_percentage' => $discountPct,
                'markup_percentage' => $markupPct,
                'final_price' => $finalPrice,
                'duration' => $validated['duration'] ?? null,
                'thumbnail_path' => $thumbnailPath,
                'image_url' => $imageUrl,
                'features' => $validated['features'] ?? [],
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
                'pricing_method' => $validated['pricing_method'],
                'created_by' => $user->id,
            ]);

            // Add items if provided
            if (isset($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    $vendorProduct = VendorProduct::find($item['vendor_product_id']);
                    
                    EventPackageItem::create([
                        'event_package_id' => $package->id,
                        'vendor_product_id' => $item['vendor_product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $vendorProduct->price ?? 0,
                        'total_price' => ($vendorProduct->price ?? 0) * $item['quantity'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('event-packages.index')
                ->with('success', 'Event package created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Failed to create event package: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create package: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified event package (public view)
     */
    public function show($slug)
    {
        $package = EventPackage::where('slug', $slug)
            ->where('is_active', true)
            ->with('items.vendorProduct.vendor')
            ->firstOrFail();
        
        $relatedPackages = EventPackage::where('id', '!=', $package->id)
            ->where('is_active', true)
            ->limit(3)
            ->get();

        return view('event-packages.show', compact('package', 'relatedPackages'));
    }

    /**
     * Show the form for editing the specified event package
     */
    public function edit(EventPackage $eventPackage)
    {
        $user = Auth::user();
        if (!$user->can('manage_event_packages') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $eventPackage->load('items.vendorProduct.vendor');
        $vendorProducts = VendorProduct::with('vendor')->get();

        return view('event-packages.edit', compact('eventPackage', 'vendorProducts'));
    }

    /**
     * Update the specified event package
     */
    public function update(Request $request, EventPackage $eventPackage)
    {
        $user = Auth::user();
        if (!$user->can('manage_event_packages') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'markup_percentage' => 'nullable|numeric|min:0|max:100',
            'duration' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
            'is_featured' => 'nullable|boolean',
            'pricing_method' => 'required|in:manual,auto,hybrid',
            'features' => 'nullable|array',
            'items' => 'nullable|array',
            'items.*.vendor_product_id' => 'required|exists:vendor_products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Calculate final price
            $basePrice = $validated['base_price'];
            $discountPct = $validated['discount_percentage'] ?? 0;
            $markupPct = $validated['markup_percentage'] ?? 0;
            
            $discount = ($basePrice * $discountPct) / 100;
            $markup = ($basePrice * $markupPct) / 100;
            $finalPrice = $basePrice - $discount + $markup;
            
            $data = [
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'],
                'base_price' => $basePrice,
                'discount_percentage' => $discountPct,
                'markup_percentage' => $markupPct,
                'final_price' => $finalPrice,
                'duration' => $validated['duration'] ?? null,
                'features' => $validated['features'] ?? [],
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
                'pricing_method' => $validated['pricing_method'],
            ];
            
            // Handle image_url
            if (isset($validated['image_url'])) {
                $data['image_url'] = $validated['image_url'];
            }

            // Handle thumbnail upload
            if ($request->hasFile('image')) {
                // Delete old thumbnail
                if ($eventPackage->thumbnail_path) {
                    Storage::disk('public')->delete($eventPackage->thumbnail_path);
                }
                $data['thumbnail_path'] = $request->file('image')->store('event-packages', 'public');
            }

            $eventPackage->update($data);

            // Sync items: delete old ones and create new ones
            if (isset($validated['items'])) {
                $eventPackage->items()->delete();

                foreach ($validated['items'] as $item) {
                    $vendorProduct = VendorProduct::find($item['vendor_product_id']);
                    
                    EventPackageItem::create([
                        'event_package_id' => $eventPackage->id,
                        'vendor_product_id' => $item['vendor_product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $vendorProduct->price ?? 0,
                        'total_price' => ($vendorProduct->price ?? 0) * $item['quantity'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('event-packages.index')
                ->with('success', 'Event package updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update package: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified event package
     */
    public function destroy(EventPackage $eventPackage)
    {
        $user = Auth::user();
        if (!$user->can('manage_event_packages') && !$user->hasRole(['Owner', 'Admin', 'SuperUser'])) {
            abort(403);
        }

        try {
            $eventPackage->delete();
            return redirect()->route('event-packages.index')
                ->with('success', 'Event package deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete package: ' . $e->getMessage());
        }
    }
}
