<?php

namespace App\Http\Controllers\Api\Data\Sinkron;

use App\Http\Controllers\Controller;
use App\Jobs\SipdRi\SinkronDataSkpdSipdRi;
use App\Jobs\SipdRi\SinkronMasterKegiatanSipdRi;
use App\Jobs\SipdRi\SinkronMasterProgramSipdRi;
use Illuminate\Http\Request;

class ApiSinkroSipdRiController extends Controller
{
    public function sinkron_sipd_ri_master_opd(Request $request)
    {
        // Logic for syncing SIPD RI master OPD data
        if (!$request->has('data') || empty($request->data)) {
            return response()->json([
                'success' => false,
                'message' => 'Data is required for synchronization.',
                'alert' => 'danger',
            ], 400);
        }

        $data = [];
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Data received for synchronization.',
        //     'alert' => 'success',
        //     'data' => $request->data,
        // ]);
        foreach ($request->data as $item) {
            if (!isset($data[$item['kode_skpd']])) {
                $data[$item['kode_skpd']] = [
                    'kode_unik_opd' => $item['tahun'] . '-' . $item['kode_skpd'],
                    'kode_opd' => $item['kode_skpd'],
                    'nama_opd' => $item['nama_skpd'],
                    'tahun' => $item['tahun'],
                    'bidangs' => [],
                ];
                preg_match_all('/\d+\.\d+(?=\.|$)/', $item['kode_skpd'], $matches);
                // hapus data terakhir dari $matches karena itu adalah kode skpd
                array_pop($matches[0]);
                if (count($matches[0]) > 0) {
                    foreach ($matches[0] as $match) {
                        if ($match !== '0.00' && $match !== '0') {
                            if (!isset($data[$item['kode_skpd']]['bidangs'][$match])) {
                                $data[$item['kode_skpd']]['bidangs'][$match] = [
                                    'kode_unik_opd_tag_bidang' => $item['tahun'] . '-' . $item['kode_skpd'] . '-' . $match,
                                    'kode_urusan' => explode('.', $match)[0],
                                    'kode_bidang' => $match,
                                ];
                            }
                        }
                    }
                }
            }
        }

        $data = array_values($data);

        SinkronDataSkpdSipdRi::dispatch($data);

        return response()->json([
            'success' => true,
            'message' => 'Data synchronization successful.',
            'alert' => 'success',
            'data' => array_values($data),
        ]);
    }

    public function sinkron_master_program_sipd_ri(Request $request)
    {
        // Logic for syncing SIPD RI master program data
        if (!$request->has('data') || empty($request->data)) {
            return response()->json([
                'success' => false,
                'message' => 'Data is required for synchronization.',
                'alert' => 'danger',
            ], 400);
        }

        // Here you can add the logic to process the program data

        $data = [];

        foreach ($request->data as $item) {
            $exp_kode = explode('.', $item['kode_program']);
            $data[] = [
                'identifier' => [
                    'kode_program' => $item['kode_program'],
                    'tahun' => $item['tahun'],
                ],
                'attributes' => [
                    'kode_urusan' => $exp_kode[0],
                    'kode_bidang' => $exp_kode[0] . '.' . $exp_kode[1],
                    'uraian' => $item['nama_program']
                ],
            ];
        }

        SinkronMasterProgramSipdRi::dispatch($data);

        // For demonstration, we will just return the received data

        return response()->json([
            'success' => true,
            'message' => 'Program data synchronization successful.',
            'alert' => 'success',
            'data' => $data,
        ], 200);
    }

    public function sinkron_master_kegiatan_sipd_ri(Request $request)
    {
        // Logic for syncing SIPD RI master kegiatan data
        if (!$request->has('data') || empty($request->data)) {
            return response()->json([
                'success' => false,
                'message' => 'Data is required for synchronization.',
                'alert' => 'danger',
            ], 400);
        }

        // Here you can add the logic to process the kegiatan data

        $data = [];

        foreach ($request->data as $item) {
            $exp_kode = explode('.', $item['kode_giat']);
            $data[] = [
                'identifier' => [
                    'kode_kegiatan' => $item['kode_giat'],
                    'tahun' => $item['tahun'],
                ],
                'attributes' => [
                    'kode_urusan' => $exp_kode[0],
                    'kode_bidang' => $exp_kode[0] . '.' . $exp_kode[1],
                    'kode_program' => $exp_kode[0] . '.' . $exp_kode[1] . '.' . $exp_kode[2],
                    'uraian' => $item['nama_giat']
                ],
            ];
        }

        // You can dispatch a job here to handle the synchronization if needed
        SinkronMasterKegiatanSipdRi::dispatch($data);

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan data synchronization successful.',
            'alert' => 'success',
            'data' => $data,
        ], 200);
    }

    public function sinkron_master_subkegiatan_sipd_ri(Request $request)
    {
        // Logic for syncing SIPD RI master subkegiatan data
        if (!$request->has('data') || empty($request->data)) {
            return response()->json([
                'success' => false,
                'message' => 'Data is required for synchronization.',
                'alert' => 'danger',
            ], 400);
        }

        // Here you can add the logic to process the subkegiatan data

        $data = [];

        foreach ($request->data as $item) {
            $exp_kode = explode('.', $item['kode_subgiat']);
            $data[] = [
                'identifier' => [
                    'kode_subkegiatan' => $item['kode_subgiat'],
                    'tahun' => $item['tahun'],
                ],
                'attributes' => [
                    'kode_urusan' => $exp_kode[0],
                    'kode_bidang' => $exp_kode[0] . '.' . $exp_kode[1],
                    'kode_program' => $exp_kode[0] . '.' . $exp_kode[1] . '.' . $exp_kode[2],
                    'kode_kegiatan' => $exp_kode[0] . '.' . $exp_kode[1] . '.' . $exp_kode[2] . '.' . $exp_kode[3],
                    'uraian' => $item['nama_subgiat']
                ],
            ];
        }

        // You can dispatch a job here to handle the synchronization if needed

        return response()->json([
            'success' => true,
            'message' => 'Subkegiatan data synchronization successful.',
            'alert' => 'success',
            'data' => $data,
        ], 200);
    }
}
