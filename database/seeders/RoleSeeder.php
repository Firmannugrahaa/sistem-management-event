<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as SpatieRole;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superUser = User::updateOrCreate(
            ['email' => 'superuser@event.com'],
            [
                'name' => 'Super User',
                'username' => 'superuser',
                'password' => Hash::make('superuser123'),
                'role' => 'SuperUser',
            ]
        );
        // Assign the SuperUser role using Spatie Permission
        $superUser->assignRole('SuperUser');
        
        $owner = User::updateOrCreate(
            ['email' => 'owner@event.com'],
            [
                'name' => 'Owner',
                'username' => 'owner',
                'password' => Hash::make('owner123'),
                'role' => 'Owner',
                'email_verified_at' => now(),
            ]
        );
        $owner->assignRole('Owner');
        
        $admin = User::updateOrCreate(
            ['email' => 'admin@event.com'],
            [
                'name' => 'Admin Event',
                'username' => 'adminevent',
                'password' => Hash::make('admin123'),
                'role' => 'Admin',
            ]
        );
        $admin->assignRole('Admin');
        
        $staff = User::updateOrCreate(
            ['email' => 'staff@event.com'],
            [
                'name' => 'Staff',
                'username' => 'staff',
                'password' => Hash::make('staff123'),
                'role' => 'Staff',
            ]
        );
        $staff->assignRole('Staff');
        
        $vendor = User::updateOrCreate(
            ['email' => 'vendora@event.com'],
            [
                'name' => 'Vendor',
                'username' => 'vendor',
                'password' => Hash::make('vendor123'),
                'role' => 'Vendor',
            ]
        );
        $vendor->assignRole('Vendor');
        
        $client = User::updateOrCreate(
            ['email' => 'client@event.com'],
            [
                'name' => 'Client Pengantin',
                'username' => 'client',
                'password' => Hash::make('client123'),
                'role' => 'Client',
            ]
        );
        $client->assignRole('Client');
    }
}
