<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AssignRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Make sure the roles exist
        $superUserRole = Role::firstOrCreate(['name' => 'SuperUser']);
        $ownerRole = Role::firstOrCreate(['name' => 'Owner']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);
        $vendorRole = Role::firstOrCreate(['name' => 'Vendor']);
        $clientRole = Role::firstOrCreate(['name' => 'Client']);
        $guestRole = Role::firstOrCreate(['name' => 'Guest']);

        // Remove any existing roles from SuperUser to ensure it's clean
        $superUser = User::where('email', 'SuperUser@event.com')->first();
        if ($superUser) {
            $superUser->syncRoles([]); // Remove all roles first
            $superUser->assignRole('SuperUser'); // Then assign SuperUser role
        }

        $owner = User::where('email', 'owner@event.com')->first();
        if ($owner) {
            $owner->syncRoles([]);
            $owner->assignRole('Owner');
        }

        $admin = User::where('email', 'Admin@event.com')->first();
        if ($admin) {
            $admin->syncRoles([]);
            $admin->assignRole('Admin');
        }

        $staff = User::where('email', 'Staff@event.com')->first();
        if ($staff) {
            $staff->syncRoles([]);
            $staff->assignRole('Staff');
        }

        $vendor = User::where('email', 'vendorA@event.com')->first();
        if ($vendor) {
            $vendor->syncRoles([]);
            $vendor->assignRole('Vendor');
        }

        $client = User::where('email', 'client@event.com')->first();
        if ($client) {
            $client->syncRoles([]);
            $client->assignRole('Client');
        }
    }
}