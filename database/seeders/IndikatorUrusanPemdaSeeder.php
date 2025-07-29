<?php

namespace Database\Seeders;

use App\Models\Data\Perencanaan\IndikatorUrusanPemda;
use App\Models\Nomenklatur\A2Bidang;
use App\Models\TargetIndikatorUrusan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IndikatorUrusanPemdaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = collect(json_decode(Storage::disk('public')->get('/data/indikator/indikator_urusan.json'), true))->sortBy('kode_bidang');
        IndikatorUrusanPemda::truncate();
        TargetIndikatorUrusan::truncate();
        foreach ($json as $item) {
            $kodeIndikator = $item['kode_indikator'];
            if (strlen($item['kode_indikator']) == 1) {
                $kodeIndikator = '00000' . $item['kode_indikator'];
            }
            if (strlen($item['kode_indikator']) == 2) {
                $kodeIndikator = '0000' . $item['kode_indikator'];
            }
            if (strlen($item['kode_indikator']) == 3) {
                $kodeIndikator = '000' . $item['kode_indikator'];
            }
            if (strlen($item['kode_indikator']) == 4) {
                $kodeIndikator = '00' . $item['kode_indikator'];
            }
            if (strlen($item['kode_indikator']) == 5) {
                $kodeIndikator = '0' . $item['kode_indikator'];
            }
            $indikatorCreate = IndikatorUrusanPemda::create([
                'kode_bidang' => $item['kode_bidang'],
                'kode_indikator' => $kodeIndikator,
                'nama_indikator' => $item['nama_indikator'],
                'satuan' => $item['satuan'],
            ]);

            $bidang = A2Bidang::where('kode_bidang', $indikatorCreate->kode_bidang)->first();

            $targetIndikator = [
                'indikator_urusan_pemda_id' => $indikatorCreate->id,
                'a2_bidang_id' => $bidang->id,
                'kode_bidang' => $indikatorCreate->kode_bidang,
                'kode_indikator' => $indikatorCreate->kode_indikator,
                'target_nasional' => $item['target_nasional'],
                'satuan' => $indikatorCreate->satuan,
                'tahun' => now()->format('Y'),
            ];
            TargetIndikatorUrusan::create($targetIndikator);
        }
    }
}
