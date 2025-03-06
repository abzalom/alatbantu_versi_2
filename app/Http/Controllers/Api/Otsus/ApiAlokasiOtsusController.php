<?php

namespace App\Http\Controllers\Api\Otsus;

use App\Http\Controllers\Controller;
use App\Models\Otsus\DanaAlokasiOtsus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ApiAlokasiOtsusController extends Controller
{
    public function api_alokasi_dana(Request $request)
    {
        $data = DB::table('dana_alokasi_otsuses');
        if ($request->has('id')) {
            $data = $data->where('id', $request->id);
        }
        $data = $data->get();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan!',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function api_insert_alokasi_dana(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tahun' => 'unique:dana_alokasi_otsuses,tahun',
                'alokasi_bg' => 'nullable|numeric',
                'alokasi_sg' => 'nullable|numeric',
                'alokasi_dti' => 'nullable|numeric',
                'status' => 'required|in:realisasi,indikatif,perkiraan',
            ],
            [
                'tahun.unique' => 'Tahun anggaran sudah ada!',
                'alokasi_bg.numeric' => 'Alokasi BG 1% harus berupa angka yang benar!',
                'alokasi_sg.numeric' => 'AlokasiSG 1,25% harus berupa angka yang benar!',
                'alokasi_dti.numeric' => 'Alokasi DTI harus berupa angka yang benar!',
                'status.required' => 'Status Alokasi tidak boleh kosong!',
                'status.in' => 'Status Alokasi hanya boleh Realisasi/ Indikatif/ Perkiraan!',
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

        if ($request->alokasi_bg || $request->alokasi_sg || $request->alokasi_dti) {
            try {
                DB::beginTransaction();
                // Melakukan penyimpanan data ke database
                $data = DanaAlokasiOtsus::create([
                    'tahun' => $request->tahun,
                    'alokasi_bg' => $request->alokasi_bg ?? 0,
                    'alokasi_sg' => $request->alokasi_sg ?? 0,
                    'alokasi_dti' => $request->alokasi_dti ?? 0,
                    'status' => $request->status,
                ]);

                // Komit transaksi setelah data berhasil di simpan
                DB::commit();

                // Mengembalikan response JSON setelah commit
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil di simpan!',
                    'alert' => 'success',
                    'data' => [
                        'id' => $data->id,
                        'tahun' => $data->tahun,
                        'alokasi_bg' => $data->alokasi_bg,
                        'alokasi_sg' => $data->alokasi_sg,
                        'alokasi_dti' => $data->alokasi_dti,
                        'status' => $data->status,
                        'total_otsus' => DB::table('dana_alokasi_otsuses')
                            ->select(DB::raw('SUM(alokasi_bg + alokasi_sg + alokasi_dti) as total'))
                            ->first()->total
                    ],
                ], 200);
            } catch (\Throwable $th) {
                // Rollback transaksi jika terjadi kesalahan
                DB::rollBack();

                // Mengembalikan response JSON dengan pesan error
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan, gagal menyimpan data!',
                    'alert' => 'danger',
                    'error' => $th->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada data yang terinput!',
            'alert' => 'info',
        ], 200);
    }

    public function api_update_alokasi_dana(Request $request)
    {
        $data = null;
        if ($request->has('id')) {
            $data = DanaAlokasiOtsus::find($request->id);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'alokasi_bg' => 'nullable|numeric',
                'alokasi_sg' => 'nullable|numeric',
                'alokasi_dti' => 'nullable|numeric',
                'status' => 'required|in:realisasi,indikatif,perkiraan',
            ],
            [
                'alokasi_bg.numeric' => 'Alokasi BG 1% harus berupa angka yang benar!',
                'alokasi_sg.numeric' => 'AlokasiSG 1,25% harus berupa angka yang benar!',
                'alokasi_dti.numeric' => 'Alokasi DTI harus berupa angka yang benar!',
                'status.required' => 'Status Alokasi tidak boleh kosong!',
                'status.in' => 'Status Alokasi hanya boleh Realisasi/ Indikatif/ Perkiraan!',
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

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan!',
                'alert' => 'danger',
            ], 404);
        }
        $data->alokasi_bg = $request->alokasi_bg ?? 0;
        $data->alokasi_sg = $request->alokasi_sg ?? 0;
        $data->alokasi_dti = $request->alokasi_dti ?? 0;
        $data->status = $request->status ?? 0;
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate!',
            'alert' => 'success',
            'data' => [
                'id' => $data->id,
                'tahun' => $data->tahun,
                'alokasi_bg' => $data->alokasi_bg,
                'alokasi_sg' => $data->alokasi_sg,
                'alokasi_dti' => $data->alokasi_dti,
                'status' => $data->status,
                'total_otsus' => DB::table('dana_alokasi_otsuses')
                    ->select(DB::raw('SUM(alokasi_bg + alokasi_sg + alokasi_dti) as total'))
                    ->first()->total
            ],
        ], 200);
    }

    public function api_delete_alokasi_dana(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = DanaAlokasiOtsus::find($request->id);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan!',
                    'alert' => 'danger',
                ], 404);
            }

            $data->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus!',
                'alert' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Data gagal dihapus!',
                'alert' => 'danger',
            ], 500);
        }
    }
}
