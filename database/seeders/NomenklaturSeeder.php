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
        A1Urusan::truncate();
        A2Bidang::truncate();
        A3Program::truncate();
        A4Kegiatan::truncate();
        A5Subkegiatan::truncate();

        $a1_urusans = json_decode(Storage::disk('public')->get('data/backup_db_versi_1/a1_urusans.json'), true);
        A1Urusan::truncate();
        foreach ($a1_urusans as $urusan) {
            A1Urusan::updateOrCreate($urusan);
        }

        $a2_bidangs = json_decode(Storage::disk('public')->get('data/backup_db_versi_1/a2_bidangs.json'), true);
        A2Bidang::truncate();
        foreach ($a2_bidangs as $bidang) {
            A2Bidang::updateOrCreate($bidang);
        }


        // $data = json_decode(Storage::disk('public')->get('data/sipd-ri/sipd_pemuktahiran.json'), true);
        // $programs = [];
        // $kegiatans = [];
        // $subkegiatans = [];
        // foreach ($data as $item) {
        //     $prog = explode(' ', $item['program'], 2);
        //     $kode_urusan = explode('.', $prog[0])[0];
        //     $kode_bidang = explode('.', $prog[0])[0] . '.' . explode('.', $prog[0])[1];
        //     if (!isset($programs[$prog[0]])) {
        //         A3Program::updateOrCreate(
        //             [
        //                 'kode_program' => $prog[0],
        //                 'tahun' => $item['tahun'],
        //             ],
        //             [
        //                 'kode_urusan' => explode('.', $prog[0])[0],
        //                 'kode_bidang' => explode('.', $prog[0])[0] . '.' . explode('.', $prog[0])[1],
        //                 'uraian' => isset($prog[1]) ? $prog[1] : '',
        //             ]
        //         );
        //     }

        //     $keg = explode(' ', $item['kegiatan'], 2);
        //     if (!isset($kegiatans[$keg[0]])) {
        //         A4Kegiatan::updateOrCreate(
        //             [
        //                 'kode_kegiatan' => $keg[0],
        //                 'tahun' => $item['tahun'],
        //             ],
        //             [
        //                 'kode_urusan' => explode('.', $prog[0])[0],
        //                 'kode_bidang' => explode('.', $prog[0])[0] . '.' . explode('.', $prog[0])[1],
        //                 'kode_program' => $prog[0],
        //                 'uraian' => isset($keg[1]) ? $keg[1] : '',
        //             ]
        //         );
        //     }

        //     $subkeg = explode(' ', $item['subkegiatan'], 2);
        //     $gaji = false;

        //     $kode_gaji = [
        //         'X.XX.01.2.02.0001',
        //         'X.XX.01.2.11.0001',
        //         'X.XX.01.3.01.0001',
        //         'X.XX.01.3.01.0002',
        //         'X.XX.01.4.01.0001',
        //         'X.XX.01.4.01.0002',
        //         'X.XX.01.2.15.0001',
        //     ];

        //     if (in_array($subkeg[0], $kode_gaji)) {
        //         $gaji = true;
        //     }

        //     A5Subkegiatan::updateOrCreate(
        //         [
        //             'kode_subkegiatan' => $subkeg[0],
        //             'tahun' => $item['tahun'],
        //         ],
        //         [
        //             'kode_urusan' => explode('.', $prog[0])[0],
        //             'kode_bidang' => explode('.', $prog[0])[0] . '.' . explode('.', $prog[0])[1],
        //             'kode_program' => $prog[0],
        //             'kode_kegiatan' => $keg[0],
        //             'uraian' => isset($subkeg[1]) ? $subkeg[1] : '',
        //             'indikator' => $item['indikator'],
        //             'kinerja' => $item['kinerja'],
        //             'satuan' => $item['satuan'],
        //             'rutin' => $kode_urusan == 'X' ? true : false,
        //             'gaji' => $gaji,
        //             'referensi' => 'SIPD-RI Pemuktahiran Tahun 2025',
        //             'tag' => json_decode($item['tag'], true),
        //             'definisi' => $item['definisi_operasional'],
        //             'pelaksana' => $item['pelaksana'],
        //             'spm' => $item['spm'],
        //             'jenis' => $item['jenis'],
        //             'subkegiatan_sebelumnya' => $item['subkegiatan_sebelumnya'],
        //         ]
        //     );
        // }
    }
}
