<?php

namespace App\Http\Controllers;

use App\Enums\PangkatEnums;
use App\Models\Data\KepalaOpd;
use App\Models\Data\Opd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiOpdController extends Controller
{
    public function api_opd(Request $request)
    {
        $data = Opd::with(['kepala']);
        if ($request->has('id')) {
            $data = $data->where('id', $request->id);
        }
        if ($request->has('kode_opd')) {
            $data = $data->where('kode_opd', $request->kode_opd);
        }
        if ($request->has('kode_unik_opd')) {
            $data = $data->where('kode_unik_opd', $request->kode_unik_opd);
        }
        if ($request->has('tahun')) {
            $data = $data->where('tahun', $request->tahun);
        }
        $data = $data->get();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => "Data Not Found!",
                'alert' => 'error',
                'data' => $data,
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => "Data Found " . $data->count() . "!",
            'alert' => 'success',
            'data' => $data,
        ]);
    }

    public function api_subkegiatan_opd(Request $request)
    {
        $data = DB::table('opds');
        if ($request->has('id')) {
            $data = $data->where('opds.id', $request->id);
        }
        if ($request->has('kode_opd')) {
            $data = $data->where('opds.kode_opd', $request->kode_opd);
        }
        $data = $data
            ->join('opd_tag_bidangs as tagging', 'opds.kode_opd', '=', 'tagging.kode_opd')
            ->join('a5_subkegiatans as subkegiatans', 'tagging.kode_bidang', '=', 'subkegiatans.kode_bidang')
            ->get();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => "Data Not Found!",
                'alert' => 'error',
                'data' => $data,
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => "Data Found " . $data->count() . "!",
            'alert' => 'success',
            'data' => $data,
        ]);
    }

    public function api_save_kepala_opd(Request $request)
    {
        $opd = Opd::find($request->id_opd);
        if (!$opd) {
            return response()->json([
                'success' => false,
                'message' => "OPD Not Found!",
                'alert' => 'danger',
                'data' => null,
            ], 404);
        }
        $validator = Validator::make(
            $request->all(),
            [
                'nip' => 'required|string|max:20|unique:kepala_opds,nip',
                'nama' => 'required|string',
                'pangkat' => 'required|in:' . implode(',', array_column(PangkatEnums::cases(), 'value')),
                'jabatan' => 'required|in:kepala,direktur,kepala_unit',
                'status_jabatan' => 'required|in:plt,definitif',
            ],
            [
                'nip.required' => 'NIP tidak boleh kosong!',
                'nip.unique' => 'NIP sudah terdaftar!',
                'nama.required' => 'Nama tidak boleh kosong!',
                'pangkat.required' => 'Pangkat tidak boleh kosong!',
                'pangkat.in' => 'Pangkat tidak valid!',
                'jabatan.required' => 'Jabatan tidak boleh kosong!',
                'jabatan.in' => 'Jabatan tidak valid!',
                'status_jabatan.required' => 'Status Jabatan tidak boleh kosong!',
                'status_jabatan.in' => 'Status Jabatan tidak valid!',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => "Validation Failed!",
                'alert' => 'danger',
                'data' => $validator->errors(),
            ], 422);
        }

        // Simpan data kepala OPD
        $kepalaOpd = new KepalaOpd();
        $kepalaOpd->nip = $request->nip;
        $kepalaOpd->nama = $request->nama;
        $kepalaOpd->pangkat = $request->pangkat;
        $kepalaOpd->jabatan = $request->jabatan;
        $kepalaOpd->status_jabatan = $request->status_jabatan;
        $kepalaOpd->kode_unik_opd = $opd->kode_unik_opd;
        $kepalaOpd->tahun = $request->token_tahun;
        $kepalaOpd->save();

        return response()->json([
            'success' => true,
            'message' => "Kepala OPD Berhasil Disimpan!",
            'alert' => 'success',
            'data' => $kepalaOpd,
        ]);
    }

    public function api_update_kepala_opd_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => "Validation Failed!",
                'alert' => 'danger',
                'data' => $validator->errors(),
            ], 422);
        }

        $kepalaOpd = KepalaOpd::find($request->nip);
        if (!$kepalaOpd) {
            return response()->json([
                'success' => false,
                'message' => "Kepala OPD Not Found!",
                'alert' => 'danger',
                'data' => null,
            ], 404);
        }

        // atur status kepala yang lainnya menjadi false pada opd yang sama dan jika gagal kembalikan error
        if (!$request->status) {
            $allKepalaOpd = KepalaOpd::where('kode_unik_opd', $kepalaOpd->kode_unik_opd)->get();
            foreach ($allKepalaOpd as $key => $value) {
                $value->status = false;
                $value->save();
            }
        }
        $kepalaOpd->status = $kepalaOpd->status ? false : true;
        $kepalaOpd->save();

        return response()->json([
            'success' => true,
            'message' => "Kepala OPD Status Updated Successfully!",
            'alert' => 'success',
            'data' => $kepalaOpd,
        ]);
    }
}
