<?php

namespace App\Jobs;

use App\Models\Nomenklatur\NomenklaturSikd;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SinkronDataSikd implements ShouldQueue
{
    use Queueable;

    /**
     * Initialize job properties
     */
    public array $data;
    public string $jenis;
    public string $sumberdana;


    /**
     * Create a new job instance.
     */
    public function __construct(array $data, string $jenis, string $sumberdana)
    {
        $this->data = $data;
        $this->jenis = $jenis;
        $this->sumberdana = $sumberdana;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->jenis == 'nomenklatur') {
            $this->nomenklatur();
        }
    }

    public function nomenklatur()
    {
        foreach ($this->data as $item) {
            $create = NomenklaturSikd::updateOrCreate(
                [
                    'id_subkegiatan' => $item['id_subkegiatan'],
                    'sumberdana' => $item['sumberdana'],
                ],
                [
                    'kode_bidang' => $item['kode_bidang'],
                    'nama_bidang' => $item['nama_bidang'],
                    'kode_program' => $item['kode_program'],
                    'nama_program' => $item['nama_program'],
                    'kode_kegiatan' => $item['kode_kegiatan'],
                    'nama_kegiatan' => $item['nama_kegiatan'],
                    'kode_subkegiatan' => $item['kode_subkegiatan'],
                    'nama_subkegiatan' => $item['nama_subkegiatan'],
                    'text' => $item['text'],
                    'indikator' => $item['indikator'],
                    'satuan' => $item['satuan'],
                    'klasifikasi_belanja' => $item['klasifikasi_belanja'],
                ]
            );
        }
    }
}
