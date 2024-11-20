<?php

namespace Database\Seeders;

use App\Models\Otsus\Data\B1IndikatorTemaOtsus;
use App\Models\Otsus\Data\B1TemaOtsus;
use App\Models\Otsus\Data\B2ProgramPrioritasOtsus;
use App\Models\Otsus\Data\B3TargetKeluaranStrategisOtsus;
use App\Models\Otsus\Data\B4AktifitasUtamaOtsus;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OtsusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(Storage::disk('public')->get('data/otsus/indikator-otsus.json'), true);
        B1TemaOtsus::truncate();
        B1IndikatorTemaOtsus::truncate();
        B2ProgramPrioritasOtsus::truncate();
        B3TargetKeluaranStrategisOtsus::truncate();
        B4AktifitasUtamaOtsus::truncate();
        B5TargetAktifitasUtamaOtsus::truncate();
        foreach ($data as $tema) {
            B1TemaOtsus::create([
                'kode_tema' => $tema['kode_tema'],
                'uraian' => $tema['uraian'],
                'tahun' => 2025,
            ]);
            foreach ($tema['indikator'] as $indikator) {
                B1IndikatorTemaOtsus::create($indikator);
            }
            foreach ($tema['program'] as $program) {
                B2ProgramPrioritasOtsus::create([
                    'kode_tema' => $program['kode_tema'],
                    'kode_program' => $program['kode_program'],
                    'uraian' => $program['uraian'],
                    'tahun' => 2025,
                ]);
                foreach ($program['keluaran'] as $keluaran) {
                    B3TargetKeluaranStrategisOtsus::create([
                        'kode_tema' => $keluaran['kode_tema'],
                        'kode_program' => $keluaran['kode_program'],
                        'kode_keluaran' => $keluaran['kode_keluaran'],
                        'uraian' => $keluaran['uraian'],
                        'satuan' => $keluaran['satuan'],
                        'tahun' => 2025,
                    ]);
                    foreach ($keluaran['aktifitas'] as $aktifitas) {
                        B4AktifitasUtamaOtsus::create([
                            'kode_tema' => $aktifitas['kode_tema'],
                            'kode_program' => $aktifitas['kode_program'],
                            'kode_keluaran' => $aktifitas['kode_keluaran'],
                            'kode_aktifitas' => $aktifitas['kode_aktifitas'],
                            'uraian' => $aktifitas['uraian'],
                            'tahun' => 2025,
                        ]);
                        foreach ($aktifitas['target_aktifitas'] as $target_aktifitas) {
                            B5TargetAktifitasUtamaOtsus::create([
                                'kode_tema' => $target_aktifitas['kode_tema'],
                                'kode_program' => $target_aktifitas['kode_program'],
                                'kode_keluaran' => $target_aktifitas['kode_keluaran'],
                                'kode_aktifitas' => $target_aktifitas['kode_aktifitas'],
                                'kode_target_aktifitas' => $target_aktifitas['kode_target_aktifitas'],
                                'uraian' => $target_aktifitas['uraian'],
                                'satuan' => $target_aktifitas['satuan'],
                                'tahun' => 2025,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
