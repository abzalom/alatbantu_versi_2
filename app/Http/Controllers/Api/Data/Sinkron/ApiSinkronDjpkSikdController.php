<?php

namespace App\Http\Controllers\Api\Data\Sinkron;

use App\Http\Controllers\Controller;
use App\Jobs\SinkronDataSikd;
use App\Models\Nomenklatur\NomenklaturSikd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ApiSinkronDjpkSikdController extends Controller
{
    public function sinkron_data_djpk_sikd(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Some parameter\'s is required!'
            ], 443);
        }

        $data = DB::table('request_sikd_djpks')->find($request->id);
        if (!$data) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Request data tidak ditemukan!'
            ], 404);
        }

        $response = Http::withHeaders([
            $data->param_key => $data->param_value
        ])->get($data->url);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'alert' => 'success',
                'data' => json_decode($response->body(), true)['data'],
            ], $response->status());
        } else {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Request error!',
            ], $response->status());
        }
    }

    public function create_data_djpk_sikd(Request $request)
    {
        if (!$request->has('sumberdana') || !$request->sumberdana || !$request->has('jenis') || !$request->jenis || !$request->has('data') || !$request->data) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Some parameter\'s id missing!'
            ], 443);
        }

        $array = json_decode($request->data, true);

        $data = [];

        if ($request->jenis == 'nomenklatur') {
            foreach ($array as $item) {
                $exptText = explode(' - ', $item['text']);
                $expKode = explode('.', $exptText[0]);
                $kode_bidang = $expKode[0] . '.' . $expKode[1];
                $kode_program = $kode_bidang . '.' . $expKode[2];
                $kode_kegiatan = $kode_program . '.' . $expKode[3] . '.' . $expKode[4];
                $kode_subkegiatan = $exptText[0];
                $data[] = [
                    'id_subkegiatan' => $item['id'],
                    'kode_bidang' => $kode_bidang,
                    'nama_bidang' => $item['bidang_urusan'],
                    'kode_program' => $kode_program,
                    'nama_program' => $item['program'],
                    'kode_kegiatan' => $kode_kegiatan,
                    'nama_kegiatan' => $item['kegiatan'],
                    'kode_subkegiatan' => $kode_subkegiatan,
                    'nama_subkegiatan' => $exptText[1],
                    'text' => $item['text'],
                    'indikator' => $item['indikator'],
                    'satuan' => $item['satuan'],
                    'klasifikasi_belanja' => $item['klasifikasi_belanja'],
                    'sumberdana' => $request->sumberdana,
                ];
            }
        }

        SinkronDataSikd::dispatch($data, $request->jenis, $request->sumberdana);

        return response()->json([
            'success' => true,
            'alert' => 'success',
            'data' => [
                'sumberdana' => $request->sumberdana,
                'jenis' => $request->jenis,
                'data' => $data,
            ]
        ], 200);
    }
}
