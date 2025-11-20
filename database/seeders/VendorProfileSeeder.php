<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\ServiceType;
use App\Models\User;

class VendorProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create some service types
        $serviceTypes = [
            [
                'name' => 'Catering',
                'description' => 'Layanan makanan dan minuman untuk berbagai acara, dengan berbagai pilihan menu sesuai kebutuhan.'
            ],
            [
                'name' => 'Sound System',
                'description' => 'Layanan peralatan audio untuk acara, termasuk sound system, mikrofon, dan peralatan panggung.'
            ],
            [
                'name' => 'Photography',
                'description' => 'Layanan dokumentasi foto profesional untuk berbagai jenis acara.'
            ],
            [
                'name' => 'Decoration',
                'description' => 'Layanan dekorasi dan tata panggung untuk berbagai jenis acara.'
            ],
            [
                'name' => 'Transportation',
                'description' => 'Layanan transportasi untuk kebutuhan acara.'
            ]
        ];

        foreach ($serviceTypes as $serviceType) {
            ServiceType::updateOrCreate(
                ['name' => $serviceType['name']],
                [
                    'name' => $serviceType['name'],
                    'description' => $serviceType['description']
                ]
            );
        }

        // Get users to connect with vendors
        $users = User::where('role', 'Vendor')->get();
        
        if ($users->count() > 0) {
            // Create some dummy vendors, making sure user_id is unique
            $vendors = [
                [
                    'user_id' => $users->first()->id,
                    'service_type_id' => ServiceType::where('name', 'Catering')->first()->id,
                    'category' => 'Catering',
                    'contact_person' => 'Ahmad Hidayat',
                    'phone_number' => '081234567890',
                    'address' => 'Jl. Merdeka No. 123, Jakarta Pusat'
                ],
            ];

            // Only add more vendors if we have more users
            if ($users->count() > 1) {
                $vendors[] = [
                    'user_id' => $users[1]->id,
                    'service_type_id' => ServiceType::where('name', 'Sound System')->first()->id,
                    'category' => 'Sound System',
                    'contact_person' => 'Budi Santoso',
                    'phone_number' => '085678901234',
                    'address' => 'Jl. Sudirman No. 45, Jakarta Selatan'
                ];
            }

            if ($users->count() > 2) {
                $vendors[] = [
                    'user_id' => $users[2]->id,
                    'service_type_id' => ServiceType::where('name', 'Photography')->first()->id,
                    'category' => 'Photography',
                    'contact_person' => 'Siti Nurhaliza',
                    'phone_number' => '087890123456',
                    'address' => 'Jl. Thamrin No. 78, Jakarta Pusat'
                ];
            }

            foreach ($vendors as $vendorData) {
                Vendor::updateOrCreate(
                    ['user_id' => $vendorData['user_id']], // Use user_id as the unique condition
                    [
                        'service_type_id' => $vendorData['service_type_id'],
                        'category' => $vendorData['category'],
                        'contact_person' => $vendorData['contact_person'],
                        'phone_number' => $vendorData['phone_number'],
                        'address' => $vendorData['address']
                    ]
                );
            }
        } else {
            // If no vendor users exist, create some
            $vendorUsers = [
                [
                    'name' => 'Catering Vendor',
                    'email' => 'catering@vendor.com',
                    'username' => 'catering_vendor',
                    'password' => bcrypt('vendor123'),
                    'role' => 'Vendor'
                ],
                [
                    'name' => 'Sound System Vendor',
                    'email' => 'sound@vendor.com',
                    'username' => 'sound_vendor',
                    'password' => bcrypt('vendor123'),
                    'role' => 'Vendor'
                ],
                [
                    'name' => 'Photography Vendor',
                    'email' => 'photo@vendor.com',
                    'username' => 'photo_vendor',
                    'password' => bcrypt('vendor123'),
                    'role' => 'Vendor'
                ]
            ];

            $createdUsers = [];
            foreach ($vendorUsers as $userData) {
                $user = User::updateOrCreate(
                    ['email' => $userData['email']],
                    $userData
                );
                $createdUsers[] = $user;
            }

            // Create vendors for these users
            $vendors = [
                [
                    'user_id' => $createdUsers[0]->id,
                    'service_type_id' => ServiceType::where('name', 'Catering')->first()->id,
                    'category' => 'Catering',
                    'contact_person' => 'Ahmad Hidayat',
                    'phone_number' => '081234567890',
                    'address' => 'Jl. Merdeka No. 123, Jakarta Pusat'
                ],
                [
                    'user_id' => $createdUsers[1]->id,
                    'service_type_id' => ServiceType::where('name', 'Sound System')->first()->id,
                    'category' => 'Sound System',
                    'contact_person' => 'Budi Santoso',
                    'phone_number' => '085678901234',
                    'address' => 'Jl. Sudirman No. 45, Jakarta Selatan'
                ],
                [
                    'user_id' => $createdUsers[2]->id,
                    'service_type_id' => ServiceType::where('name', 'Photography')->first()->id,
                    'category' => 'Photography',
                    'contact_person' => 'Siti Nurhaliza',
                    'phone_number' => '087890123456',
                    'address' => 'Jl. Thamrin No. 78, Jakarta Pusat'
                ]
            ];

            foreach ($vendors as $vendorData) {
                Vendor::updateOrCreate(
                    ['user_id' => $vendorData['user_id']], // Use user_id as the unique condition
                    [
                        'service_type_id' => $vendorData['service_type_id'],
                        'category' => $vendorData['category'],
                        'contact_person' => $vendorData['contact_person'],
                        'phone_number' => $vendorData['phone_number'],
                        'address' => $vendorData['address']
                    ]
                );
            }
        }
    }
}