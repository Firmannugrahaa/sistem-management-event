<?php

namespace Database\Seeders;

use App\Models\EventPackage;
use Illuminate\Database\Seeder;

class EventPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket Wedding Intimate',
                'slug' => 'paket-wedding-intimate',
                'description' => 'Paket pernikahan lengkap untuk acara intimate dengan keluarga dan kerabat terdekat. Includes venue, catering, decoration, documentation, dan entertainment.',
                'base_price' => 30000000, // Total harga asli items
                'discount_percentage' => 15, // Discount bundling 15%
                'markup_percentage' => 0,
                'final_price' => 25500000, // Will be auto-calculated
                'duration' => 'Full Day (10 Jam)',
                'image_url' => 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&h=800&q=80',
                'features' => [
                    'Venue Indoor AC (Rp 10.000.000)',
                    'Catering 100 Pax (Rp 12.000.000)',
                    'Dekorasi Pelaminan Minimalis (Rp 4.000.000)',
                    'Dokumentasi Foto & Video (Rp 3.000.000)',
                    'Makeup Artist Pengantin + Orang Tua (Rp 2.000.000)',
                    'MC & Entertainment Acoustic (Rp 1.500.000)',
                ],
                'thumbnail_path' => null,
                'is_active' => true,
                'is_featured' => true, // Best Value package
                'pricing_method' => 'hybrid',
            ],
            [
                'name' => 'Paket Corporate Gathering Premium',
                'slug' => 'paket-corporate-gathering',
                'description' => 'Solusi lengkap untuk acara gathering perusahaan, seminar, atau launching produk dengan fasilitas premium.',
                'base_price' => 15000000,
                'discount_percentage' => 10, // Discount 10%
                'markup_percentage' => 0,
                'final_price' => 13500000,
                'duration' => '6 Jam',
                'image_url' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&h=800&q=80',
                'features' => [
                    'Venue Meeting Room (Rp 5.000.000)',
                    'Catering Buffet 50 Pax (Rp 6.000.000)',
                    'Coffee Break 2x (Rp 1.500.000)',
                    'Sound System & Projector (Rp 1.500.000)',
                    'MC Professional (Rp 1.000.000)',
                    'Dokumentasi Foto (Rp 1.500.000)',
                ],
                'thumbnail_path' => null,
                'is_active' => true,
                'is_featured' => false,
                'pricing_method' => 'hybrid',
            ],
            [
                'name' => 'Paket Birthday Party Sweet 17',
                'slug' => 'paket-birthday-party-sweet-17',
                'description' => 'Rayakan momen spesial sweet 17 dengan pesta yang meriah dan tak terlupakan!',
                'base_price' => 10000000,
                'discount_percentage' => 12, // Discount 12%
                'markup_percentage' => 0,
                'final_price' => 8800000,
                'duration' => '4 Jam',
                'image_url' => 'https://images.unsplash.com/photo-1527529482837-4698179dc6ce?auto=format&fit=crop&w=1200&h=800&q=80',
                'features' => [
                    'Venue Indoor (Rp 3.000.000)',
                    'Catering 50 Pax (Rp 3.500.000)',
                    'Dekorasi Balon & Backdrop (Rp 1.500.000)',
                    'Kue Ulang Tahun 2 Tier (Rp 800.000)',
                    'MC & DJ Performance (Rp 1.200.000)',
                    'Photo Booth Unlimited (Rp 1.000.000)',
                ],
                'thumbnail_path' => null,
                'is_active' => true,
                'is_featured' => true,
                'pricing_method' => 'hybrid',
            ],
            [
                'name' => 'Paket Engagement Rustic',
                'slug' => 'paket-engagement-rustic',
                'description' => 'Paket lamaran dengan tema rustic yang hangat dan romantis. Perfect untuk momen spesial Anda.',
                'base_price' => 8000000,
                'discount_percentage' => 0, // No discount
                'markup_percentage' => 5, // Markup 5%
                'final_price' => 8400000,
                'duration' => '4 Jam',
                'image_url' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=1200&h=800&q=80',
                'features' => [
                    'Venue Semi-Outdoor (Rp 2.500.000)',
                    'Catering 30 Pax (Rp 2.700.000)',
                    'Dekorasi Rustic Backdrop (Rp 1.500.000)',
                    'Dokumentasi Foto (Rp 1.000.000)',
                    'Makeup Artist (Rp 800.000)',
                    'Sound System Portable (Rp 500.000)',
                ],
                'thumbnail_path' => null,
                'is_active' => true,
                'is_featured' => false,
                'pricing_method' => 'hybrid',
            ],
            [
                'name' => 'Paket Grand Wedding Luxury',
                'slug' => 'paket-grand-wedding-luxury',
                'description' => 'Paket pernikahan mewah untuk acara besar dengan fasilitas premium dan layanan eksklusif.',
                'base_price' => 75000000,
                'discount_percentage' => 20, // Huge discount 20%
                'markup_percentage' => 0,
                'final_price' => 60000000,
                'duration' => 'Full Day + Rehearsal',
                'image_url' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=1200&h=800&q=80',
                'features' => [
                    'Ballroom Venue (Rp 25.000.000)',
                    'Fine Dining Catering 300 Pax (Rp 30.000.000)',
                    'Premium Decoration (Rp 10.000.000)',
                    'Cinematic Documentation (Rp 8.000.000)',
                    'International Makeup Artist (Rp 5.000.000)',
                    'Live Band Performance (Rp 4.000.000)',
                    'Wedding Organizer (Rp 3.000.000)',
                ],
                'thumbnail_path' => null,
                'is_active' => true,
                'is_featured' => true,
                'pricing_method' => 'hybrid',
            ],
        ];

        foreach ($packages as $pkg) {
            EventPackage::create($pkg);
        }
    }
}
