<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiConfigTimPembahas extends Controller
{
    public function set_order_tim_pembahas_bappeda(Request $request)
    {
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Fitur ini masih dalam pengembangan.',
        //     'data' => $request->data,
        // ], 200);
        $validator = Validator::make(
            $request->all(),
            [
                'data' => 'required|array',
                'data.*.urutan' => 'required|integer',
                'data.*.tim_pembahas_id' => 'required|integer|exists:tim_pembahas,id',
                'data.*.opd_id' => 'required|integer|exists:opds,id',
            ],
            [
                'data.required' => 'Tidak ada data yang diterima.',
                'data.array' => 'Tipe data tidak valid (must be *array).',
                'data.*.urutan.required' => 'Urutan wajib diisi pada setiap item.',
                'data.*.urutan.integer' => 'Urutan harus berupa angka pada setiap item.',
                'data.*.urutan.min' => 'Urutan minimal bernilai 1 pada setiap item.',
                'data.*.tim_pembahas_id.required' => 'ID Tim Pembahas wajib diisi pada setiap item.',
                'data.*.tim_pembahas_id.integer' => 'ID Tim Pembahas harus berupa angka pada setiap item.',
                'data.*.tim_pembahas_id.exists' => 'ID Tim Pembahas harus ada di database pada setiap item.',
                'data.*.opd_id.required' => 'ID OPD wajib diisi pada setiap item.',
                'data.*.opd_id.integer' => 'ID OPD harus berupa angka pada setiap item.',
                'data.*.opd_id.exists' => 'ID OPD harus ada di database pada setiap item.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
        $validated = $validator->validated()['data'];
        foreach ($validated as $item) {
            DB::table('opd_tim_pembahas')
                ->where('tim_pembahas_id', $item['tim_pembahas_id'])
                ->where('opd_id', $item['opd_id'])
                ->update(['urutan' => $item['urutan']]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Order tim pembahas berhasil disimpan.',
            'data' => $validated,
        ], 200);
    }

    public function set_order_tim_pembahas_opd(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'data' => 'required|array',
                'data.*.urutan' => 'required|integer',
                'data.*.tim_pembahas_id' => 'required|integer|exists:tim_pembahas_opds,id',
                'data.*.opd_id' => 'required|integer|exists:opds,id',
            ],
            [
                'data.required' => 'Tidak ada data yang diterima.',
                'data.array' => 'Tipe data tidak valid (must be *array).',
                'data.*.urutan.required' => 'Urutan wajib diisi pada setiap item.',
                'data.*.urutan.integer' => 'Urutan harus berupa angka pada setiap item.',
                'data.*.urutan.min' => 'Urutan minimal bernilai 1 pada setiap item.',
                'data.*.tim_pembahas_id.required' => 'ID Tim Pembahas wajib diisi pada setiap item.',
                'data.*.tim_pembahas_id.integer' => 'ID Tim Pembahas harus berupa angka pada setiap item.',
                'data.*.tim_pembahas_id.exists' => 'ID Tim Pembahas harus ada di database pada setiap item.',
                'data.*.opd_id.required' => 'ID OPD wajib diisi pada setiap item.',
                'data.*.opd_id.integer' => 'ID OPD harus berupa angka pada setiap item.',
                'data.*.opd_id.exists' => 'ID OPD harus ada di database pada setiap item.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
        $validated = $validator->validated()['data'];
        foreach ($validated as $item) {
            DB::table('tim_pembahas_opds')
                ->where('id', $item['tim_pembahas_id'])
                ->where('opd_id', $item['opd_id'])
                ->update(['urutan' => $item['urutan']]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Order tim pembahas berhasil disimpan.',
            'data' => $validated,
        ], 200);
    }

    public function destroy_member_tim_pembahas_bappeda(Request $request)
    {
        // return response()->json([
        //     'success' => false,
        //     'message' => 'Fitur ini masih dalam pengembangan.',
        //     'alert' => 'danger',
        //     'data' => $request->all(),
        // ], 200);
        $validator = Validator::make(
            $request->all(),
            [
                'tim_pembahas_id' => 'required|integer|exists:tim_pembahas,id',
                'opd_id' => 'required|integer|exists:opds,id',
            ],
            [
                'tim_pembahas_id.required' => 'ID Tim Pembahas wajib diisi.',
                'tim_pembahas_id.integer' => 'ID Tim Pembahas harus berupa angka.',
                'tim_pembahas_id.exists' => 'ID Tim Pembahas tidak ditemukan di database.',
                'opd_id.required' => 'ID OPD wajib diisi.',
                'opd_id.integer' => 'ID OPD harus berupa angka.',
                'opd_id.exists' => 'ID OPD tidak ditemukan di database.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'alert' => 'danger',
                'errors' => $validator->errors(),
            ], 422);
        }
        $validated = $validator->validated();
        // hapus data
        $deleted = DB::table('opd_tim_pembahas')
            ->where('tim_pembahas_id', $validated['tim_pembahas_id'])
            ->delete();
        if ($deleted) {
            // set urutan ulang untuk yang lainnya
            $timPembahasOpd = DB::table('opd_tim_pembahas')
                ->where('opd_id', $validated['opd_id'])
                ->orderBy('urutan', 'asc')
                ->get();
            $order = 0;
            foreach ($timPembahasOpd as $tim) {
                DB::table('opd_tim_pembahas')
                    ->where('id', $tim->id)
                    ->update(['urutan' => $order]);
                $order++;
            }
            return response()->json([
                'success' => true,
                'message' => 'Anggota Tim Pembahas berhasil dihapus dari OPD.',
                'alert' => 'success',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Gagal menghapus anggota Tim Pembahas dari OPD. Mungkin anggota tersebut tidak ada di OPD manapun.',
            ], 400);
        }
    }
}
