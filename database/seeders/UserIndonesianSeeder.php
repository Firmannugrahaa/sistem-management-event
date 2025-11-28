<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserIndonesianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Owner user to assign as the owner of these team members
        $owner = User::where('email', 'owner@event.com')->first();
        
        if (!$owner) {
            $this->command->info('Owner user not found. Creating default owner first.');
            $owner = User::create([
                'name' => 'Owner',
                'username' => 'owner',
                'email' => 'owner@event.com',
                'password' => Hash::make('owner123'),
                'role' => 'Owner',
            ]);
            $owner->assignRole('Owner');
        }

        // 2 Admins
        $admins = [
            [
                'name' => 'Budi Santoso',
                'username' => 'budisantoso',
                'email' => 'admin.budi@event.com',
                'password' => Hash::make('password123'),
                'role' => 'Admin',
                'owner_id' => $owner->id,
                'status' => 'approved',
                'approved_by' => $owner->id,
                'approved_at' => now(),
            ],
            [
                'name' => 'Siti Aminah',
                'username' => 'sitiaminah',
                'email' => 'admin.siti@event.com',
                'password' => Hash::make('password123'),
                'role' => 'Admin',
                'owner_id' => $owner->id,
                'status' => 'approved',
                'approved_by' => $owner->id,
                'approved_at' => now(),
            ],
        ];

        foreach ($admins as $adminData) {
            $user = User::updateOrCreate(
                ['email' => $adminData['email']],
                $adminData
            );
            $user->assignRole('Admin');
        }

        // 5 Staff
        $staffs = [
            [
                'name' => 'Agus Pratama',
                'username' => 'aguspratama',
                'email' => 'staff.agus@event.com',
                'password' => Hash::make('password123'),
                'role' => 'Staff',
                'owner_id' => $owner->id,
                'status' => 'approved',
                'approved_by' => $owner->id,
                'approved_at' => now(),
            ],
            [
                'name' => 'Dewi Lestari',
                'username' => 'dewilestari',
                'email' => 'staff.dewi@event.com',
                'password' => Hash::make('password123'),
                'role' => 'Staff',
                'owner_id' => $owner->id,
                'status' => 'approved',
                'approved_by' => $owner->id,
                'approved_at' => now(),
            ],
            [
                'name' => 'Eko Kurniawan',
                'username' => 'ekokurniawan',
                'email' => 'staff.eko@event.com',
                'password' => Hash::make('password123'),
                'role' => 'Staff',
                'owner_id' => $owner->id,
                'status' => 'approved',
                'approved_by' => $owner->id,
                'approved_at' => now(),
            ],
            [
                'name' => 'Rina Wulandari',
                'username' => 'rinawulandari',
                'email' => 'staff.rina@event.com',
                'password' => Hash::make('password123'),
                'role' => 'Staff',
                'owner_id' => $owner->id,
                'status' => 'approved',
                'approved_by' => $owner->id,
                'approved_at' => now(),
            ],
            [
                'name' => 'Joko Susilo',
                'username' => 'jokosusilo',
                'email' => 'staff.joko@event.com',
                'password' => Hash::make('password123'),
                'role' => 'Staff',
                'owner_id' => $owner->id,
                'status' => 'approved',
                'approved_by' => $owner->id,
                'approved_at' => now(),
            ],
        ];

        foreach ($staffs as $staffData) {
            $user = User::updateOrCreate(
                ['email' => $staffData['email']],
                $staffData
            );
            $user->assignRole('Staff');
        }
    }
}
