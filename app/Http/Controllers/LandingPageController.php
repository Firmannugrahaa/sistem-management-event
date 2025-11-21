<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Vendor;
use App\Models\Service;
use App\Models\CompanySetting;

class LandingPageController extends Controller
{
    public function index()
    {
        // Mengambil data portfolio, vendor, dan layanan
        $portfolios = Portfolio::limit(6)->get();
        $vendors = Vendor::with(['user', 'serviceType'])
            ->whereNotNull('user_id')
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', function ($roleQuery) {
                    $roleQuery->where('name', 'Vendor');
                });
            })
            ->limit(8)
            ->get();
        $services = Service::limit(6)->get();

        // Ambil data company settings
        $companySetting = CompanySetting::first();

        return view('landing-page.index', compact('portfolios', 'vendors', 'services', 'companySetting'));
    }
}
