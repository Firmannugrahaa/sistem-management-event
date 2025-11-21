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
        $teamMembers = User::where('owner_id', auth()->id())
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Vendor');
            })
            ->with('roles')
            ->paginate(10);
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

        $creator = auth()->user();
        $canCreateWithoutApproval = $creator->can('user.auto_approve_on_create');

        // If creator is not a SuperUser, new user belongs to the creator's company.
        // If creator is a SuperUser, they are creating a new Owner, so owner_id will be set later.
        $ownerIdForNewUser = !$creator->hasRole('SuperUser') ? ($creator->owner_id ?? $creator->id) : null;

        $newUser = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'owner_id' => $ownerIdForNewUser,
            'status' => $canCreateWithoutApproval ? 'approved' : 'pending',
            'approved_by' => $canCreateWithoutApproval ? $creator->id : null,
            'approved_at' => $canCreateWithoutApproval ? now() : null,
        ]);

        // If a SuperUser created this user, establish them as the owner of their own new 'company'.
        if ($creator->hasRole('SuperUser') && $newUser->owner_id === null) {
            $newUser->owner_id = $newUser->id;
            $newUser->save();
        }

        $newUser->assignRole($request->role);

        return redirect()->route('team-vendor.index', ['view' => 'team'])->with('success', 'Team member added successfully.');
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

        $creator = auth()->user();
        $canCreateWithoutApproval = $creator->can('vendor.auto_approve_on_create');

        // If creator is not a SuperUser, new user belongs to the creator's company.
        $ownerIdForNewUser = !$creator->hasRole('SuperUser') ? ($creator->owner_id ?? $creator->id) : null;

        $vendorUser = User::create([
            'name' => $request->business_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($temporaryPassword),
            'owner_id' => $ownerIdForNewUser,
            'must_change_password' => true,
            'status' => $canCreateWithoutApproval ? 'approved' : 'pending',
            'approved_by' => $canCreateWithoutApproval ? $creator->id : null,
            'approved_at' => $canCreateWithoutApproval ? now() : null,
        ]);

        // If a SuperUser created this user, establish them as the owner of their own new 'company'.
        if ($creator->hasRole('SuperUser') && $vendorUser->owner_id === null) {
            $vendorUser->owner_id = $vendorUser->id;
            $vendorUser->save();
        }

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

        return redirect()->route('team-vendor.index', ['view' => 'vendor'])->with('success', 'Vendor added successfully!');
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
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($member->id)
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($member->id)
            ],
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

        return redirect()->route('team-vendor.index', ['view' => 'team'])->with('success', 'Team member updated successfully.');
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

        return redirect()->route('team-vendor.index', ['view' => 'team'])->with('success', 'Team member removed successfully.');
    }

    /**
     * Approve a pending team member.
     */
    public function approveUser(User $member)
    {
        $member->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('team-vendor.index', ['view' => 'team'])->with('success', 'Team member approved successfully.');
    }

    /**
     * Reject and delete a pending team member.
     */
    public function rejectUser(User $member)
    {
        // Ensure we are only rejecting a pending user
        if ($member->status !== 'pending') {
            return redirect()->route('team-vendor.index', ['view' => 'team'])->with('error', 'This user is not pending approval.');
        }
        $memberName = $member->name;
        $member->delete();

        return redirect()->route('team-vendor.index', ['view' => 'team'])->with('success', "Pending team member '{$memberName}' has been rejected and removed.");
    }

    /**
     * Approve a pending vendor.
     */
    public function approveVendor(User $user)
    {
        $user->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('team-vendor.index', ['view' => 'vendor'])->with('success', 'Vendor approved successfully.');
    }

    /**
     * Reject and delete a pending vendor.
     */
    public function rejectVendor(User $user)
    {
        // Ensure we are only rejecting a pending user
        if ($user->status !== 'pending') {
            return redirect()->route('team-vendor.index', ['view' => 'vendor'])->with('error', 'This vendor is not pending approval.');
        }
        $vendorName = $user->name;
        $user->delete();

        return redirect()->route('team-vendor.index', ['view' => 'vendor'])->with('success', "Pending vendor '{$vendorName}' has been rejected and removed.");
    }
}
