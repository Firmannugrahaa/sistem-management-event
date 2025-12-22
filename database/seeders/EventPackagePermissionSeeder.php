<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class EventPackagePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create new permission
        Permission::firstOrCreate(['name' => 'manage_event_packages']);
        $this->command->info("Permission 'manage_event_packages' created.");

        // Assign permission to Owner and Admin roles
        $roles = ['Owner', 'Admin'];

        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo('manage_event_packages');
                $this->command->info("Assigned 'manage_event_packages' to role: $roleName");
            } else {
                $this->command->error("Role not found: $roleName");
            }
        }
    }
}
