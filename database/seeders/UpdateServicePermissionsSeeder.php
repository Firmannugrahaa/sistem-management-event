<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UpdateServicePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create new permissions
        $permissions = [
            'manage_services',
            'manage_packages',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->command->info("Permission $permission ensured.");
        }

        // Assign permissions to roles
        $roles = [
            'Owner' => ['manage_services', 'manage_packages'],
            'Admin' => ['manage_services', 'manage_packages'],
            'Vendor' => ['manage_services', 'manage_packages'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($rolePermissions);
                $this->command->info("Assigned permissions to role: $roleName");
            } else {
                $this->command->error("Role not found: $roleName");
            }
        }
    }
}
