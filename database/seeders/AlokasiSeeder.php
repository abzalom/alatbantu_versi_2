<?php

namespace Database\Seeders;

use App\Models\Otsus\DanaAlokasiOtsus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AlokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(Storage::disk('public')->get('/data/otsus/data_alokasi_otsuses.json'), true);

        DanaAlokasiOtsus::truncate();
        foreach ($data as $alokasi) {
            DanaAlokasiOtsus::create($alokasi);
        }
    }
}
