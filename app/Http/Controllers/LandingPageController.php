<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Service;
use App\Models\Venue;
use App\Models\CompanySetting;
use App\Models\LandingGallery;

class LandingPageController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        $user = auth()->user();

        // For authenticated clients, we might want to show additional content
        if ($user && $user->hasRole('Client')) {
            // Client-specific enhancements can go here
            $showClientDashboardAccess = true;
        } else {
            $showClientDashboardAccess = false;
        }

        // Mengambil data portfolio, venue, dan vendor
        $portfolios = Portfolio::with('images')->where('status', 'published')->orderBy('order', 'asc')->limit(6)->get();
        
        // Get Venue Vendors
        // Get Venue Vendors
        $venueVendors = Vendor::where(function($q) {
                $q->where('category', 'Venue')
                  ->orWhereHas('serviceType', function($s) {
                      $s->where('name', 'Venue');
                  });
            })
            ->with(['user', 'packages' => function($q) {
                $q->where('is_visible', true)->select('vendor_id', 'price');
            }, 'catalogItems' => function($q) {
                $q->where('status', 'available')->select('vendor_id', 'attributes', 'name'); 
            }, 'catalogItems.images', 'portfolios' => function($q) {
                $q->where('status', 'published')->select('id', 'vendor_id', 'title')->limit(3);
            }, 'portfolios.images']) 
            ->get()
            ->map(function($vendor) {
                $user = $vendor->user;
                $lowestPrice = $vendor->packages->min('price');
                
                // Get image: Catalog Item Photos -> Portfolio Photos -> Default
                $image = null;
                
                // Priority 1: Try to get image from catalog items (actual venue photos)
                if ($vendor->catalogItems->isNotEmpty()) {
                    foreach($vendor->catalogItems as $item) {
                        if ($item->images->isNotEmpty()) {
                            $firstImg = $item->images->first();
                            if ($firstImg && $firstImg->image_path && file_exists(public_path('storage/' . $firstImg->image_path))) {
                                $image = asset('storage/' . $firstImg->image_path);
                                break; // Found a valid image, stop searching
                            }
                        }
                    }
                }
                
                // Priority 2: If no catalog images, try portfolio images
                if (!$image && $vendor->portfolios->isNotEmpty()) {
                    foreach($vendor->portfolios as $portfolio) {
                        if ($portfolio->images->isNotEmpty()) {
                            $firstImage = $portfolio->images->first();
                            if ($firstImage && $firstImage->image_path && file_exists(public_path('storage/' . $firstImage->image_path))) {
                                $image = asset('storage/' . $firstImage->image_path);
                                break;
                            }
                        }
                    }
                }
                
                // Priority 3: Default placeholder
                if (!$image) {
                    $image = 'https://images.unsplash.com/photo-1519167758481-83f29da8c2bc?auto=format&fit=crop&w=600&q=80';
                }

                return [
                    'vendor_id' => $user ? $user->id : $vendor->user_id,
                    'name' => $vendor->brand_name ?? ($user ? $user->name : 'Unnamed'),
                    'owner' => $user ? $user->name : 'Unknown Owner',
                    'address' => $vendor->address ?? 'Lokasi belum diatur',
                    'image' => $image,
                    'price' => $lowestPrice,
                    'capacity' => '500-1000'
                ];
            });

        // Get main vendors for the main vendor section (first 8 vendors)
        $vendors = Vendor::with(['user', 'serviceType'])
                        ->whereNotNull('user_id')
                        ->where('is_active', true) // Only show active vendors
                        ->whereHas('user', function($query) {
                            $query->whereHas('roles', function($roleQuery) {
                                $roleQuery->where('name', 'Vendor');
                            });
                        })
                        // Prioritize vendors with complete business profiles
                        ->orderByRaw('CASE WHEN brand_name IS NOT NULL AND logo_path IS NOT NULL THEN 0 ELSE 1 END')
                        ->orderBy('created_at', 'desc')
                        ->limit(8)
                        ->get()
                        ->map(function ($vendor) {
                            // Add placeholder rating for display purposes
                            $vendor->average_rating = 4.5; // Placeholder average rating
                            $vendor->total_reviews = rand(10, 100); // Placeholder number of reviews

                            // Ensure contact information is available
                            $vendor->display_name = $vendor->brand_name ?? ($vendor->user ? $vendor->user->name : $vendor->contact_person);
                            $vendor->display_category = $vendor->serviceType ? $vendor->serviceType->name : $vendor->category;

                            return $vendor;
                        });

        // Get additional vendors for the second vendor section (remaining vendors)
        $additionalVendors = Vendor::with(['user', 'serviceType'])
                        ->whereNotNull('user_id')
                        ->where('is_active', true) // Only show active vendors
                        ->whereHas('user', function($query) {
                            $query->whereHas('roles', function($roleQuery) {
                                $roleQuery->where('name', 'Vendor');
                            });
                        })
                        ->orderByRaw('CASE WHEN brand_name IS NOT NULL AND logo_path IS NOT NULL THEN 0 ELSE 1 END')
                        ->orderBy('created_at', 'desc')
                        ->offset(8) // Skip the first 8 vendors to show different ones
                        ->limit(6)
                        ->get()
                        ->map(function ($vendor) {
                            // Add placeholder rating for display purposes
                            $vendor->average_rating = 4.5; // Placeholder average rating
                            $vendor->total_reviews = rand(10, 100); // Placeholder number of reviews

                            // Ensure contact information is available
                            $vendor->display_name = $vendor->brand_name ?? ($vendor->user ? $vendor->user->name : $vendor->contact_person);
                            $vendor->display_category = $vendor->serviceType ? $vendor->serviceType->name : $vendor->category;

                            return $vendor;
                        });

        // Ambil data company settings
        $companySetting = CompanySetting::first();

        // Get Catalog Venues (from Vendor Catalog Items)
        $catalogVenues = \App\Models\VendorCatalogItem::with(['vendor.user', 'images'])
            ->where('show_on_landing', true)
            ->where('status', 'available')
            ->whereHas('vendor', function($q) {
                $q->where('is_active', true);
                // Optional: Filter by Service Type 'Venue' if needed
                // $q->whereHas('serviceType', function($st) {
                //     $st->where('name', 'Venue');
                // });
            })
            ->latest()
            ->limit(8)
            ->get();

        // Get Event Packages (Admin Created)
        $eventPackages = \App\Models\EventPackage::with('items.vendorCatalogItem')->where('is_active', true)->latest()->get();

        // Get Gallery Items: Merge Curated Portfolio Images AND Manual Landing Gallery Items
        $curatedImages = \App\Models\PortfolioImage::where('is_featured_in_gallery', true)
            ->with('portfolio')
            ->latest()
            ->get()
            ->map(function ($image) {
                // Ensure category is compatible with frontend filters (lowercase slug)
                $catSlug = \Illuminate\Support\Str::slug($image->portfolio->category ?? 'other');
                // Map to common structure
                return (object) [
                    'image_path' => $image->image_path,
                    'title' => $image->portfolio->title ?? 'Gallery Image',
                    'category' => $catSlug, 
                    'category_label' => $image->portfolio->category ?? 'Other',
                    'is_featured' => false,
                    'created_at' => $image->created_at 
                ];
            });

        $manualGalleryItems = LandingGallery::approved()
            ->ordered()
            ->get()
            ->map(function ($item) {
                 $item->category_label = ucfirst($item->category);
                 return $item;
            });

        // Merge and sort by newest
        $galleryItems = $curatedImages->merge($manualGalleryItems)->sortByDesc('created_at');

        return view('landing-page.index', compact('portfolios', 'venueVendors', 'catalogVenues', 'vendors', 'additionalVendors', 'companySetting', 'showClientDashboardAccess', 'eventPackages', 'galleryItems'));
    }
}
