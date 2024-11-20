<?php

namespace Database\Seeders;

use App\Models\Data\Lokus;
use App\Models\Data\Sumberdana;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReferensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasi = json_decode(Storage::disk('public')->get('data/referensi/lokasi.json'), true);
        foreach ($lokasi as $lokus) {
            Lokus::create($lokus);
        }

        $sumberdana = json_decode(Storage::disk('public')->get('data/referensi/sumberdana.json'), true);
        foreach ($sumberdana as $dana) {
            Sumberdana::create($dana);
        }
    }
}
