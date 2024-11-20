<?php

namespace Database\Seeders;

use App\Models\Nomenklatur\A1Urusan;
use App\Models\Nomenklatur\A2Bidang;
use App\Models\Nomenklatur\A3Program;
use App\Models\Nomenklatur\A4Kegiatan;
use App\Models\Nomenklatur\A5Subkegiatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class NomenklaturSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $a1_urusans = json_decode(Storage::disk('public')->get('data/backup_db_versi_1/a1_urusans.json'), true);
        A1Urusan::truncate();
        foreach ($a1_urusans as $urusan) {
            A1Urusan::create($urusan);
        }

        $a2_bidangs = json_decode(Storage::disk('public')->get('data/backup_db_versi_1/a2_bidangs.json'), true);
        A2Bidang::truncate();
        foreach ($a2_bidangs as $bidang) {
            A2Bidang::create($bidang);
        }

        $a3_programs = json_decode(Storage::disk('public')->get('data/backup_db_versi_1/a3_programs.json'), true);
        A3Program::truncate();
        foreach ($a3_programs as $program) {
            A3Program::create($program);
        }

        $a4_kegiatans = json_decode(Storage::disk('public')->get('data/backup_db_versi_1/a4_kegiatans.json'), true);
        A4Kegiatan::truncate();
        foreach ($a4_kegiatans as $kegiatan) {
            A4Kegiatan::create($kegiatan);
        }

        $a5_subkegiatans = json_decode(Storage::disk('public')->get('data/backup_db_versi_1/a5_subkegiatans.json'), true);
        A5Subkegiatan::truncate();
        foreach ($a5_subkegiatans as $subkegiatan) {
            A5Subkegiatan::create($subkegiatan);
        }
    }
}
