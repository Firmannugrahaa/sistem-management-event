<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Portfolio;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Portfolio::create([
            'title' => 'Wedding Outdoor di Taman',
            'description' => 'Pernikahan outdoor yang romantis dengan dekorasi bunga segar dan pemandangan alam yang indah. Acara ini menampilkan berbagai elemen alami yang menciptakan suasana yang hangat dan tak terlupakan.',
            'image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80',
            'client' => 'Budi & Siti',
            'project_date' => '2024-06-15',
            'category' => 'Wedding',
            'location' => 'Taman Kota, Jakarta'
        ]);

        Portfolio::create([
            'title' => 'Corporate Gathering 2024',
            'description' => 'Acara gathering perusahaan besar dengan lebih dari 500 peserta. Acara ini mencakup berbagai kegiatan team building, hiburan, dan makan malam eksklusif.',
            'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80',
            'client' => 'PT Maju Jaya',
            'project_date' => '2024-08-20',
            'category' => 'Corporate',
            'location' => 'Hotel Grand Palace, Bandung'
        ]);

        Portfolio::create([
            'title' => 'Pesta Ulang Tahun Anak Tema Princess',
            'description' => 'Pesta ulang tahun anak dengan tema princess yang menakjubkan. Dekorasi penuh warna dengan elemen interaktif untuk anak-anak.',
            'image' => 'https://images.unsplash.com/photo-1530103862676-de3c9a59af38?auto=format&fit=crop&w=800&q=80',
            'client' => 'Keluarga Pratama',
            'project_date' => '2024-09-10',
            'category' => 'Children Party',
            'location' => 'Rumah Pribadi, Surabaya'
        ]);

        Portfolio::create([
            'title' => 'Product Launch Gadget Terbaru',
            'description' => 'Peluncuran produk gadget terbaru dengan tampilan futuristik dan teknologi canggih. Acara ini menarik perhatian media dan influencer teknologi.',
            'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=800&q=80',
            'client' => 'TechVision Inc',
            'project_date' => '2024-10-05',
            'category' => 'Product Launch',
            'location' => 'Convention Center, Bali'
        ]);

        Portfolio::create([
            'title' => 'Gala Dinner Amal',
            'description' => 'Makan malam amal eksklusif untuk membantu korban bencana alam. Acara ini menampilkan lelang amal dan hiburan premium.',
            'image' => 'https://images.unsplash.com/photo-1519225421980-715cb0202128?auto=format&fit=crop&w=800&q=80',
            'client' => 'Yayasan Peduli Bangsa',
            'project_date' => '2024-07-30',
            'category' => 'Gala Dinner',
            'location' => 'JW Marriott Hotel, Jakarta'
        ]);

        Portfolio::create([
            'title' => 'Konferensi Teknologi 2024',
            'description' => 'Konferensi teknologi internasional dengan pembicara dari seluruh dunia. Acara ini mencakup sesi presentasi, pameran, dan jaringan profesional.',
            'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80',
            'client' => 'Tech Summit Organization',
            'project_date' => '2024-11-12',
            'category' => 'Conference',
            'location' => 'International Convention Center, Yogyakarta'
        ]);
    }
}
