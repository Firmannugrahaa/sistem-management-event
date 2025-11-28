<?php

namespace Database\Seeders;

use App\Models\ClientRequest;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class ClientRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first vendor for sample data
        $vendor = Vendor::first();
        
        // Get admin/staff for assignment
        $admin = User::role('Admin')->first();
        $staff = User::role('Staff')->first();

        // Sample client requests
        $requests = [
            [
                'client_name' => 'Budi dan Siti',
                'client_email' => 'budi.siti@example.com',
                'client_phone' => '08123456789',
                'event_date' => now()->addDays(45),
                'budget' => 50000000,
                'event_type' => 'Wedding',
                'message' => 'Kami ingin mengadakan pernikahan outdoor dengan tema garden party. Mohon informasi paket yang tersedia.',
                'status' => 'pending',
                'vendor_id' => $vendor?->id,
                'request_source' => 'website',
            ],
            [
                'client_name' => 'PT Indo Tech',
                'client_email' => 'event@indotech.com',
                'client_phone' => '08234567890',
                'event_date' => now()->addDays(30),
                'budget' => 75000000,
                'event_type' => 'Corporate',
                'message' => 'Kami membutuhkan venue untuk annual gathering perusahaan sekitar 200 orang.',
                'status' => 'on_process',
                'assigned_to' => $admin?->id,
                'vendor_id' => $vendor?->id,
                'request_source' => 'email',
                'responded_at' => now()->subDays(2),
            ],
            [
                'client_name' => 'Dewi Lestari',
                'client_email' => 'dewi.lestari@example.com',
                'client_phone' => '08345678901',
                'event_date' => now()->addDays(60),
                'budget' => 30000000,
                'event_type' => 'Birthday',
                'message' => 'Ulang tahun anak ke-7, tema princess. Mohon rekomendasi dekorasi dan catering.',
                'status' => 'on_process',
                'assigned_to' => $staff?->id,
                'vendor_id' => $vendor?->id,
                'request_source' => 'phone',
                'responded_at' => now()->subDays(1),
            ],
            [
                'client_name' => 'Ahmad dan Fatimah',
                'client_email' => 'ahmad.fatimah@example.com',
                'client_phone' => '08456789012',
                'event_date' => now()->addDays(90),
                'budget' => 80000000,
                'event_type' => 'Wedding',
                'message' => 'Pernikahan indoor dengan konsep modern minimalis. Kapasitas tamu sekitar 300 orang.',
                'status' => 'done',
                'assigned_to' => $admin?->id,
                'vendor_id' => $vendor?->id,
                'request_source' => 'website',
                'responded_at' => now()->subDays(7),
            ],
            [
                'client_name' => 'Sarah Johnson',
                'client_email' => 'sarah.johnson@example.com',
                'client_phone' => '08567890123',
                'event_date' => now()->addDays(20),
                'budget' => 45000000,
                'event_type' => 'Engagement',
                'message' => 'Acara tunangan dengan tema rustic. Mencari paket lengkap dengan dekorasi dan catering.',
                'status' => 'pending',
                'vendor_id' => $vendor?->id,
                'request_source' => 'social_media',
            ],
            [
                'client_name' => 'CV Media Kreatif',
                'client_email' => 'info@mediakreatif.com',
                'client_phone' => '08678901234',
                'event_date' => now()->addDays(15),
                'budget' => 25000000,
                'event_type' => 'Conference',
                'message' => 'Workshop digital marketing untuk 50 peserta. Membutuhkan venue + sound system.',
                'status' => 'on_process',
                'assigned_to' => $staff?->id,
                'request_source' => 'referral',
                'responded_at' => now()->subHours(12),
            ],
        ];

        foreach ($requests as $request) {
            ClientRequest::create($request);
        }
    }
}
