<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientLandingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all available venues
        $venues = Venue::all();
        
        // Get all available vendors with their user info
        $vendors = Vendor::with('user', 'serviceType')->get();
        
        return view('client.landing', compact(
            'venues',
            'vendors'
        ));
    }
}