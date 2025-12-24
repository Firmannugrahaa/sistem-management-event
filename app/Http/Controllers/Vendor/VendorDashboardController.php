<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorPackage;
use App\Models\VendorProduct;
use App\Models\VendorCatalogItem;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    /**
     * Display the vendor dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // If user doesn't have a vendor profile, redirect to create one
        if (!$vendor) {
            return redirect()->route('vendor.business-profile.edit')
                ->with('warning', 'Silakan lengkapi profil bisnis Anda terlebih dahulu.');
        }

        // Load vendor with relationships
        $vendor->load(['serviceType', 'user']);

        // ============ SUMMARY STATS ============
        
        // Packages
        $totalPackages = VendorPackage::where('vendor_id', $vendor->id)->count();
        $activePackages = VendorPackage::where('vendor_id', $vendor->id)->where('is_visible', true)->count();
        
        // Services (Products) - VendorProduct table doesn't have is_active column
        $totalServices = VendorProduct::where('vendor_id', $vendor->id)->count();
        $activeServices = $totalServices; // All products are considered active
        
        // Catalog Items (Products)
        $totalCatalogItems = VendorCatalogItem::where('vendor_id', $vendor->id)->count();
        $availableCatalogItems = VendorCatalogItem::where('vendor_id', $vendor->id)->where('status', 'available')->count();
        
        // Events Assigned
        $assignedEvents = $vendor->events()->with(['user', 'venue'])->get();
        $upcomingEvents = $assignedEvents->where('start_time', '>', now())->sortBy('start_time');
        $completedEvents = $assignedEvents->where('start_time', '<=', now())->count();
        
        // Revenue (from payments on assigned events)
        $totalRevenue = Payment::whereHas('invoice.event.vendors', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->sum('amount');
        
        // ============ RECENT ACTIVITY ============
        
        // Recent Notifications
        $recentNotifications = $user->notifications()
            ->latest()
            ->limit(5)
            ->get();
        
        // Unread Notifications Count
        $unreadNotificationsCount = $user->unreadNotifications()->count();
        
        // ============ PACKAGES WITH DETAILS ============
        
        $packages = VendorPackage::where('vendor_id', $vendor->id)
            ->with(['services', 'items.pivot'])
            ->latest()
            ->limit(5)
            ->get();
        
        // ============ QUICK STATS FOR CARDS ============
        
        $stats = [
            'packages' => [
                'total' => $totalPackages,
                'active' => $activePackages,
                'icon' => '📦',
                'label' => 'Paket Layanan',
                'route' => route('vendor.packages.index'),
            ],
            'services' => [
                'total' => $totalServices,
                'active' => $activeServices,
                'icon' => '🛠️',
                'label' => 'Layanan',
                'route' => route('vendor.products.index'),
            ],
            'catalog' => [
                'total' => $totalCatalogItems,
                'active' => $availableCatalogItems,
                'icon' => '📋',
                'label' => 'Produk Katalog',
                'route' => route('vendor.catalog.items.index'),
            ],
            'events' => [
                'total' => $assignedEvents->count(),
                'upcoming' => $upcomingEvents->count(),
                'icon' => '📅',
                'label' => 'Event Ditugaskan',
                'route' => route('vendor.events.index'),
            ],
        ];
        
        // ============ ONBOARDING STATUS ============
        
        $onboardingSteps = [
            [
                'title' => 'Lengkapi Profil Bisnis',
                'description' => 'Isi nama brand, logo, dan informasi kontak',
                'completed' => !empty($vendor->brand_name) && !empty($vendor->logo_path),
                'route' => route('vendor.business-profile.edit'),
                'icon' => '🏪',
            ],
            [
                'title' => 'Tambah Layanan',
                'description' => 'Buat layanan jasa yang Anda tawarkan',
                'completed' => $totalServices > 0,
                'route' => route('vendor.products.create'),
                'icon' => '🛠️',
            ],
            [
                'title' => 'Tambah Produk Katalog',
                'description' => 'Masukkan produk/inventaris yang tersedia',
                'completed' => $totalCatalogItems > 0,
                'route' => route('vendor.catalog.items.create'),
                'icon' => '📋',
            ],
            [
                'title' => 'Buat Paket Layanan',
                'description' => 'Bundel layanan dan produk menjadi paket menarik',
                'completed' => $totalPackages > 0,
                'route' => route('vendor.packages.create'),
                'icon' => '📦',
            ],
            [
                'title' => 'Upload Portfolio',
                'description' => 'Tampilkan hasil kerja terbaik Anda',
                'completed' => $vendor->portfolios()->count() > 0,
                'route' => route('vendor.portfolios.create'),
                'icon' => '🖼️',
            ],
        ];
        
        $completedSteps = collect($onboardingSteps)->where('completed', true)->count();
        $onboardingProgress = ($completedSteps / count($onboardingSteps)) * 100;
        $isNewVendor = $onboardingProgress < 100;
        
        // ============ VENDOR CATEGORY SPECIFIC ============
        
        $vendorCategory = strtolower($vendor->serviceType->name ?? $vendor->category ?? '');
        $isCatering = str_contains($vendorCategory, 'catering');
        
        return view('vendor.dashboard.index', compact(
            'vendor',
            'stats',
            'packages',
            'upcomingEvents',
            'completedEvents',
            'totalRevenue',
            'recentNotifications',
            'unreadNotificationsCount',
            'onboardingSteps',
            'onboardingProgress',
            'isNewVendor',
            'vendorCategory',
            'isCatering'
        ));
    }
}
