<?php

namespace App\Http\Controllers\SuperUser;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('superuser.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('superuser.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|alpha_dash|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('superuser.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('superuser.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->merge([
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|exists:roles,name'
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Sync roles
        $user->syncRoles([$request->role]);

        return redirect()->route('superuser.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('SuperUser') && $user->id === auth()->id()) {
            return redirect()->route('superuser.users.index')->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('superuser.users.index')->with('success', 'User deleted successfully.');
    }
}