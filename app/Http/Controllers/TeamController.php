<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class TeamController extends Controller
{
    /**
     * Display a listing of the user's team members.
     */
    public function index()
    {
        $teamMembers = User::where('owner_id', auth()->id())->with('roles')->paginate(10);
        return view('team.index', compact('teamMembers'));
    }

    /**
     * Show the form for creating a new team member.
     */
    public function create()
    {
        // Only allow owners to assign 'Admin' or 'Staff' roles
        $roles = Role::whereIn('name', ['Admin', 'Staff'])->get();
        return view('team.create', compact('roles'));
    }

    /**
     * Store a newly created team member in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $newUser = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'owner_id' => auth()->id(), // Set the owner
        ]);

        $newUser->assignRole($request->role);

        return redirect()->route('team.index')->with('success', 'Team member added successfully.');
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function createVendor()
    {
        $serviceTypes = ServiceType::all();
        return view('team.create-vendor', compact('serviceTypes'));
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function storeVendor(Request $request)
    {
        $request->merge([
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
        ]);

        $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'service_type_id' => ['required', 'exists:service_types,id'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:12'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Generate temporary password from username + current year
        $currentYear = date('Y');
        $temporaryPassword = $request->username . $currentYear;
        
        $vendorUser = User::create([
            'name' => $request->business_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($temporaryPassword),
            'owner_id' => auth()->id(),
            'must_change_password' => true,
        ]);

        $vendorUser->assignRole('Vendor');

        // Get the service type name to use as category
        $serviceType = ServiceType::findOrFail($request->service_type_id);
        
        Vendor::create([
            'user_id' => $vendorUser->id,
            'service_type_id' => $request->service_type_id,
            'category' => $serviceType->name, // Use service type name as category
            'contact_person' => $request->contact_person,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return redirect()->route('team.index')->with('success', 'Vendor added successfully!');
    }

    /**
     * Show the form for editing the specified team member.
     */
    public function edit(User $member)
    {
        // Authorization: Make sure the user being edited belongs to the authenticated user's team
        if ($member->owner_id !== auth()->id()) {
            abort(403, 'UNAUTHORIZED_ACTION');
        }
        
        $roles = \Spatie\Permission\Models\Role::whereIn('name', ['Admin', 'Staff'])->get();
        return view('team.edit', compact('member', 'roles'));
    }

    /**
     * Update the specified team member in storage.
     */
    public function update(Request $request, User $member)
    {
        // Authorization: Make sure the user being updated belongs to the authenticated user's team
        if ($member->owner_id !== auth()->id()) {
            abort(403, 'UNAUTHORIZED_ACTION');
        }
        
        $request->merge([
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 
                Rule::unique('users', 'username')->ignore($member->id)],
            'email' => ['required', 'string', 'email', 'max:255', 
                Rule::unique('users', 'email')->ignore($member->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $member->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $member->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Sync the user's role
        $member->roles()->sync([]);
        $member->assignRole($request->role);

        return redirect()->route('team.index')->with('success', 'Team member updated successfully.');
    }

    /**
     * Remove the specified team member from storage.
     */
    public function destroy(User $member)
    {
        // Authorization: Make sure the user being deleted belongs to the authenticated user's team
        if ($member->owner_id !== auth()->id()) {
            abort(403, 'UNAUTHORIZED_ACTION');
        }

        $member->delete();

        return redirect()->route('team.index')->with('success', 'Team member removed successfully.');
    }
}

