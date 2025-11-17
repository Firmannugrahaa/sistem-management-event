<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SuperUserPermissionController extends Controller
{
    public function index()
    {
        // Get all roles except SuperUser (to prevent tampering with SuperUser permissions)
        $roles = Role::where('name', '!=', 'SuperUser')->get();
        $permissions = Permission::all();
        
        // Get current role-permission mappings
        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role->name] = $role->permissions->pluck('id')->toArray();
        }

        return view('superuser.permissions', compact('roles', 'permissions', 'rolePermissions'));
    }
    
    public function update(Request $request)
    {
        DB::transaction(function () use ($request) {
            $roles = Role::where('name', '!=', 'SuperUser')->get();
            
            foreach ($roles as $role) {
                $permissionIds = $request->get($role->name, []);
                $permissions = Permission::whereIn('id', $permissionIds)->get();
                $role->syncPermissions($permissions);
            }
        });
        
        return redirect()->route('superuser.permissions.index')->with('status', 'Permissions updated successfully!');
    }
    
    public function debug()
    {
        $user = auth()->user();
        $roles = $user->roles->pluck('name');
        $permissions = $user->permissions->pluck('name');
        
        return response()->json([
            'user' => $user->name,
            'email' => $user->email,
            'roles' => $roles,
            'permissions' => $permissions,
            'has_super_role' => $user->hasRole('SuperUser'),
            'can_manage_tenants' => $user->can('manage_tenants'),
        ]);
    }
}