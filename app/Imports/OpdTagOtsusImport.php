<?php

namespace App\Imports;

use App\Models\Data\Opd;
use App\Models\Data\Sumberdana;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class OpdTagOtsusImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows, WithCalculatedFormulas
{

    use Importable, SkipsFailures;

    public function model($row)
    {
        if (!empty($row)) {
            // dump($row);
            $data = collect($row)->except('text_target_aktifitas')->toArray();
            Log::info($data); // Logging untuk debugging
            return OpdTagOtsus::firstOrCreate(
                [
                    'kode_unik_opd_tag_otsus' => $data['kode_unik_opd_tag_otsus'],
                ],
                [
                    'kode_unik_opd' => $data['kode_unik_opd'],
                    'kode_opd' => $data['kode_opd'],
                    'kode_tema' => $data['kode_tema'],
                    'kode_program' => $data['kode_program'],
                    'kode_keluaran' => $data['kode_keluaran'],
                    'kode_aktifitas' => $data['kode_aktifitas'],
                    'kode_target_aktifitas' => $data['kode_target_aktifitas'],
                    'satuan' => $data['satuan'],
                    'volume' => $data['volume'],
                    'sumberdana' => $data['sumberdana'],
                    'tahun' => $data['tahun'],
                ]
            );
        }
    }


    public function prepareForValidation($data, $index)
    {
        $tahun = $data['tahun'] ? $data['tahun'] : session()->get('tahun');
        $opd = Opd::where('kode_unik_opd', $tahun . '-' . $data['kode_skpd'])->first();
        $target_aktifitas = B5TargetAktifitasUtamaOtsus::where('kode_target_aktifitas', $data['kode_indikator'])->first();
        $kode_unik_opd_tag_otsus = $opd ? $opd->kode_unik_opd . '-' . $target_aktifitas->kode_target_aktifitas : null;
        $opdTagOtsus = OpdTagOtsus::where('kode_unik_opd_tag_otsus', $kode_unik_opd_tag_otsus)->exists();
        if (!$opdTagOtsus) {
            $id_sumberdana = explode('~', $data['sumberdana'])[0]; // Ambil semua angka sebelum '~'
            $sumberdana = Sumberdana::find($id_sumberdana);
            return [
                'kode_unik_opd' => $opd ? $opd->kode_unik_opd : null,
                'kode_unik_opd_tag_otsus' => $kode_unik_opd_tag_otsus,
                'kode_opd' =>  $opd ?  $opd->kode_opd : null,
                'kode_tema' => $target_aktifitas ? $target_aktifitas->kode_tema : null,
                'kode_program' => $target_aktifitas ? $target_aktifitas->kode_program : null,
                'kode_keluaran' => $target_aktifitas ? $target_aktifitas->kode_keluaran : null,
                'kode_aktifitas' => $target_aktifitas ? $target_aktifitas->kode_aktifitas : null,
                'kode_target_aktifitas' => $target_aktifitas ? $target_aktifitas->kode_target_aktifitas : null,
                'satuan' => $target_aktifitas ? $target_aktifitas->satuan : null,
                'volume' => clearFloatFormat($data['volume']) === 0 ? null : $data['volume'],
                'sumberdana' => $sumberdana ? $sumberdana->uraian : $data['sumberdana'],
                'tahun' => $tahun,
                'text_target_aktifitas' => $target_aktifitas ? $target_aktifitas->kode_aktifitas . ' ' . $target_aktifitas->kode_aktifitas : $data['nama_indikator'],
            ];
        } else {
            return [];
        }
    }

    public function rules(): array
    {
        return [
            'tahun' => [
                'required',
            ],

            'kode_unik_opd' => [
                'required',
                'exists:opds,kode_unik_opd'
            ],

            'kode_target_aktifitas' => [
                'required',
                'exists:b5_target_aktifitas_utama_otsuses,kode_target_aktifitas'
            ],

            'sumberdana' => [
                'required',
                'exists:sumberdanas,uraian',
            ],

            'volume' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) {
                    $target = B5TargetAktifitasUtamaOtsus::where('kode_target_aktifitas', request()->input('kode_target_aktifitas'))->first();
                    if ($target) {
                        $kodeParts = explode('.', $target->kode_target_aktifitas);
                        if (isset($kodeParts[1]) && $kodeParts[1] !== 'X' && empty($value)) {
                            $fail("Kolom $attribute wajib diisi karena target aktifitas bukan 'X'.");
                        }
                    }
                }
            ],

        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'tahun.required' => 'tidak boleh kosong!',

            'kode_unik_opd.required' => 'tidak boleh kosong!',
            'kode_unik_opd.exists' => 'OPD tidak ditemukan!',

            'kode_target_aktifitas.required' => 'tidak boleh kosong!',
            'kode_target_aktifitas.exists' => 'Indikator tidak ditemukan!',

            'volume.required' => 'tidak boleh kosong!',
            'volume.numeric' => 'Volume hanya boleh berupa anggka!',

            'sumberdana.required' => 'tidak boleh kosong!',
            'sumberdana.exists' => 'Sumberdana tidak ditemukan! <small><a href="/ref/data" target="_blank">lihat referensi</a></small>',
        ];
    }
}
