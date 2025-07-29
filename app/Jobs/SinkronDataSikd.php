<?php

namespace App\Jobs;

use App\Models\Data\Sikd\Publish\SikdPublishRap;
use App\Models\Nomenklatur\NomenklaturSikd;
use App\Models\Rap\RapOtsus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

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
            // Log::debug("Sinkronisasi Nomenklatur SIKD", ['data' => $this->data]);
            $this->nomenklatur();
        }
        if ($this->jenis == 'rap') {
            $this->rap();
        }
    }

    public function nomenklatur()
    {
        foreach ($this->data as $item) {
            $rap = RapOtsus::where('kode_subkegiatan', $item['kode_subkegiatan'])->get();

            if ($rap->isNotEmpty()) { // Gunakan isNotEmpty() sebagai alternatif yang lebih jelas
                foreach ($rap as $r) {
                    Log::info("Updating RapOtsus", ['data' => $r->toArray()]);
                    $r->klasifikasi_belanja = $item['klasifikasi_belanja'];
                    $r->save();
                }
            }
            $create = NomenklaturSikd::updateOrCreate(
                [
                    'id_subkegiatan' => $item['id_subkegiatan'],
                    'sumberdana' => $item['sumberdana'],
                    'tahun' => $item['tahun'],
                ],
                [
                    'kode_bidang' => $item['kode_bidang'],
                    'nama_bidang' => $item['nama_bidang'],
                    'kode_program' => $item['kode_program'],
                    'nama_program' => $item['nama_program'],
                    'kode_kegiatan' => $item['kode_kegiatan'],
                    'nama_kegiatan' => $item['nama_kegiatan'],
                    'kode_subkegiatan' => $item['kode_subkegiatan'],
                    'kode_unik_subkegiatan' => $item['kode_subkegiatan'] . '-' . $item['sumberdana'],
                    'nama_subkegiatan' => $item['nama_subkegiatan'],
                    'text' => $item['text'],
                    'indikator' => $item['indikator'],
                    'satuan' => $item['satuan'],
                    'klasifikasi_belanja' => $item['klasifikasi_belanja'],
                ]
            );
        }
    }

    public function rap()
    {
        foreach ($this->data as $item) {
            SikdPublishRap::updateOrCreate(
                [
                    'id_rap' => $item['id_rap']
                ],
                [
                    'sumberdana' => $item['sumberdana'],
                    'rencanaanggaranprogram_id' => $item['rencanaanggaranprogram_id'],
                    'subkegiatan_id' => $item['subkegiatan_id'],
                    'subkegiatan_history_id' => $item['subkegiatan_history_id'],
                    'jenis_kegiatan' => $item['jenis_kegiatan'],
                    'target_keluaran' => $item['target_keluaran'],
                    'target_keluaran_efisiensi' => $item['target_keluaran_efisiensi'],
                    'target_keluaran_non_efisiensi' => $item['target_keluaran_non_efisiensi'],
                    'pagu_alokasi' => $item['pagu_alokasi'],
                    'pagu_efisiensi' => $item['pagu_efisiensi'],
                    'pagu_non_efisiensi' => $item['pagu_non_efisiensi'],
                    'lokus' => json_encode($item['lokus']),
                    'koordinat' => $item['koordinat'],
                    'koordinat_lintang' => $item['koordinat_lintang'],
                    'koordinat_bujur' => $item['koordinat_bujur'],
                    'opd_id' => $item['opd_id'],
                    'jadwal_kegiatan_awal' => $item['jadwal_kegiatan_awal'],
                    'jadwal_kegiatan_akhir' => $item['jadwal_kegiatan_akhir'],
                    'keterangan' => $item['keterangan'],
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at'],
                    'link_file_dukung' => $item['link_file_dukung'],
                    'helper_id' => $item['helper_id'],
                    'aktivitas_id' => $item['aktivitas_id'],
                    'jenis_layanan' => $item['jenis_layanan'],
                    'penerima_manfaat' => $item['penerima_manfaat'],
                    'program_strategis' => $item['program_strategis'],
                    'pendanaan_lain' => json_encode($item['pendanaan_lain']),
                    'multiyears' => $item['multiyears'],
                    'kode_urusan' => $item['kode_urusan'],
                    'uraian_urusan' => $item['uraian_urusan'],
                    'kode_bidang_urusan' => $item['kode_bidang_urusan'],
                    'uraian_bidang_urusan' => $item['uraian_bidang_urusan'],
                    'kode_program' => $item['kode_program'],
                    'uraian_program' => $item['uraian_program'],
                    'kode_kegiatan' => $item['kode_kegiatan'],
                    'uraian_kegiatan' => $item['uraian_kegiatan'],
                    'kode_subkegiatan' => $item['kode_subkegiatan'],
                    'klasifikasi_belanja' => $item['klasifikasi_belanja'],
                    'subkegiatan_uraian' => $item['subkegiatan_uraian'],
                    'indikator_keluaran' => $item['indikator_keluaran'],
                    'satuan' => $item['satuan'],
                    'kode_subkegiatan_full' => $item['kode_subkegiatan_full'],
                    'text_subkegiatan' => $item['text_subkegiatan'],
                    'opd_uraian' => $item['opd_uraian'],
                    'kesesuaian' => $item['kesesuaian'],
                    'tahun' => $item['tahun'],
                ]
            );
        }
    }
}
