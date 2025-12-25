<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;

class SampleReportsSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 3; // ID user masyarakat

        // 5 alamat berbeda di Surabaya
        $addresses = [
            [
                'address' => 'Jl. Pemuda No.1, Genteng, Surabaya',
                'lat' => -7.2575,
                'lng' => 112.7521
            ],
            [
                'address' => 'Jl. Raya Darmo No.55, Wonokromo, Surabaya',
                'lat' => -7.2760,
                'lng' => 112.7440
            ],
            [
                'address' => 'Jl. Mayjen Sungkono No.89, Rungkut, Surabaya',
                'lat' => -7.2765,
                'lng' => 112.7525
            ],
            [
                'address' => 'Jl. Basuki Rahmat No.20, Tegalsari, Surabaya',
                'lat' => -7.2630,
                'lng' => 112.7370
            ],
            [
                'address' => 'Jl. A.Yani No.123, Gubeng, Surabaya',
                'lat' => -7.2600,
                'lng' => 112.7500
            ],
        ];

        // Ambil lokasi untuk 5 laporan pertama (sama titik)
        $sameLoc = $addresses[0];

        // 5 laporan pertama -> lokasi sama
        for ($i = 1; $i <= 5; $i++) {
            Report::create([
                'user_id'    => $userId,
                'title'      => "Laporan Banjir #$i",
                'description'=> "Ini adalah contoh laporan banjir ke-$i dari seeder (titik sama).",
                'latitude'   => $sameLoc['lat'],
                'longitude'  => $sameLoc['lng'],
                'address'    => $sameLoc['address'],
                'status'     => 'pending',
                'photo'      => null,
            ]);
        }

        // 5 laporan berikutnya -> lokasi berbeda
        for ($i = 6; $i <= 10; $i++) {
            $loc = $addresses[array_rand($addresses)];

            Report::create([
                'user_id'    => $userId,
                'title'      => "Laporan Banjir #$i",
                'description'=> "Ini adalah contoh laporan banjir ke-$i dari seeder (titik berbeda).",
                'latitude'   => $loc['lat'] + rand(-5,5)/1000, // variasi kecil agar tidak sama persis
                'longitude'  => $loc['lng'] + rand(-5,5)/1000,
                'address'    => $loc['address'],
                'status'     => 'pending',
                'photo'      => null,
            ]);
        }
    }
}
