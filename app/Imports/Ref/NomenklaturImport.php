<?php

namespace App\Imports\Ref;

use App\Models\Nomenklatur\A3Program;
use App\Models\Nomenklatur\A4Kegiatan;
use App\Models\Nomenklatur\A5Subkegiatan;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Maatwebsite\Excel\Concerns\WithLimit;

class NomenklaturImport implements ToModel, WithHeadingRow, WithCalculatedFormulas, SkipsEmptyRows, WithChunkReading, WithBatchInserts, ShouldQueue
{
    public function model(array $row)
    {
        A3Program::updateOrCreate(
            [
                'kode_program' => $row['kode_program'],
                'tahun' => $row['tahun'],
            ],
            [
                'kode_urusan' => $row['kode_urusan'],
                'kode_bidang' => $row['kode_bidang'],
                'uraian' => $row['nama_program'],
            ]
        );


        A4Kegiatan::updateOrCreate(
            [
                'kode_kegiatan' => $row['kode_kegiatan'],
                'tahun' => $row['tahun'],
            ],
            [
                'kode_urusan' => $row['kode_urusan'],
                'kode_bidang' => $row['kode_bidang'],
                'kode_program' => $row['kode_program'],
                'uraian' => $row['nama_kegiatan'],
            ]
        );

        A5Subkegiatan::updateOrCreate(
            [
                'kode_subkegiatan' => $row['kode_subkegiatan'],
                'tahun' => $row['tahun'],
            ],
            [
                'kode_urusan' => $row['kode_urusan'],
                'kode_bidang' => $row['kode_bidang'],
                'kode_program' => $row['kode_program'],
                'kode_kegiatan' => $row['kode_kegiatan'],
                'uraian' => $row['nama_subkegiatan'],
                'indikator' => $row['indikator'],
                'kinerja' => $row['kinerja'],
                'satuan' => $row['satuan'],
                'rutin' => $row['rutin'],
                'gaji' => $row['gaji'],
                'referensi' => 'SIPD-RI Pemuktahiran Tahun 2025',
                'tag' => json_decode($row['tag'], true),
                'definisi' => $row['definisi_operasional'],
                'pelaksana' => $row['pelaksana'],
                'spm' => $row['spm'],
            ]
        );
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }
}
