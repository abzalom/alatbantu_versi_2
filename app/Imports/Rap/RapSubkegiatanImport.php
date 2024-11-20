<?php

namespace App\Imports\Rap;

use App\Models\Data\Lokus;
use App\Models\Data\Opd;
use App\Models\Data\Sumberdana;
use App\Models\Nomenklatur\A5Subkegiatan;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use App\Models\Rap\RapOtsus;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use function PHPUnit\Framework\isEmpty;

class RapSubkegiatanImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsEmptyRows,
    WithCalculatedFormulas
{

    use Importable, SkipsFailures;

    public function model($row)
    {
        // dump($row);
        $duplikasi = RapOtsus::where([
            'kode_unik_opd_tag_otsus' => $row['kode_unik_opd_tag_otsus'],
            'kode_subkegiatan' => $row['kode_subkegiatan'],
            'tahun' => $row['tahun'],
        ])->exists();
        if (!$duplikasi) {
            RapOtsus::create($row);
        }
    }

    public function prepareForValidation($data, $index)
    {
        $target_aktifitas = B5TargetAktifitasUtamaOtsus::where('kode_target_aktifitas', $data['kode_indikator'])->first();
        $subkegiatan = A5Subkegiatan::where('kode_subkegiatan', $data['kode_subkegiatan'])->first();
        $subkegiatan = $subkegiatan ? $subkegiatan : null;
        $opd = Opd::where('kode_opd', $data['kode_skpd'])->first();

        $kode_opd = $opd ? $opd['kode_opd'] : '';
        $kode_unik_opd = $opd ? $opd['kode_unik_opd'] : '';

        $kode_target_aktifitas = $target_aktifitas ? $target_aktifitas['kode_target_aktifitas'] : '';
        $kode_unik_opd_tag_otsus = $kode_unik_opd . '-' . $kode_target_aktifitas;


        $opdTagOtsus = OpdTagOtsus::where('kode_unik_opd_tag_otsus', $kode_unik_opd_tag_otsus)->first();
        preg_match_all('/\d+(?=~)/', $data['lokasi'], $matchLokasi);
        $id_lokasi = $matchLokasi[0]; // Ambil semua angka sebelum '~'

        preg_match_all('/\d+(?=~)/', $data['sumberdana'], $mathcDanaLain);
        $id_sumberdana = $mathcDanaLain[0]; // Ambil semua angka sebelum '~'
        $getSumberdana = Sumberdana::find($id_sumberdana);


        preg_match_all('/\d+(?=~)/', $data['dana_lain'], $mathcDanaLain);
        $id_dana_lain = $mathcDanaLain[0]; // Ambil semua angka sebelum '~'

        $getDanaLain = Sumberdana::whereIn('id', $id_dana_lain)->get();
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
            'kode_unik_opd' => $kode_unik_opd,
            'kode_unik_opd_tag_bidang' => $kode_unik_opd . '-' . $subkegiatan->kode_bidang,
            'kode_unik_opd_tag_otsus' => $opdTagOtsus ? $opdTagOtsus->kode_unik_opd_tag_otsus : null,
            'kode_opd' => $kode_opd,
            'kode_tema' => $target_aktifitas ? $target_aktifitas['kode_tema'] : null,
            'kode_program' => $target_aktifitas ? $target_aktifitas['kode_program'] : null,
            'kode_keluaran' => $target_aktifitas ? $target_aktifitas['kode_keluaran'] : null,
            'kode_aktifitas' => $target_aktifitas ? $target_aktifitas['kode_aktifitas'] : null,
            'kode_target_aktifitas' => $target_aktifitas ? $target_aktifitas['kode_target_aktifitas'] : $data['kode_indikator'],
            'kode_subkegiatan' => $subkegiatan ? $subkegiatan['kode_subkegiatan'] : $data['kode_subkegiatan'],
            'nama_subkegiatan' => $subkegiatan ? $subkegiatan['uraian'] : null,
            'indikator_subkegiatan' => $subkegiatan ?  $subkegiatan['indikator'] : null,
            'satuan_subkegiatan' => $subkegiatan ? $subkegiatan['satuan'] : null,
            'klasifikasi_belanja' => $subkegiatan ? $subkegiatan['klasifikasi_belanja'] : null,
            'text_subkegiatan' => $subkegiatan ? $subkegiatan['kode_subkegiatan'] . ' ' . $subkegiatan['uraian'] : $data['kode_subkegiatan'] . ' ' . $data['nama_subkegiatan'],
            'sumberdana' => $getSumberdana ? $getSumberdana->uraian : null,
            'penerima_manfaat' => strtolower($data['penerima_manfaat']),
            'jenis_layanan' => strtolower($data['jenis_layanan']),
            'jenis_kegiatan' => strtolower($data['jenis_kegiatan']),
            'dana_lain' => $dana_lain,
            'lokus' => $lokus,
            'vol_subkeg' => clearFloatFormat($data['volume_subkegiatan']) ? clearFloatFormat($data['volume_subkegiatan']) : $data['volume_subkegiatan'],
            'anggaran' => clearFloatFormat($data['anggaran']) ? clearFloatFormat($data['anggaran']) : $data['anggaran'],
            'mulai' => $data['mulai'],
            'selesai' => $data['selesai'],
            'keterangan' => $data['keterangan'] ? $data['keterangan'] : null,
            'ppsb' => strtolower($data['ppsb']),
            'multiyears' => strtolower($data['multiyears']),
            'koordinat' => $data['koordinat'],
            'catatan' => array_key_exists('catatan', $data) ? $data['catatan'] : null,
            'tahun' => $data['tahun'],
        ];
        return $newData;
    }

    public function rules(): array
    {
        // return [];
        return [
            'tahun' => 'required',
            '*.tahun' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== session()->get('tahun')) {
                        $fail($attribute . ' tidak sesuai dengan sesi.');
                    }
                }
            ],

            'kode_opd' => 'required',
            '*.kode_opd' => 'required',

            '*.kode_target_aktifitas' => [
                'exists:b5_target_aktifitas_utama_otsuses,kode_target_aktifitas',
            ],

            'kode_unik_opd_tag_otsus' => 'required',
            '*.kode_unik_opd_tag_otsus' => [
                'required',
                'exists:opd_tag_otsuses,kode_unik_opd_tag_otsus',
            ],

            'kode_subkegiatan' => 'required',
            '*.kode_subkegiatan' => [
                'required',
                'exists:a5_subkegiatans,kode_subkegiatan',
            ],

            'penerima_manfaat' => 'required|in:oap,umum',
            'jenis_layanan' => 'required|in:terkait,pendukung',
            'jenis_kegiatan' => 'required|in:fisik,nonfisik',
            'dana_lain' => 'required',
            'lokus' => 'required',

            'vol_subkeg' => 'required|numeric',

            'anggaran' => 'required|numeric',

            'mulai' => 'required|date_format:Y-m-d',

            'selesai' => 'required|date_format:Y-m-d',

            'ppsb' => 'required|in:ya,tidak',

            'multiyears' => 'required|in:ya,tidak',

            'koordinat' => 'required_if:jenis_kegiatan,fisik'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'tahun.required' => 'tidak boleh kosong!',
            'tahun.custom' => 'Tahun anggaran tidak sesuai dengan sesi tahun anggran!',

            'kode_opd.required' => 'tidak boleh kosong!',
            'kode_opd.exists' => 'Perangkat Daerah tidak ditemukan!',

            'kode_subkegiatan.required' => 'tidak boleh kosong!',
            'kode_subkegiatan.exists' => 'Kode Sub kegiatan tidak ditemukan!',

            'kode_unik_opd_tag_otsus.required' => 'tidak boleh kosong!',
            'kode_unik_opd_tag_otsus.exists' => 'Data indikator opd tidak ditemukan atau belum ditambahkan!',

            'kode_target_aktifitas.exists' => 'Target Aktifitas Utama tidak ditemukan!',

            'penerima_manfaat.required' => 'tidak boleh kosong!',
            'penerima_manfaat.in' => 'nilai hanya boleh "oap" atau "umum"!',

            'jenis_layanan.required' => 'tidak boleh kosong!',
            'jenis_layanan.in' => 'nilai hanya boleh "terkait" atau "pendukung"!',

            'jenis_kegiatan.required' => 'tidak boleh kosong!',
            'jenis_kegiatan.in' => 'nilai hanya boleh "fisk" atau "nonfisik"!',

            'dana_lain.required' => 'Sumber dana kemungkinan kosong atau tidak ditemukan. contoh format sumber dana yang benar: 1~PAD,  jika lebih dari satu pisahkan dengan tanda |, contoh: 1~PAD|2~DAU',

            'lokus.required' => 'Lokasi Kegiatan kemungkinan kosong atau tidak ditemukan. contoh format Lokasi Kegiatan yang benar: 1~Dadat,  jika lebih dari satu pisahkan dengan tanda |, contoh: 1~Dadat|2~Gesa Baru',

            'vol_subkeg.required' => 'tidak boleh kosong!',
            'vol_subkeg.numeric' => 'hanya boleh berupa angka!',

            'anggaran.required' => 'tidak boleh kosong!',
            'anggaran.numeric' => 'hanya boleh berupa angka!',

            'mulai.required' => 'tidak boleh kosong!',
            'mulai.date_format' => 'format tanggal salah. contoh yang benar 2025-01-01 (tahun-bulan-tanggal)!',

            'selesai.required' => 'tidak boleh kosong!',
            'selesai.date_format' => 'format tanggal salah. contoh yang benar 2025-11-01 (tahun-bulan-tanggal)!',

            'ppsb.required' => 'tidak boleh kosong!',
            'ppsb.in' => 'nilai hanya boleh "ya" atau "tidak"!',

            'multiyears.required' => 'tidak boleh kosong!',
            'multiyears.in' => 'nilai hanya boleh "ya" atau "tidak"!',

            'koordinat.required_if' => 'kegiatan fisik wajib memasukan koordinat!',
        ];
    }
}
