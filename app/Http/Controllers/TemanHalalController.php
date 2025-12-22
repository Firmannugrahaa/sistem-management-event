<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemanHalalController extends Controller
{
    public function index()
    {
        // Dummy data untuk vendor
        $vendors = [
            [
                'name' => 'Sakinah Wedding Organizer',
                'category' => 'Wedding Organizer',
                'image' => 'https://placehold.co/600x400/F7EDE2/1C2440?text=Wedding+Organizer',
                'rating' => 4.9,
                'reviews' => 128,
                'status' => 'Verified Partner'
            ],
            [
                'name' => 'Ijab & Qabul Catering',
                'category' => 'Catering',
                'image' => 'https://placehold.co/600x400/FFCDB2/1C2440?text=Catering+Halal',
                'rating' => 4.8,
                'reviews' => 95,
                'status' => 'Verified Partner'
            ],
            [
                'name' => 'Mahligai Dekorasi',
                'category' => 'Dekorasi',
                'image' => 'https://placehold.co/600x400/D4AF37/FFFFFF?text=Dekorasi+Elegan',
                'rating' => 4.7,
                'reviews' => 76,
                'status' => 'Verified Partner'
            ],
            [
                'name' => 'Sanggar Aisyah',
                'category' => 'Sanggar',
                'image' => 'https://placehold.co/600x400/F7EDE2/1C2440?text=Sanggar+Busana',
                'rating' => 4.9,
                'reviews' => 142,
                'status' => 'Verified Partner'
            ],
            [
                'name' => 'Studio Cahaya Halal',
                'category' => 'Fotografi',
                'image' => 'https://placehold.co/600x400/FFCDB2/1C2440?text=Foto+Studio',
                'rating' => 4.8,
                'reviews' => 110,
                'status' => 'Verified Partner'
            ],
            [
                'name' => 'Busana Syar\'i Elegan',
                'category' => 'Busana',
                'image' => 'https://placehold.co/600x400/D4AF37/FFFFFF?text=Busana+Muslim',
                'rating' => 4.7,
                'reviews' => 88,
                'status' => 'Verified Partner'
            ]
        ];

        // Dummy data untuk event
        $events = [
            [
                'name' => 'Wedding Akad Syar\'i',
                'vendor' => 'Sakinah Wedding Organizer',
                'image' => 'https://placehold.co/800x600/F7EDE2/1C2440?text=Akad+Syr\'i',
                'total_events' => 24
            ],
            [
                'name' => 'Resepsi Tradisi Nusantara',
                'vendor' => 'Ijab & Qabul Catering',
                'image' => 'https://placehold.co/800x600/FFCDB2/1C2440?text=Resepsi+Nusantara',
                'total_events' => 18
            ],
            [
                'name' => 'Akad Outdoor Garden',
                'vendor' => 'Mahligai Dekorasi',
                'image' => 'https://placehold.co/800x600/D4AF37/FFFFFF?text=Akad+Garden',
                'total_events' => 32
            ],
            [
                'name' => 'Wedding Indoor Mewah',
                'vendor' => 'Sanggar Aisyah',
                'image' => 'https://placehold.co/800x600/F7EDE2/1C2440?text=Wedding+Indoor',
                'total_events' => 15
            ]
        ];

        // Dummy data untuk paket
        $packages = [
            [
                'name' => 'Paket Silver',
                'price' => 15000000,
                'features' => [
                    'Akad & Resepsi Outdoor',
                    'Dekorasi Elegan',
                    'Catering 200 porsi',
                    'Fotografi & Video',
                    'Makeup & Busana'
                ],
                'is_featured' => false
            ],
            [
                'name' => 'Paket Gold',
                'price' => 25000000,
                'features' => [
                    'Akad & Resepsi Indoor/Outdoor',
                    'Dekorasi Premium',
                    'Catering 300 porsi',
                    'Fotografi & Video',
                    'Makeup & Busana',
                    'Transportasi Pengantin'
                ],
                'is_featured' => true
            ],
            [
                'name' => 'Paket Platinum',
                'price' => 40000000,
                'features' => [
                    'Akad & Resepsi Indoor/Outdoor',
                    'Dekorasi VIP',
                    'Catering 500 porsi',
                    'Fotografi & Video (2 Hari)',
                    'Makeup & Busana',
                    'Transportasi Pengantin',
                    'Hiburan & MC Profesional'
                ],
                'is_featured' => false
            ]
        ];

        return view('temanhalal', compact('vendors', 'events', 'packages'));
    }
}