<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kost;
use App\Models\Owner;

class KostSeeder extends Seeder
{
    public function run(): void
    {
        $owner = Owner::first();

        if ($owner) {
            Kost::create([
                'owner_id' => $owner->id,
                'name' => 'Kost Harmoni',
                'description' => 'Kost nyaman dan strategis dekat kampus',
                'location' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'google_maps_link' => 'https://maps.google.com',
                'type' => 'Campuran',
                'max_occupants' => 2,
                'price' => 1500000,
                'facilities' => 'WiFi, AC, Kamar Mandi Dalam, Kasur, Lemari, Meja Belajar',
                'images' => [],
                'contact_whatsapp' => '081234567890',
                'contact_instagram' => 'kostharmoni',
                'contact_facebook' => 'kostharmoni',
            ]);

            Kost::create([
                'owner_id' => $owner->id,
                'name' => 'Kost Putri Sejahtera',
                'description' => 'Khusus putri, aman dan nyaman',
                'location' => 'Jl. Gatot Subroto No. 45, Jakarta Selatan',
                'google_maps_link' => null,
                'type' => 'Putri',
                'max_occupants' => 1,
                'price' => 1200000,
                'facilities' => 'WiFi, Kamar Mandi Dalam, Kasur, Lemari',
                'images' => [],
                'contact_whatsapp' => '081234567890',
                'contact_instagram' => null,
                'contact_facebook' => null,
            ]);
        }
    }
}