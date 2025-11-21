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
        $layout = $request->query('layout', 'grid'); // Default to 'grid'

        // Determine the master owner ID for the team/company
        $teamOwnerId = auth()->user()->owner_id ?? auth()->id();

        $teamMembers = null;
        $vendors = null;

        // Initialize all variables to prevent undefined variable errors
        $allRoles = collect();
        $allCategories = collect();
        $allServiceTypes = collect();

        if ($view === 'team') {
            $query = User::where('owner_id', $teamOwnerId)
                ->whereHas('roles', function ($q) {
                    $q->where('name', '!=', 'Vendor');
                })
                ->with('roles');

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%")
                        ->orWhere('username', 'like', "%{$searchTerm}%");
                });
            }

            // Apply role filter
            if ($request->has('role') && !empty($request->role)) {
                $roleId = $request->role;
                $query->whereHas('roles', function ($q) use ($roleId) {
                    $q->where('role_id', $roleId);
                });
            }

            $teamMembers = $query->paginate(10)->appends($request->query());

            // Get all distinct roles for the role filter
            $allRoles = User::where('owner_id', $teamOwnerId)
                ->with('roles')
                ->get()
                ->flatMap->roles
                ->unique('id')
                ->values();
            $teamMembers = User::where('owner_id', auth()->id())
                ->whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'Vendor');
                })
                ->with('roles')
                ->paginate(10);
        } elseif ($view === 'vendor') {
            $query = Vendor::whereHas('user', function ($q) use ($teamOwnerId) {
                $q->where('owner_id', $teamOwnerId);
            })->with('user', 'serviceType');

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('category', 'like', "%{$searchTerm}%")
                        ->orWhere('contact_person', 'like', "%{$searchTerm}%")
                        ->orWhere('phone_number', 'like', "%{$searchTerm}%")
                        ->orWhereHas('user', function ($uq) use ($searchTerm) {
                            $uq->where('name', 'like', "%{$searchTerm}%");
                        })
                        ->orWhereHas('serviceType', function ($stq) use ($searchTerm) {
                            $stq->where('name', 'like', "%{$searchTerm}%");
                        });
                });
            }

            // Apply category filter
            if ($request->has('category') && !empty($request->category)) {
                $category = $request->category;
                $query->where('category', $category);
            }

            // Apply service type filter
            if ($request->has('service_type') && !empty($request->service_type)) {
                $serviceTypeId = $request->service_type;
                $query->where('service_type_id', $serviceTypeId);
            }

            $vendors = $query->paginate(10)->appends($request->query());

            // Get all distinct categories for the category filter
            $allCategories = Vendor::whereHas('user', function ($q) use ($teamOwnerId) {
                $q->where('owner_id', $teamOwnerId);
            })->distinct()->pluck('category');

            // Get all service types for the service type filter
            $allServiceTypes = \App\Models\ServiceType::all();
        }

        return view('team-vendor.index', compact('teamMembers', 'vendors', 'view', 'allRoles', 'allCategories', 'allServiceTypes', 'layout'));
    }
}
