<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'tenant.create',
            'permission.create',
            'permission.read',
            'permission.update',
            'permission.delete',
            'role.assign',
            'role.revoke',
            'audit.log.global',
            'user.create',
            'user.read',
            'user.update',
            'user.delete',
            'role.create',
            'role.read',
            'role.update',
            'role.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create other roles and assign specific permissions (excluding SuperUser)
        // SuperUser role and permissions are handled separately in SuperUserSeeder
        $roles = [
            'Owner' => ['create_events', 'edit_events', 'delete_events', 'view_guests'],
            'Admin' => ['view_events', 'create_events', 'edit_events', 'view_guests'],
            'Staff' => ['view_events', 'view_guests'],
            'Vendor' => ['view_events'],
            'Client' => ['view_events'],
            'Guest' => [],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $rolePerms = Permission::whereIn('name', $rolePermissions)->get();
            $role->syncPermissions($rolePerms);
        }
    }
}
