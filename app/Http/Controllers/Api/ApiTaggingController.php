<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiTaggingController extends Controller
{
    public function indikator_target_aktifitas_tag_rap(Request $request)
    {
        $data = new B5TargetAktifitasUtamaOtsus();
        $data = $data->with('raps');
        // $data = B5TargetAktifitasUtamaOtsus::with('raps');
        if ($request->has('kode_opd')) {
            $data = $data->with([
                'raps' => fn($q) => $q->where('kode_opd', $request->kode_opd)
            ]);
        }
        if ($request->has('id')) {
            $data = $data->with('raps')->where('id', $request->id);
        }
        if ($request->has('kode_target_aktifitas')) {
            $data = $data->with('raps')->where('kode_target_aktifitas', $request->kode_target_aktifitas);
        }
        $data = $data->get();

        return $data;
    }

    public function new_tagging_opd_otsus(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'id_target_aktifitas' => 'required|exists:b5_target_aktifitas_utama_otsuses,id',
                'opd' => 'required|exists:opds,id',
                'volume' => 'required|numeric',
                'sumberdana' => 'required|in:"Otsus 1,25%","Otsus 1%","DTI"',
                'tahun' => 'required|numeric',
            ],
            [
                'id_target_aktifitas.required' => 'Target aktifitas utama tidak boleh kosong!',
                'id_target_aktifitas.exists' => 'Target aktifitas utama tidak ditemukan!',
                'opd.required' => 'Perangkat daerah tidak boleh kosong!',
                'opd.exists' => 'Perangkat daerah tidak ditemukan!',
                'volume.required' => 'Volume tidak boleh kosong!',
                'volume.numeric' => 'Volume harus berupa angka!',
                'sumberdana.required' => 'Sumber dana tidak boleh kosong!',
                'sumberdana.in' => 'Sumber dana tidak valid!',
                'tahun.required' => 'Tahun tidak boleh kosong!',
                'tahun.numeric' => 'Tahun harus berupa angka!',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. data gagal di simpan!',
                'alert' => 'danger',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            DB::beginTransaction();
            // Menentukan alias sumber dana
            $aliasDanaMap = [
                'Otsus 1%' => 'bg',
                'Otsus 1,25%' => 'sg',
                'dti' => 'dti'
            ];
            $alias_dana = $aliasDanaMap[$request->sumberdana] ?? 'dti';

            // Mengambil data OPD & Target Aktivitas
            $opd = Opd::find($request->opd);
            $target_aktifitas = B5TargetAktifitasUtamaOtsus::find($request->id_target_aktifitas);

            // Membuat kode unik
            $kode_unik_opd_tag_otsus = "{$opd->kode_unik_opd}-{$target_aktifitas->kode_target_aktifitas}-{$alias_dana}";

            // Mencari atau membuat tag baru
            $tag = OpdTagOtsus::where('kode_unik_opd_tag_otsus', $kode_unik_opd_tag_otsus)->withCount('raps')->first();

            if ($tag) {
                $tag->update(['volume' => $request->volume]);
                $message = 'Indikator Perangkat Daerah berhasil di update';
            } else {
                $tag = OpdTagOtsus::create([
                    'kode_unik_opd' => $opd->kode_unik_opd,
                    'kode_unik_opd_tag_otsus' => $kode_unik_opd_tag_otsus,
                    'kode_opd' => $opd->kode_opd,
                    'kode_tema' => $target_aktifitas->kode_tema,
                    'kode_program' => $target_aktifitas->kode_program,
                    'kode_keluaran' => $target_aktifitas->kode_keluaran,
                    'kode_aktifitas' => $target_aktifitas->kode_aktifitas,
                    'kode_target_aktifitas' => $target_aktifitas->kode_target_aktifitas,
                    'volume' => $request->volume,
                    'satuan' => $target_aktifitas->satuan,
                    'sumberdana' => $request->sumberdana,
                    'alias_dana' => $alias_dana,
                    'tahun' => $request->tahun,
                ]);
                $message = 'Indikator Perangat Daerah berhasil ditagging!';
            }
            DB::commit();
            $countTagOpd = OpdTagOtsus::where([
                'kode_target_aktifitas' => $tag->kode_target_aktifitas,
                'tahun' => $tag->tahun
            ])->count();
            return response()->json([
                'success' => true,
                'message' => $message,
                'alert' => 'success',
                'data' => [
                    'id' => $opd->id,
                    'kode_opd' => $opd->kode_opd,
                    'nama_opd' => $opd->nama_opd,
                    'opd_text' => "{$opd->kode_opd} {$opd->nama_opd}",
                    'raps_count' => $opd->raps_count ?? 0,
                    'tag' => $tag,
                    'target_aktifitas' => $target_aktifitas->id,
                    'countTagOpd' => $countTagOpd,
                ],
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Data gagal tersimpan!',
                'alert' => 'danger',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }

    public function delete_tagging_opd_otsus(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:opd_tag_otsuses,id'
            ],
            [
                'id.required' => 'Tagging OPD belum di pilih!',
                'id.exists' => 'Data tagging tidak ditemukan!'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. data gagal di simpan!',
                'alert' => 'danger',
                'errors' => $validator->errors()
            ], 400);
        }

        $tag = OpdTagOtsus::with('raps')->find($request->id);
        $target_aktifitas = B5TargetAktifitasUtamaOtsus::where('kode_target_aktifitas', $tag->kode_target_aktifitas)->first();
        if ($tag) {
            try {
                DB::beginTransaction();
                if ($tag->raps->count() > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'RAP sudah ada. Tagging tidak dapat dihapus!',
                        'alert' => 'danger',
                    ], 403);
                }
                $dataDelete = $tag;
                $tag->delete();
                DB::commit();
                $countTagOpd = OpdTagOtsus::where([
                    'kode_target_aktifitas' => $dataDelete->kode_target_aktifitas,
                    'tahun' => $dataDelete->tahun
                ])->count();
                return response()->json([
                    'success' => true,
                    'message' => 'Tagging berhasil dihapus!',
                    'alert' => 'success',
                    'data' => $dataDelete,
                    'target_aktifitas' => $target_aktifitas->id,
                    'countTagOpd' => $countTagOpd,
                ], 200);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan!',
                    'alert' => 'warning',
                    'error' => $th->getMessage()
                ], 403);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tagging tidak ditemukan!',
                'alert' => 'danger',
            ], 404);
        }
    }

    public function list_opd_target(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:b5_target_aktifitas_utama_otsuses,id'
            ],
            [
                'id.required' => 'Terjadi kesalahan. Target Aktifitas tidak boleh kosong!',
                'id.exists' => 'Target Aktifitas Utama tidak ditemukan!',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'errors' => $validator->errors()
            ], 400);
        }

        $target_aktifitas = B5TargetAktifitasUtamaOtsus::with([
            'taggings' => fn($q) => $q->where('tahun', $request->tahun)->withCount(['raps' => fn($q) => $q->withTrashed()]),
            'taggings.opd',
        ])->find($request->id);

        $data = [];

        foreach ($target_aktifitas->taggings as $item) {
            if (!isset($data[$item->kode_unik_opd_tag_otsus])) {
                $data[$item->kode_unik_opd_tag_otsus] = [
                    'id_opd' => $item->opd->id,
                    'opd_tag_otsus' => $item->id,
                    'kode_opd' => $item->opd->kode_opd,
                    'kode_unik_opd' => $item->opd->kode_unik_opd,
                    'nama_opd' => $item->opd->nama_opd,
                    'opd_text' => $item->opd->kode_opd . ' ' . $item->opd->nama_opd,
                    'kode_unik_opd_tag_otsus' => $item->kode_unik_opd_tag_otsus,
                    'volume' => $item->volume,
                    'satuan' => $item->satuan,
                    'sumberdana' => $item->sumberdana,
                    'alias_dana' => $item->alias_dana,
                    'raps_count' => $item->raps_count,
                    'tahun' => $item->opd->tahun,
                ];
                // $data[$item->kode_unik_opd_tag_otsus] = $item->opd;
            }
        }

        $data = [
            'id_target' => $target_aktifitas->id,
            'kode_target' => $target_aktifitas->kode_target_aktifitas,
            'nama_target' => $target_aktifitas->uraian,
            'satuan_target' => $target_aktifitas->satuan,
            'tagging_opd' => array_values($data)
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
