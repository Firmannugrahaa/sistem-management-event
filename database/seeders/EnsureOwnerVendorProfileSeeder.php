<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\ServiceType;

class EnsureOwnerVendorProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ownerEmail = 'owner@event.com';
        $owner = User::where('email', $ownerEmail)->first();

        if (!$owner) {
            $this->command->error("User with email $ownerEmail not found.");
            return;
        }

        $vendor = Vendor::where('user_id', $owner->id)->first();

        if ($vendor) {
            $this->command->info("Vendor profile for Owner already exists.");
            return;
        }

        // Ensure a service type exists
        $serviceType = ServiceType::firstOrCreate(
            ['name' => 'General Event Services'],
            ['description' => 'General services for events']
        );

        Vendor::create([
            'user_id' => $owner->id,
            'service_type_id' => $serviceType->id,
            'category' => 'Event Organizer',
            'contact_person' => $owner->name,
            'phone_number' => '08123456789',
            'address' => 'Headquarters',
            'brand_name' => 'Owner Event Services',
            'description' => 'Main event organizer services',
            'is_active' => true,
        ]);

        $this->command->info("Created Vendor profile for Owner ({$owner->name}).");
    }
}
