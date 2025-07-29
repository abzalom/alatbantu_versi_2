<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Data\Sumberdana;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use App\Models\Rap\RapOtsus;
use App\Models\Scopes\TahunScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiIndikatorOtsus extends Controller
{
    public function api_indikator_tema(Request $request)
    {
        $data = DB::table('b1_tema_otsuses');
        if ($request->has('id')) {
            $data = $data->where('id', $request->id);
        }
        if ($request->has('kode_tema')) {
            $data = $data->where('kode_tema', $request->kode_tema);
        }
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found!'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data Found ' . $data->count(),
            'data' => $data->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode_tema' => $item->kode_tema,
                    'uraian' => $item->uraian,
                    'text' => $item->kode_tema . '. ' . $item->uraian,
                ];
            }),
        ]);
    }

    public function api_indikator_program(Request $request)
    {
        $data = DB::table('b2_program_prioritas_otsuses');
        if ($request->has('id')) {
            $data = $data->where('id', $request->id);
        }
        if ($request->has('kode_tema')) {
            $data = $data->where('kode_tema', $request->kode_tema);
        }
        if ($request->has('kode_program')) {
            $data = $data->where('kode_program', $request->kode_program);
        }
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found!'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data Found ' . $data->count(),
            'data' => $data->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode_tema' => $item->kode_tema,
                    'kode_program' => $item->kode_program,
                    'uraian' => $item->uraian,
                    'text' => $item->kode_program . '. ' . $item->uraian,
                ];
            }),
        ]);
    }

    public function api_indikator_keluaran(Request $request)
    {
        $data = DB::table('b3_target_keluaran_strategis_otsuses');
        if ($request->has('id')) {
            $data = $data->where('id', $request->id);
        }
        if ($request->has('kode_tema')) {
            $data = $data->where('kode_tema', $request->kode_tema);
        }
        if ($request->has('kode_program')) {
            $data = $data->where('kode_program', $request->kode_program);
        }
        if ($request->has('kode_keuaran')) {
            $data = $data->where('kode_keuaran', $request->kode_keuaran);
        }
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found!'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data Found ' . $data->count(),
            'data' => $data->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode_tema' => $item->kode_tema,
                    'kode_program' => $item->kode_program,
                    'kode_keluaran' => $item->kode_keluaran,
                    'uraian' => $item->uraian,
                    'satuan' => $item->satuan,
                    'text' => $item->kode_keluaran . '. ' . $item->uraian,
                ];
            }),
        ]);
    }

    public function api_indikator_aktifitas_utama(Request $request)
    {
        $data = DB::table('b4_aktifitas_utama_otsuses');
        if ($request->has('id')) {
            $data = $data->where('id', $request->id);
        }
        if ($request->has('kode_tema')) {
            $data = $data->where('kode_tema', $request->kode_tema);
        }
        if ($request->has('kode_program')) {
            $data = $data->where('kode_program', $request->kode_program);
        }
        if ($request->has('kode_keluaran')) {
            $data = $data->where('kode_keluaran', $request->kode_keluaran);
        }
        if ($request->has('kode_aktifitas')) {
            $data = $data->where('kode_aktifitas', $request->kode_aktifitas);
        }
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found!'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data Found ' . $data->count(),
            'data' => $data->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode_tema' => $item->kode_tema,
                    'kode_program' => $item->kode_program,
                    'kode_keluaran' => $item->kode_keluaran,
                    'kode_aktifitas' => $item->kode_aktifitas,
                    'uraian' => $item->uraian,
                    'text' => $item->kode_aktifitas . '. ' . $item->uraian,
                ];
            }),
        ]);
    }

    public function api_indikator_target_aktifitas_utama(Request $request)
    {
        $data = DB::table('b5_target_aktifitas_utama_otsuses');
        if ($request->has('id')) {
            $data = $data->where('id', $request->id);
        }
        if ($request->has('kode_tema')) {
            $data = $data->where('kode_tema', $request->kode_tema);
        }
        if ($request->has('kode_program')) {
            $data = $data->where('kode_program', $request->kode_program);
        }
        if ($request->has('kode_keuaran')) {
            $data = $data->where('kode_keuaran', $request->kode_keuaran);
        }
        if ($request->has('kode_aktifitas')) {
            $data = $data->where('kode_aktifitas', $request->kode_aktifitas);
        }
        if ($request->has('kode_target_aktifitas')) {
            $data = $data->where('kode_target_aktifitas', $request->kode_target_aktifitas);
        }
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found!'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data Found ' . $data->count(),
            'data' => $data->get()->map(function ($item) {
                $text = $item->kode_target_aktifitas . ' ' . $item->uraian;
                return [
                    'id' => $item->id,
                    'kode_tema' => $item->kode_tema,
                    'kode_program' => $item->kode_program,
                    'kode_keluaran' => $item->kode_keluaran,
                    'kode_aktifitas' => $item->kode_aktifitas,
                    'kode_target_aktifitas' => $item->kode_target_aktifitas,
                    'uraian' => $item->uraian,
                    'satuan' => $item->satuan,
                    // 'volume' => $item->volume,
                    // 'sumberdana' => $item->sumberdana,
                    'tahun' => $item->tahun,
                    'text' => $text,
                ];
            }),
        ]);
    }

    public function api_edit_volume_indikator_target_aktifitas_utama(Request $request)
    {
        $validator = Validator::make(
            $request->only(['id']),
            [
                'id' => 'required|exists:b5_target_aktifitas_utama_otsuses,id',
            ],
            [
                'id.required' => 'Terjadi kesalahan! Silahkan coba lagi!',
                'id.exists' => 'Target aktifitas utama tidak boleh kosong!',
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

        if ($request->volume) {
            $sumberdana_validation = Validator::make(
                $request->only(['sumberdana']),
                [
                    'sumberdana' => 'required|exists:sumberdanas,uraian',
                ],
                [
                    'sumberdana.required' => 'Sumber dana tidak boleh kosong!',
                    'sumberdana.exists' => 'Sumber dana tidak ditemukan!',
                ]
            );

            if ($sumberdana_validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan. data gagal di simpan!',
                    'alert' => 'danger',
                    'errors' => $sumberdana_validation->errors()
                ], 422);
            }
        }


        try {
            DB::beginTransaction();
            $data = B5TargetAktifitasUtamaOtsus::find($request->id);
            $data->volume = $request->volume;
            $data->sumberdana = $request->sumberdana;

            $raps = RapOtsus::withoutGlobalScope(TahunScope::class)->where('kode_target_aktifitas', $data->kode_target_aktifitas)->get();

            foreach ($raps as $rap) {
                $rap->sumberdana = $data->sumberdana;
                $rap->save();
            }

            $data->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di simpan!',
                'alert' => 'success',
                'data' => [
                    'id' => $data->id,
                    'kode_tema' => $data->kode_tema,
                    'kode_program' => $data->kode_program,
                    'kode_keluaran' => $data->kode_keluaran,
                    'kode_aktifitas' => $data->kode_aktifitas,
                    'uraian' => $data->uraian,
                    'satuan' => $data->satuan,
                    'volume' => $data->volume,
                    'sumberdana' => $data->sumberdana,
                    'tahun' => $data->tahun,
                    'text' => $data->kode_aktifitas . '. ' . $data->uraian,
                ],
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

    public function api_reset_volume_indikator_target_aktifitas_utama(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = B5TargetAktifitasUtamaOtsus::with(['raps'])->find($request->id);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan!',
                    'alert' => 'danger',
                ], 404);
            }
            $data->volume = null;
            $data->sumberdana = null;

            foreach ($data->raps as $rap) {
                $rap->sumberdana = null;
                $rap->save();
            }

            $data->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Volume dan Sumber Dana berhasil di reset!',
                'alert' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan!',
                'alert' => 'danger',
            ], 500);
        }
    }
}
