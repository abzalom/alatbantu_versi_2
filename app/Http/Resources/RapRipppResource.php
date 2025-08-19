<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RapRipppResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'kode_unik_opd' => $this->kode_unik_opd,
            'kode_opd' => $this->kode_opd,
            'kode_tema' => $this->kode_tema,
            'kode_program_rippp' => $this->kode_program,
            'kode_keluaran_rippp' => $this->kode_keluaran,
            'kode_aktifitas_rippp' => $this->kode_aktifitas,
            'kode_target_aktifitas_rippp' => $this->kode_target_aktifitas,
            'kode_target_aktifitas_rippp' => $this->kode_target_aktifitas,
            'kode_subkegiatan' => $this->kode_subkegiatan,
            'kode_kegiatan' => $this->kode_kegiatan,
            'indikator_subkegiatan' => $this->indikator_subkegiatan,
            'satuan_subkegiatan' => $this->satuan_subkegiatan,
            'klasifikasi_belanja' => $this->klasifikasi_belanja,
            'text_subkegiatan' => $this->text_subkegiatan,
            'sumberdana' => $this->sumberdana,
            'alias_dana' => $this->alias_dana,
            'penerima_manfaat' => $this->penerima_manfaat,
            'jenis_layanan' => $this->jenis_layanan,
            'jenis_kegiatan' => $this->jenis_kegiatan,
            'dana_lain' => $this->dana_lain,
            'vol_subkeg' => $this->vol_subkeg,
            'anggaran' => $this->anggaran,
            'mulai' => $this->mulai,
            'selesai' => $this->selesai,
            'keterangan' => $this->keterangan,
            'ppsb' => $this->ppsb,
            'multiyears' => $this->multiyears,
            'multiyears' => $this->multiyears,
            'file_path' => $this->file_path,
            'file_kak_name' => $this->file_kak_name,
            'file_rab_name' => $this->file_rab_name,
            'file_pendukung1_name' => $this->file_pendukung1_name,
            'file_pendukung2_name' => $this->file_pendukung2_name,
            'file_pendukung3_name' => $this->file_pendukung3_name,
            'link_file_dukung_lain' => $this->link_file_dukung_lain,
            'koordinat' => $this->koordinat,
            'pembahasan' => $this->pembahasan,
            'validasi' => $this->validasi,
            'catatan' => $this->catatan,
            'kirim' => $this->kirim,
            'tahun' => $this->tahun,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
