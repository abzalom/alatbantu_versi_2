<?php

namespace App\Http\Controllers\Api;

use App\Models\Data\Opd;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Support\Facades\Validator;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;

class ApiOpdIndikatorController extends Controller
{
    public function indikator_opd(Request $request)
    {
        if (!$request->has('tahun')) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan!.',
                'alert' => 'error',
            ], 500);
        }
        $data = OpdTagOtsus::with([
            'opd',
            'target_aktifitas'
        ])->where('tahun', $request->tahun);

        if ($request->has('id')) {
            $data = $data->where('id', $request->id);
        }
        if ($request->has('kode_unik_opd_tag_otsus')) {
            $data = $data->where('id', $request->id);
        }
        if ($request->has('kode_opd')) {
            $data = $data->where('id', $request->id);
        }

        $data = $data->get();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan!.',
                'alert' => 'error',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data Found ' . $data->count(),
            'alert' => 'success',
            'data' => $data,
        ], 200);
    }


    public function indikator_add_opd(Request $request)
    {
        $validator = Validator::make(
            $request->only('id_opd', 'id_target_aktifitas', 'volume_indikator', 'sumberdana_indikator'),
            [
                'id_opd' => 'required|exists:opds,id',
                'id_target_aktifitas' => 'required|exists:b5_target_aktifitas_utama_otsuses,id',
                'volume_indikator' => [
                    Rule::requiredIf(function () use ($request) {
                        $targetAktifitas = B5TargetAktifitasUtamaOtsus::find($request->id_target_aktifitas);
                        $expKode = explode('.', $targetAktifitas->kode_target_aktifitas);
                        return $expKode[1] !== 'X' ?? false;
                    }),
                    'nullable',
                    'numeric'
                ],
                'sumberdana_indikator' => [
                    'required',
                    Rule::in(['otsus 1%', 'otsus 1,25%', 'DTI'])
                ],
            ],
            [
                'id_opd.required' => 'Perangkat Daerah tidak boleh kosong!',
                'id_opd.exists' => 'Terjadi kesalahan! Perangkat Daerah tidak ditemukan!',
                'id_target_aktifitas.required' => 'Target Aktifitas Utama tidak boleh kosong!',
                'id_target_aktifitas.exists' => 'Terjadi kesalahan! Target Aktifitas Utama tidak ditemukan!',
                'volume_indikator.numeric' => 'Volume harus berupa anggaka!',
                'sumberdana_indikator.required' => 'Sumber Dana tidak boleh kosong!',
                'sumberdana_indikator.in' => 'Sumber Dana tidak valid!',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. data gagal di simpan!',
                'alert' => 'danger',
                'errors' => $validator->errors()
            ], 422);
        }
        $opd = Opd::withoutGlobalScopes()->with('tag_otsus')->find($request->id_opd);
        $target = B5TargetAktifitasUtamaOtsus::find($request->id_target_aktifitas);

        $exists_validator = Validator::make(
            [
                'id_target_aktifitas' => session()->get('tahun') . '-' . $opd->kode_opd . '-' . $target->kode_target_aktifitas,
            ],
            [
                'id_target_aktifitas' => 'unique:opd_tag_otsuses,kode_unik_opd_tag_otsus',
            ],
            [
                'id_target_aktifitas.unique' => 'Target Aktifitas Utama sudah ada!',
            ]
        );

        if ($exists_validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan duplikasi. data gagal di simpan!',
                'alert' => 'danger',
                'errors' => $exists_validator->errors()
            ], 422);
        }

        $unik_dana = null;

        if ($request->sumberdana_indikator === 'otsus 1%') {
            $unik_dana = 'bg';
        }

        if ($request->sumberdana_indikator === 'otsus 1,25%') {
            $unik_dana = 'sg';
        }

        if ($request->sumberdana_indikator === 'DTI') {
            $unik_dana = 'dti';
        }

        $data = [
            'kode_unik_opd_tag_otsus' => $opd->kode_unik_opd . '-' . $target->kode_target_aktifitas . '-' . $unik_dana,
            'kode_unik_opd' => $opd->kode_unik_opd,
            'kode_opd' => $opd->kode_opd,
            'kode_tema' => $target->kode_tema,
            'kode_program' => $target->kode_program,
            'kode_keluaran' => $target->kode_keluaran,
            'kode_aktifitas' => $target->kode_aktifitas,
            'kode_target_aktifitas' => $target->kode_target_aktifitas,
            'satuan' => $target->satuan,
            'volume' => $request->volume_indikator,
            'sumberdana' => $request->sumberdana_indikator,
            'catatan' => $request->has('catatan') ? $request->catatan : null,
            'tahun' => $request->tahun,
        ];

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Data berhasil di simpan!',
        //     'alert' => 'success',
        //     'data' => $data,
        // ], 200);

        try {
            DB::beginTransaction();
            $create = OpdTagOtsus::create($data);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di simpan!',
                'alert' => 'success',
                'data' => Opd::withoutGlobalScopes()->with([
                    'tag_otsus' => fn($q) => $q->where('id', $create->id),
                    'tag_otsus.target_aktifitas',
                ])->find($request->id_opd),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan! data gagal di simpan.',
                'alert' => 'success',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function indikator_update_opd(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'id_indikator' => 'required|exists:opd_tag_otsuses,id',
                'volume_indikator' => 'required|numeric',
                'sumberdana_indikator' => [
                    'required',
                    Rule::in(['Otsus 1%', 'Otsus 1,25%', 'DTI'])
                ],
            ],
            [
                'id_indikator.required' => 'Indikator tidak boleh kosong!',
                'id_indikator.exists' => 'Indikator tidak ditemukan!',

                'volume_indikator.required' => 'Volume tidak boleh kosong!',
                'volume_indikator.numeric' => 'Volume harus berupa anggka!',

                'sumberdana_indikator.required' => 'Sumberdana tidak boleh kosong!',
                'sumberdana_indikator.in' => 'Sumberdana tidak benar!',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. data gagal di simpan!',
                'alert' => 'danger',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            $data = OpdTagOtsus::with([
                'opd',
                'target_aktifitas',
                'raps'
            ])->find($request->id_indikator);
            $data->volume = $request->volume_indikator;
            $data->sumberdana = $request->sumberdana_indikator;
            // foreach ($data->raps as $rap) {
            //     $rap->sumberdana = $data->sumberdana;
            //     $rap->save();
            // }
            $data->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di simpan!',
                'alert' => 'success',
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. data gagal di simpan!',
                'alert' => 'danger',
                'errors' => $th
            ], 422);
        }
    }
}
