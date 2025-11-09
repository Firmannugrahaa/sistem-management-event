<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates SuperUser role with global permissions and assigns all existing
     * permissions to SuperUser. SuperUser will bypass all permission checks
     * through the CheckSuperUser middleware.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create global permissions for SuperUser
        $permissions = [
            'manage_tenants',
            'manage_global_permissions',
            'manage_roles',
            'assign_permissions',
            'view_audit_logs'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create SuperUser role
        $superUserRole = Role::firstOrCreate(['name' => 'SuperUser']);

        // Also create any additional permissions that might exist in the system
        $additionalPermissions = [
            'view_events', 'create_events', 'edit_events', 'delete_events',
            'view_guests', 'create_guests', 'edit_guests', 'delete_guests',
            'view_vendors', 'create_vendors', 'edit_vendors', 'delete_vendors',
            'view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices',
            'view_payments', 'create_payments', 'edit_payments', 'delete_payments',
            'view_venues', 'create_venues', 'edit_venues', 'delete_venues',
            'view_vouchers', 'create_vouchers', 'edit_vouchers', 'delete_vouchers',
            'user.create', 'user.read', 'user.update', 'user.delete',
            'role.create', 'role.read', 'role.update', 'role.delete',
        ];

        foreach ($additionalPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create other standard roles if they don't exist
        $standardRoles = ['Owner', 'Admin', 'Staff', 'Vendor', 'Client', 'Guest'];
        foreach ($standardRoles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Assign ALL existing permissions to SuperUser (dynamic, not hardcoded)
        $allPermissions = Permission::all();
        $superUserRole->syncPermissions($allPermissions);
    }
}