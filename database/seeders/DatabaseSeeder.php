<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SuperUserSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            AssignRolesSeeder::class,
            ProvincesSeeder::class,
            CitiesSeeder::class,
            DistrictsSeeder::class,
            VillagesSeeder::class,
            ServiceTypeSeeder::class,
            PortfolioSeeder::class,
            ServiceSeeder::class,
            VendorProfileSeeder::class,
        ]);
    }
}
