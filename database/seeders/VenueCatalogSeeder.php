<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\VendorCatalogItem;
use App\Models\VendorCatalogImage;
use Illuminate\Support\Facades\File;

class VenueCatalogSeeder extends Seeder
{
    public function run(): void
    {
        // Get all venue vendors
        $venueVendors = Vendor::where(function($q) {
            $q->where('category', 'Venue')
              ->orWhereHas('serviceType', fn($s) => $s->where('name', 'Venue'));
        })->get();

        if ($venueVendors->isEmpty()) {
            $this->command->info('No venue vendors found. Skipping venue catalog seeding.');
            return;
        }

        foreach ($venueVendors as $vendor) {
            // Create catalog items for each venue
            $catalogItem = VendorCatalogItem::create([
                'vendor_id' => $vendor->id,
                'name' => $vendor->brand_name . ' - Main Hall',
                'description' => 'Ruangan utama dengan kapasitas besar dan fasilitas lengkap',
                'price' => rand(5000000, 15000000),
                'status' => 'available',
                'quantity' => 1,
                'show_stock' => false,
                'show_on_landing' => true,
            ]);

            // Create sample images using Unsplash placeholders
            // For now, we'll create image records that point to online URLs
            // In production, you'd download and store these locally
            $sampleImages = [
                'https://images.unsplash.com/photo-1519167758481-83f29da8c2bc?w=800',
                'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=800',
                'https://images.unsplash.com/photo-1478146896981-b80fe463b330?w=800',
            ];

            foreach ($sampleImages as $index => $imageUrl) {
                // For demo purposes, we'll use the URL directly
                // In production, download the image and store it properly
                VendorCatalogImage::create([
                    'catalog_item_id' => $catalogItem->id,
                    'image_path' => 'venues/sample_' . $vendor->id . '_' . ($index + 1) . '.jpg',
                    'order' => $index + 1,
                ]);
            }

            $this->command->info("Created catalog item and images for vendor: {$vendor->brand_name}");
        }

        $this->command->info('Venue catalog seeding completed!');
    }
}
