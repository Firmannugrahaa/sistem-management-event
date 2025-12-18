<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChecklistTemplate;
use App\Models\ChecklistTemplateItem;

class ChecklistTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Wedding Template
        $weddingTemplate = ChecklistTemplate::create([
            'event_type' => 'Wedding',
            'name' => 'Wedding Planner Checklist',
        ]);

        // Default wedding checklist items with timeline data
        $items = [
            // 1. Administrasi & Legal (6 months before - CRITICAL)
            ['category' => 'Administrasi & Legal', 'title' => 'Menyiapkan KTP CPP & CPW', 'order' => 1, 'days_before_event' => 180, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Administrasi & Legal', 'title' => 'Kartu Keluarga', 'order' => 2, 'days_before_event' => 180, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Administrasi & Legal', 'title' => 'Surat Pengantar RT/RW', 'order' => 3, 'days_before_event' => 150, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Administrasi & Legal', 'title' => 'Formulir N1, N2, N3, N4', 'order' => 4, 'days_before_event' => 120, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Administrasi & Legal', 'title' => 'Pendaftaran KUA', 'order' => 5, 'days_before_event' => 90, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Administrasi & Legal', 'title' => 'Penentuan wali nikah', 'order' => 6, 'days_before_event' => 120, 'priority' => 'IMPORTANT', 'is_flexible' => false],
            ['category' => 'Administrasi & Legal', 'title' => 'Jadwal akad nikah', 'order' => 7, 'days_before_event' => 120, 'priority' => 'CRITICAL', 'is_flexible' => false],

            // 2. Venue & Acara (6 months before - CRITICAL)
            ['category' => 'Venue & Acara', 'title' => 'Booking venue akad', 'order' => 8, 'days_before_event' => 180, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Venue & Acara', 'title' => 'Booking venue resepsi', 'order' => 9, 'days_before_event' => 180, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Venue & Acara', 'title' => 'Layout tempat duduk', 'order' => 10, 'days_before_event' => 45, 'priority' => 'IMPORTANT', 'is_flexible' => true],
            ['category' => 'Venue & Acara', 'title' => 'Rundown acara', 'order' => 11, 'days_before_event' => 30, 'priority' => 'IMPORTANT', 'is_flexible' => false],
            ['category' => 'Venue & Acara', 'title' => 'Penentuan MC & susunan acara', 'order' => 12, 'days_before_event' => 60, 'priority' => 'IMPORTANT', 'is_flexible' => true],

            // 3. Vendor & Layanan (3-6 months before - CRITICAL/IMPORTANT)
            ['category' => 'Vendor & Layanan', 'title' => 'Catering utama', 'order' => 13, 'days_before_event' => 120, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Vendor & Layanan', 'title' => 'Tes menu', 'order' => 14, 'days_before_event' => 45, 'priority' => 'IMPORTANT', 'is_flexible' => true],
            ['category' => 'Vendor & Layanan', 'title' => 'Dekorasi', 'order' => 15, 'days_before_event' => 90, 'priority' => 'IMPORTANT', 'is_flexible' => false],
            ['category' => 'Vendor & Layanan', 'title' => 'MUA pengantin', 'order' => 16, 'days_before_event' => 90, 'priority' => 'IMPORTANT', 'is_flexible' => false],
            ['category' => 'Vendor & Layanan', 'title' => 'Fotografer & videografer', 'order' => 17, 'days_before_event' => 120, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Vendor & Layanan', 'title' => 'Entertainment (band / wedding singer)', 'order' => 18, 'days_before_event' => 60, 'priority' => 'NICE_TO_HAVE', 'is_flexible' => true],

            // 4. Undangan & Tamu (2-3 months before - IMPORTANT)
            ['category' => 'Undangan & Tamu', 'title' => 'Desain undangan', 'order' => 19, 'days_before_event' => 75, 'priority' => 'IMPORTANT', 'is_flexible' => false],
            ['category' => 'Undangan & Tamu', 'title' => 'Cetak undangan', 'order' => 20, 'days_before_event' => 60, 'priority' => 'IMPORTANT', 'is_flexible' => false],
            ['category' => 'Undangan & Tamu', 'title' => 'Undangan digital', 'order' => 21, 'days_before_event' => 45, 'priority' => 'IMPORTANT', 'is_flexible' => true],
            ['category' => 'Undangan & Tamu', 'title' => 'Penyusunan daftar tamu', 'order' => 22, 'days_before_event' => 90, 'priority' => 'IMPORTANT', 'is_flexible' => false],
            ['category' => 'Undangan & Tamu', 'title' => 'Konfirmasi kehadiran', 'order' => 23, 'days_before_event' => 14, 'priority' => 'CRITICAL', 'is_flexible' => false],

            // 5. Souvenir & Perlengkapan (1-2 months before - NICE_TO_HAVE)
            ['category' => 'Souvenir & Perlengkapan', 'title' => 'Pilih souvenir', 'order' => 24, 'days_before_event' => 60, 'priority' => 'NICE_TO_HAVE', 'is_flexible' => true],
            ['category' => 'Souvenir & Perlengkapan', 'title' => 'Pesan souvenir', 'order' => 25, 'days_before_event' => 45, 'priority' => 'NICE_TO_HAVE', 'is_flexible' => true],
            ['category' => 'Souvenir & Perlengkapan', 'title' => 'Packaging souvenir', 'order' => 26, 'days_before_event' => 14, 'priority' => 'NICE_TO_HAVE', 'is_flexible' => true],
            ['category' => 'Souvenir & Perlengkapan', 'title' => 'Distribusi souvenir', 'order' => 27, 'days_before_event' => 7, 'priority' => 'NICE_TO_HAVE', 'is_flexible' => true],

            // 6. Busana & Penampilan (1-2 months before - IMPORTANT)
            ['category' => 'Busana & Penampilan', 'title' => 'Baju akad', 'order' => 28, 'days_before_event' => 60, 'priority' => 'IMPORTANT', 'is_flexible' => false],
            ['category' => 'Busana & Penampilan', 'title' => 'Baju resepsi', 'order' => 29, 'days_before_event' => 60, 'priority' => 'IMPORTANT', 'is_flexible' => false],
            ['category' => 'Busana & Penampilan', 'title' => 'Aksesoris', 'order' => 30, 'days_before_event' => 30, 'priority' => 'NICE_TO_HAVE', 'is_flexible' => true],
            ['category' => 'Busana & Penampilan', 'title' => 'Fitting baju', 'order' => 31, 'days_before_event' => 21, 'priority' => 'IMPORTANT', 'is_flexible' => false],

            // 7. Teknis Hari-H (1-2 weeks before - CRITICAL)
            ['category' => 'Teknis Hari-H', 'title' => 'Gladi bersih', 'order' => 32, 'days_before_event' => 7, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Teknis Hari-H', 'title' => 'Briefing vendor', 'order' => 33, 'days_before_event' => 3, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Teknis Hari-H', 'title' => 'Koordinasi keluarga', 'order' => 34, 'days_before_event' => 2, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Teknis Hari-H', 'title' => 'Checklist H-1', 'order' => 35, 'days_before_event' => 1, 'priority' => 'CRITICAL', 'is_flexible' => false],
            ['category' => 'Teknis Hari-H', 'title' => 'Checklist Hari-H', 'order' => 36, 'days_before_event' => 0, 'priority' => 'CRITICAL', 'is_flexible' => false],
        ];

        foreach ($items as $item) {
            ChecklistTemplateItem::create([
                'template_id' => $weddingTemplate->id,
                'category' => $item['category'],
                'title' => $item['title'],
                'order' => $item['order'],
                'days_before_event' => $item['days_before_event'],
                'priority' => $item['priority'],
                'is_flexible' => $item['is_flexible'],
            ]);
        }

        $this->command->info('Wedding checklist template created with ' . count($items) . ' items (with timeline data).');
    }
}
