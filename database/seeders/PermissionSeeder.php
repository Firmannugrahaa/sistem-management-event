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
            // Team & Vendor Management permissions
            'team_member.read',
            'team_member.create',
            'team_member.update',
            'team_member.delete',
            'team_member.approve',
            'vendor.read',
            'vendor.create',
            'vendor.update',
            'vendor.delete',
            'vendor.approve',
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

        // Assign specific permissions to Admin role for team/vendor management
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminPermissions = [
            'team_member.read',
            'team_member.create',
            'team_member.update',
            'team_member.delete',
            'team_member.approve',
            'vendor.read',
            'vendor.create',
            'vendor.update',
            'vendor.delete',
            'vendor.approve',
        ];
        $adminRole->givePermissionTo($adminPermissions);

        // Assign SuperUser role if it doesn't exist
        $superUserRole = Role::firstOrCreate(['name' => 'SuperUser']);
        $superUserRole->givePermissionTo(Permission::all());
    }
}
