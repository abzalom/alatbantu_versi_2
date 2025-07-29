<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiOpdController extends Controller
{
    public function api_opd(Request $request)
    {
        $data = DB::table('opds');
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
}
