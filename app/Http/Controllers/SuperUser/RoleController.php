<?php

namespace App\Http\Controllers\SuperUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('superuser.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('superuser.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('superuser.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        if ($role->name === 'SuperUser') {
            return redirect()->route('superuser.roles.index')->with('error', 'Cannot edit SuperUser role.');
        }

        $permissions = Permission::all();
        return view('superuser.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'SuperUser') {
            return redirect()->route('superuser.roles.index')->with('error', 'Cannot edit SuperUser role.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('superuser.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'SuperUser') {
            return redirect()->route('superuser.roles.index')->with('error', 'Cannot delete SuperUser role.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('superuser.roles.index')->with('error', 'Cannot delete role that is assigned to users.');
        }

        $role->delete();

        return redirect()->route('superuser.roles.index')->with('success', 'Role deleted successfully.');
    }
}
