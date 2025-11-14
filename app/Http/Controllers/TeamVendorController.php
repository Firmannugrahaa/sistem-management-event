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
        $view = $request->query('view', 'team');
        $search = $request->query('search');
        $sortBy = $request->query('sortBy', 'created_at');
        $sortDirection = $request->query('sortDirection', 'desc');

        $teamMembers = null;
        $vendors = null;

        if ($view === 'team') {
            $teamMembersQuery = User::where('owner_id', auth()->id())->with('roles');

            if ($search) {
                $teamMembersQuery->where('name', 'like', '%' . $search . '%');
            }

            $teamMembers = $teamMembersQuery->orderBy($sortBy, $sortDirection)->paginate(10);
        } elseif ($view === 'vendor') {
            $vendorsQuery = Vendor::whereHas('user', function($query) {
                $query->where('owner_id', auth()->id());
            })->with('user', 'serviceType');

            if ($search) {
                $vendorsQuery->whereHas('user', function($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            }

            if ($sortBy === 'business_name') {
                $vendorsQuery->join('users', 'vendors.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sortDirection)
                    ->select('vendors.*');
            } else {
                $vendorsQuery->orderBy($sortBy, $sortDirection);
            }

            $vendors = $vendorsQuery->paginate(10);
        }
        
        return view('team-vendor.index', compact('teamMembers', 'vendors', 'view', 'search', 'sortBy', 'sortDirection'));
    }
}
