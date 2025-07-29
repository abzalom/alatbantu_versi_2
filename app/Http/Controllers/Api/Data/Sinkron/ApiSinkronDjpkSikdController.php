<?php

namespace App\Http\Controllers\Api\Data\Sinkron;

use App\Http\Controllers\Controller;
use App\Jobs\SinkronDataSikd;
use App\Models\Nomenklatur\NomenklaturSikd;
use Carbon\Carbon;
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

        $urlExp = explode('/', $data->url);
        $endOfSegmentUrl = end($urlExp);
        $splitSegmentUrl = explode('=', $endOfSegmentUrl);
        $endOfSegmentUrl = end($splitSegmentUrl);

        $seond = (int) $endOfSegmentUrl / 1000;
        $timeStamp = Carbon::createFromTimestamp($seond);
        // $date = $timeStamp->format('Y-m-d H:i:s');
        // $year = $timeStamp->format('Y');

        if ($response->successful()) {
            $responseData = json_decode($response->body(), true);

            $returnData = $responseData['data'];
            if ($data->jenis == 'rap') {
                $dataWithYear = array_map(function ($item) use ($data) {
                    $item['tahun'] = $data->tahun;
                    return $item;
                }, $responseData['data']);
                $returnData = $dataWithYear;
            }
            return response()->json([
                'success' => true,
                'alert' => 'success',
                'data' => $returnData,
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
                    'tahun' => $request->token_tahun,
                ];
            }
        }

        if ($request->jenis == 'rap') {
            foreach ($array as $item) {
                $kode_subkegiatan_full = $item['kode_urusan'] . '.' . $item['kode_bidang_urusan'] . '.' . $item['kode_program'] . '.' . $item['kode_kegiatan'] . '.' . $item['kode_subkegiatan'];
                $data[] = [
                    'sumberdana' => $request->sumberdana,
                    'id_rap' => $item['id'],
                    'rencanaanggaranprogram_id' => $item['rencanaanggaranprogram_id'],
                    'subkegiatan_id' => $item['subkegiatan_id'],
                    'subkegiatan_history_id' => $item['subkegiatan_history_id'],
                    'jenis_kegiatan' => $item['jenis_kegiatan'],
                    'target_keluaran' => $item['target_keluaran'],
                    'target_keluaran_efisiensi' => $item['target_keluaran_efisiensi'],
                    'target_keluaran_non_efisiensi' => $item['target_keluaran_non_efisiensi'],
                    'pagu_alokasi' => $item['pagu_alokasi'],
                    'pagu_efisiensi' => $item['pagu_efisiensi'],
                    'pagu_non_efisiensi' => $item['pagu_non_efisiensi'],
                    'lokus' => $item['lokus'],
                    'koordinat' => $item['koordinat'],
                    'koordinat_lintang' => $item['koordinat_lintang'],
                    'koordinat_bujur' => $item['koordinat_bujur'],
                    'opd_id' => $item['opd_id'],
                    'jadwal_kegiatan_awal' => $item['jadwal_kegiatan_awal'],
                    'jadwal_kegiatan_akhir' => $item['jadwal_kegiatan_akhir'],
                    'keterangan' => $item['keterangan'],
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at'],
                    'link_file_dukung' => $item['link_file_dukung'],
                    'helper_id' => $item['helper_id'],
                    'aktivitas_id' => $item['aktivitas_id'],
                    'jenis_layanan' => $item['jenis_layanan'],
                    'penerima_manfaat' => $item['penerima_manfaat'],
                    'program_strategis' => $item['program_strategis'],
                    'pendanaan_lain' => $item['pendanaan_lain'],
                    'multiyears' => $item['multiyears'],
                    'kode_urusan' => $item['kode_urusan'],
                    'uraian_urusan' => $item['uraian_urusan'],
                    'kode_bidang_urusan' => $item['kode_bidang_urusan'],
                    'uraian_bidang_urusan' => $item['uraian_bidang_urusan'],
                    'kode_program' => $item['kode_program'],
                    'uraian_program' => $item['uraian_program'],
                    'kode_kegiatan' => $item['kode_kegiatan'],
                    'uraian_kegiatan' => $item['uraian_kegiatan'],
                    'kode_subkegiatan' => $item['kode_subkegiatan'],
                    'klasifikasi_belanja' => $item['klasifikasi_belanja'],
                    'subkegiatan_uraian' => $item['subkegiatan_uraian'],
                    'indikator_keluaran' => $item['indikator_keluaran'],
                    'satuan' => $item['satuan'],
                    'kode_subkegiatan_full' => $kode_subkegiatan_full,
                    'text_subkegiatan' => $kode_subkegiatan_full . ' ' . $item['subkegiatan_uraian'],
                    'opd_uraian' => $item['opd_uraian'],
                    'kesesuaian' => $item['kesesuaian'],
                    'tahun' => $item['tahun'],
                ];
            }

            // return response()->json([
            //     'success' => true,
            //     'alert' => 'success',
            //     'data' => [
            //         'sumberdana' => $request->sumberdana,
            //         'jenis' => $request->jenis,
            //         'data' => $data,
            //     ]
            // ], 200);
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
