<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
