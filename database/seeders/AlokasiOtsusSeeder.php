<?php

namespace Database\Seeders;

use App\Models\Otsus\DanaAlokasiOtsus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlokasiOtsusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DanaAlokasiOtsus::truncate();
        $data = [
            [
                'tahun' => 2022,
                'alokasi_bg' => 59593516000,
                'alokasi_sg' => 68610956000,
                'alokasi_dti' => 11681550000,
                'status' => 'realisasi'
            ],
            [
                'tahun' => 2023,
                'alokasi_bg' => 46734811000,
                'alokasi_sg' => 58418514000,
                'alokasi_dti' => 69227763000,
                'status' => 'realisasi'
            ],
            [
                'tahun' => 2024,
                'alokasi_bg' => 48054708000,
                'alokasi_sg' => 72639280000,
                'alokasi_dti' => 37899033000,
                'status' => 'realisasi'
            ],
            [
                'tahun' => 2025,
                'alokasi_bg' => 63385882000,
                'alokasi_sg' => 80316354000,
                'alokasi_dti' => 56885588000,
                'status' => 'indikatif'
            ],
            [
                'tahun' => 2026,
                'alokasi_bg' => 49692921000,
                'alokasi_sg' => 51032082000,
                'alokasi_dti' => 16375009000,
                'status' => 'perkiraan'
            ],
        ];

        foreach ($data as $alokasi) {
            DanaAlokasiOtsus::create($alokasi);
        }
    }
}
