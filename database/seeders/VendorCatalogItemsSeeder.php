<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\VendorCatalogItem;
use App\Models\VendorCatalogImage;
use Illuminate\Database\Seeder;

class VendorCatalogItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all vendors and create catalog items for each
        $vendors = Vendor::whereNotNull('category')->get();
        
        $catalogData = [
            'Venue' => [
                ['name' => 'Ballroom Crystal', 'description' => 'Ruangan ballroom mewah dengan kapasitas 500 orang', 'price' => 50000000],
                ['name' => 'Garden Wedding Venue', 'description' => 'Area outdoor dengan taman yang indah', 'price' => 35000000],
                ['name' => 'Rooftop Venue', 'description' => 'Venue rooftop dengan pemandangan kota', 'price' => 45000000],
            ],
            'Catering' => [
                ['name' => 'Paket Prasmanan 100 Pax', 'description' => 'Menu prasmanan lengkap untuk 100 tamu', 'price' => 15000000],
                ['name' => 'Paket Prasmanan 200 Pax', 'description' => 'Menu prasmanan lengkap untuk 200 tamu', 'price' => 28000000],
                ['name' => 'Paket VIP Dinner 50 Pax', 'description' => 'Menu fine dining eksklusif', 'price' => 25000000],
                ['name' => 'Paket Snack Box 100 Pcs', 'description' => 'Snack box untuk coffee break', 'price' => 5000000],
            ],
            'Photography' => [
                ['name' => 'Paket Foto Wedding', 'description' => 'Dokumentasi foto wedding full day', 'price' => 15000000],
                ['name' => 'Paket Prewedding', 'description' => 'Sesi foto prewedding indoor/outdoor', 'price' => 8000000],
                ['name' => 'Paket Foto + Video', 'description' => 'Kombinasi foto dan video cinematic', 'price' => 25000000],
            ],
            'Fotografi & Videografi' => [
                ['name' => 'Paket Dokumentasi Lengkap', 'description' => 'Foto dan video full coverage', 'price' => 30000000],
                ['name' => 'Paket Video Cinematic', 'description' => 'Video cinematic highlight 5 menit', 'price' => 12000000],
                ['name' => 'Paket Drone Footage', 'description' => 'Pengambilan gambar aerial dengan drone', 'price' => 5000000],
            ],
            'Decoration' => [
                ['name' => 'Dekorasi Pelaminan', 'description' => 'Dekorasi panggung pelaminan lengkap', 'price' => 20000000],
                ['name' => 'Dekorasi Meja Tamu', 'description' => 'Rangkaian bunga untuk meja tamu', 'price' => 8000000],
                ['name' => 'Dekorasi Photo Booth', 'description' => 'Setup area photo booth', 'price' => 5000000],
            ],
            'Entertainment' => [
                ['name' => 'Band Akustik', 'description' => 'Live music akustik 3 jam', 'price' => 8000000],
                ['name' => 'DJ Performance', 'description' => 'DJ profesional dengan equipment', 'price' => 10000000],
                ['name' => 'MC Wedding', 'description' => 'Master of Ceremony profesional', 'price' => 5000000],
            ],
            'Sound System' => [
                ['name' => 'Sound System Standard', 'description' => 'Paket sound untuk 200 tamu', 'price' => 5000000],
                ['name' => 'Sound System Premium', 'description' => 'Paket sound untuk 500 tamu', 'price' => 12000000],
                ['name' => 'Lighting Package', 'description' => 'Pencahayaan dekoratif dan panggung', 'price' => 8000000],
            ],
            'MUA' => [
                ['name' => 'Makeup Pengantin', 'description' => 'Makeup dan hairdo untuk pengantin', 'price' => 8000000],
                ['name' => 'Makeup Akad + Resepsi', 'description' => 'Retouch makeup untuk 2 sesi', 'price' => 12000000],
                ['name' => 'Makeup Bridesmaid', 'description' => 'Makeup untuk pendamping pengantin', 'price' => 2000000],
            ],
        ];

        foreach ($vendors as $vendor) {
            $category = $vendor->category;
            
            // Get catalog items for this category, or use generic if not found
            $items = $catalogData[$category] ?? $this->getGenericItems($category);
            
            foreach ($items as $itemData) {
                // Check if already exists
                $exists = VendorCatalogItem::where('vendor_id', $vendor->id)
                    ->where('name', $itemData['name'])
                    ->exists();
                
                if (!$exists) {
                    VendorCatalogItem::create([
                        'vendor_id' => $vendor->id,
                        'name' => $itemData['name'],
                        'description' => $itemData['description'],
                        'price' => $itemData['price'],
                        'status' => 'available',
                    ]);
                    
                    $this->command->info("Created catalog item: {$itemData['name']} for vendor: {$vendor->brand_name}");
                }
            }
        }
    }
    
    private function getGenericItems(string $category): array
    {
        return [
            ['name' => "Paket {$category} Basic", 'description' => "Layanan {$category} paket dasar", 'price' => 5000000],
            ['name' => "Paket {$category} Premium", 'description' => "Layanan {$category} paket premium", 'price' => 15000000],
            ['name' => "Paket {$category} VIP", 'description' => "Layanan {$category} paket VIP eksklusif", 'price' => 30000000],
        ];
    }
}
