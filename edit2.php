<?php

use App\Models\Data\Opd;
use App\Models\Data\Lokus;
use App\Models\Rap\RapOtsus;
use App\Models\Data\Sumberdana;
use App\Models\Nomenklatur\A5Subkegiatan;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;

function model($row)
{
    $target_aktifitas = B5TargetAktifitasUtamaOtsus::where('kode_target_aktifitas', $row['kode_indikator'])->first();
    $subkegiatan = A5Subkegiatan::where('kode_subkegiatan', $row['kode_subkegiatan'])->first();
    $opd = Opd::where('kode_opd', $row['kode_skpd'])->first();

    $kode_opd = $opd ? $opd['kode_opd'] : '';
    $kode_target_aktifitas = $target_aktifitas ? $target_aktifitas['kode_target_aktifitas'] : '';
    preg_match_all('/\d+(?=~)/', $row['lokasi'], $matchLokasi);
    $id_lokasi = $matchLokasi[0]; // Ambil semua angka sebelum '~'
    preg_match_all('/\d+(?=~)/', $row['dana_lain'], $mathcDanaLain);
    $id_sumberdana = $mathcDanaLain[0]; // Ambil semua angka sebelum '~'

    $getDanaLain = Sumberdana::whereIn('id', $id_sumberdana)->get();
    $dana_lain = $getDanaLain->count() > 0 ? $getDanaLain->map(function ($item) {
        return [
            'id' => $item->id,
            'uraian' => $item->uraian,
        ];
    })->toJson() : null;
    $getLokus = Lokus::whereIn('id', $id_lokasi)->get();
    $lokus = $getLokus->count() > 0 ? $getLokus->map(function ($item) {
        return [
            'id' => $item->id,
            'kecamatan' => $item->kecamatan,
            'kampung' => $item->kampung,
        ];
    })->toJson() : null;

    $newData = [
        'kode_unik_opd_tag_otsus' => $row['tahun'] . '-' . $kode_opd . '-' . $kode_target_aktifitas,
        'kode_opd' => $opd['kode_opd'],
        'kode_tema' => $target_aktifitas ? $target_aktifitas['kode_tema'] : null,
        'kode_program' => $target_aktifitas ? $target_aktifitas['kode_program'] : null,
        'kode_keluaran' => $target_aktifitas ? $target_aktifitas['kode_keluaran'] : null,
        'kode_aktifitas' => $target_aktifitas ? $target_aktifitas['kode_aktifitas'] : null,
        'kode_target_aktifitas' => $target_aktifitas ? $target_aktifitas['kode_target_aktifitas'] : $row['kode_indikator'],
        'kode_subkegiatan' => $subkegiatan ? $subkegiatan['kode_subkegiatan'] : $row['kode_subkegiatan'],
        'nama_subkegiatan' => $subkegiatan ? $subkegiatan['uraian'] : null,
        'indikator_subkegiatan' => $subkegiatan ?  $subkegiatan['indikator'] : null,
        'satuan_subkegiatan' => $subkegiatan ? $subkegiatan['satuan'] : null,
        'klasifikasi_belanja' => $subkegiatan ? $subkegiatan['klasifikasi_belanja'] : null,
        'text_subkegiatan' => $subkegiatan ? $subkegiatan['kode_subkegiatan'] . ' ' . $subkegiatan['uraian'] : $row['kode_subkegiatan'] . ' ' . $row['nama_subkegiatan'],
        'sumberdana' => $target_aktifitas ? $target_aktifitas['sumberdana'] : null,
        'penerima_manfaat' => strtolower($row['penerima_manfaat']),
        'jenis_layanan' => strtolower($row['jenis_layanan']),
        'jenis_kegiatan' => strtolower($row['jenis_kegiatan']),
        'dana_lain' => $dana_lain,
        'lokus' => $lokus,
        'vol_subkeg' => clearFloatFormat($row['volume_subkegiatan']) ? clearFloatFormat($row['volume_subkegiatan']) : $row['volume_subkegiatan'],
        'anggaran' => clearFloatFormat($row['anggaran']) ? clearFloatFormat($row['anggaran']) : $row['anggaran'],
        'mulai' => $row['mulai'],
        'selesai' => $row['selesai'],
        'keterangan' => $row['keterangan'] ? $row['keterangan'] : null,
        'ppsb' => strtolower($row['ppsb']),
        'multiyears' => strtolower($row['multiyears']),
        'koordinat' => $row['koordinat'],
        'catatan' => array_key_exists('catatan', $row) ? $row['catatan'] : null,
        'tahun' => $row['tahun'],
    ];
}
