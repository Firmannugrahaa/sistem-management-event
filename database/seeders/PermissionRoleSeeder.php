<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['Spatie\Permission\PermissionRegistrar']->forgetCachedPermissions();

        // Get or create the roles and permissions to ensure they exist
        $superUserRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'SuperUser']);
        $ownerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Owner']);

        // Get or create the access-settings permission
        $accessSettingsPermission = \Spatie\Permission\Models\Permission::firstOrCreate(
            ['name' => 'access-settings'],
            ['guard_name' => 'web']
        );

        // Assign the access-settings permission to Super User and Owner roles
        if ($superUserRole && $accessSettingsPermission) {
            $superUserRole->givePermissionTo($accessSettingsPermission);
        }

        if ($ownerRole && $accessSettingsPermission) {
            $ownerRole->givePermissionTo($accessSettingsPermission);
        }
    }
}
