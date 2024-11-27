<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Data\Lokus;
use App\Models\Rap\RapOtsus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ApiUserRapController extends Controller
{
    public function user_update_rap(Request $request)
    {
        $validator = Validator::make(
            $request->except('_token'),
            [
                'id_rap' => 'required|exists:rap_otsuses,id',
                'vol_subkeg' => 'required|numeric',
                'lokus' => 'required|exists:lokuses,id',
                'koordinat' => [
                    Rule::requiredIf(function () use ($request) {
                        $item = DB::table('rap_otsuses')->where('id', $request->id_rap)->first();
                        return $item && $item->jenis_kegiatan === 'fisik';
                    }),
                    'nullable',
                ],
                'keterangan' => 'required',
            ],
            [
                'id_rap.required' => 'sub kegiatan tidak boleh kosong!',
                'id_rap.exists' => 'Terjadi kesalahan! sub kegiatan tidak ditemukan!',
                'vol_subkeg.required' => 'Target tidak boleh kosong!',
                'vol_subkeg.numeric' => 'Target hanya boleh berupa angka [0~9] dan gunakan (.) sebagai koma!',
                'lokus.required' => 'Lokasi Kegiatan tidak boleh kosong!',
                'lokus.exists' => 'Terjadi kesalahan! Lokasi tidak ditemukan!',
                'koordinat.required' => 'Kegiatan fisik wajib memasukan koordinat lokasi kegiatan!',
                'keterangan.required' => 'Keterangan tidak boleh kosong!',
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
            $lokus = Lokus::whereIn('id', $request->lokus)->get(['id', 'kecamatan', 'kampung'])->toJson();
            $rap = RapOtsus::find($request->id_rap);
            $rap->vol_subkeg = $request->vol_subkeg;
            $rap->lokus = $lokus;
            $rap->koordinat = $request->koordinat;
            $rap->keterangan = $request->keterangan;
            $rap->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di simpan!',
                'alert' => 'success',
                'data' => $rap,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan! data gagal di simpan!',
                'alert' => 'error',
            ], 200);
        }
    }
}
