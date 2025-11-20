<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamVendorController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->query('view', 'team'); // Default to 'team'

        $teamMembers = null;
        $vendors = null;

        if ($view === 'team') {
            $teamMembers = User::where('owner_id', auth()->id())
                             ->whereDoesntHave('roles', function ($query) {
                                 $query->where('name', 'Vendor');
                             })
                             ->with('roles')
                             ->paginate(10);
        } elseif ($view === 'vendor') {
            $vendors = Vendor::whereHas('user', function($query) {
                $query->where('owner_id', auth()->id());
            })->with('user', 'serviceType')->paginate(10);
        }
        
        return view('team-vendor.index', compact('teamMembers', 'vendors', 'view'));
    }
}
