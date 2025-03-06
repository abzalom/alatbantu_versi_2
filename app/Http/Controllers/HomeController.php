<?php

namespace App\Http\Controllers;

use App\Models\Data\Opd;
use App\Models\Otsus\DanaAlokasiOtsus;
use App\Models\Rap\RapOtsus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $alokasi_otsus = DanaAlokasiOtsus::where('tahun', session()->get('tahun'))
            ->first();
        $raps = RapOtsus::where('tahun', session()->get('tahun'))->get();
        $countSkpd = $raps->countBy('kode_unik_opd')->count();
        $countProgram = $raps->countBy('kode_program')->count();
        $countKegiatan = $raps->countBy('kode_kegiatan')->count();
        $countRap = $raps->count();
        $totalInputOtsus = $raps->sum('anggaran');
        // return $totalInputOtsus;

        $dataKlasBel = [
            'alokasi_bg' => [
                'name' => 'Otsus BG 1%',
                'active' => true,
                'id' => 'pills-bg',
                'alias' => 'bg',
                'pagu' => $alokasi_otsus->alokasi_bg,
                'alokasi_terinput' => 0,
                'klasifikasi' => []
            ],
            'alokasi_sg' => [
                'name' => 'Otsus SG 1,25%',
                'active' => false,
                'id' => 'pills-sg',
                'alias' => 'sg',
                'pagu' => $alokasi_otsus->alokasi_sg,
                'alokasi_terinput' => 0,
                'klasifikasi' => []
            ],
            'alokasi_dti' => [
                'name' => 'DTI',
                'active' => false,
                'id' => 'pills-dti',
                'alias' => 'dti',
                'pagu' => $alokasi_otsus->alokasi_dti,
                'alokasi_terinput' => 0,
                'klasifikasi' => []
            ],
        ];

        foreach ($raps as $rap) {
            $alias_dana = match ($rap->sumberdana) {
                'Otsus 1%' => 'alokasi_bg',
                'Otsus 1,25%' => 'alokasi_sg',
                default => 'alokasi_dti'
            };

            if (!isset($dataKlasBel[$alias_dana]['klasifikasi'][$rap->klasifikasi_belanja])) {
                $dataKlasBel[$alias_dana]['klasifikasi'][$rap->klasifikasi_belanja] = [
                    'name' => $rap->klasifikasi_belanja,
                    'total' => 0,
                    'persen' => 0, // Tambahkan key persen
                ];
            }

            // Tambahkan anggaran ke total
            $dataKlasBel[$alias_dana]['klasifikasi'][$rap->klasifikasi_belanja]['total'] += $rap->anggaran;
            $dataKlasBel[$alias_dana]['alokasi_terinput'] += $rap->anggaran;
        }

        // Hitung persentase setelah total anggaran terkumpul
        foreach ($dataKlasBel as &$alokasi) {
            $pagu = $alokasi['pagu'];

            if ($pagu > 0) {
                foreach ($alokasi['klasifikasi'] as &$klasifikasi) {
                    $klasifikasi['persen'] = ($klasifikasi['total'] / $pagu) * 100;
                }
            }
        }

        // Mengubah klasifikasi menjadi array numerik agar lebih rapi
        foreach ($dataKlasBel as &$alokasi) {
            // Urutkan berdasarkan persen (descending order)
            usort($alokasi['klasifikasi'], function ($a, $b) {
                return $b['persen'] <=> $a['persen']; // Descending order
            });

            // Reset indeks array agar tetap berurutan (0, 1, 2, ...)
            $alokasi['klasifikasi'] = array_values($alokasi['klasifikasi']);
        }


        // return $dataKlasBel;
        return view('home', [
            'app' => [
                'title' => 'RAP OTSUS',
                'desc' => 'Halaman Home',
            ],
            'alokasi_otsus' => $alokasi_otsus,
            'totalAlokasiOtsusTkdd' => $alokasi_otsus->alokasi_bg + $alokasi_otsus->alokasi_sg + $alokasi_otsus->alokasi_dti,
            'countSkpd' => $countSkpd,
            'countProgram' => $countProgram,
            'countKegiatan' => $countKegiatan,
            'countRap' => $countRap,
            'dataKlasBel' => $dataKlasBel,
            'totalInputOtsus' => $totalInputOtsus,
        ]);
    }
}
