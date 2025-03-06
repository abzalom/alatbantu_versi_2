<?php

namespace App\Http\Controllers;

use App\Models\Data\Opd;
use App\Models\Nomenklatur\A5Subkegiatan;
use App\Models\Nomenklatur\NomenklaturSikd;
use App\Models\Otsus\DanaAlokasiOtsus;
use App\Models\Rap\RapOtsus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function testing(Request $request)
    {
        $sumberdana = $request->jenis == 'bg' ? 'Otsus 1%' : ($request->jenis == 'sg' ? 'Otsus 1,25%' : 'DTI');
        $alokasiKolom = 'alokasi_' . $request->jenis;
        $alokasi_otsus = DanaAlokasiOtsus::where('tahun', session()->get('tahun'))
            ->first();
        $pagu_alokasi = $alokasi_otsus->$alokasiKolom;

        $data = DB::table('rap_otsuses')
            ->select(
                'klasifikasi_belanja',
                DB::raw('SUM(anggaran) as total_anggaran'),
                DB::raw("SUM(anggaran) / $pagu_alokasi as persen")
            ) // Menggunakan SUM dengan alias
            ->where('tahun', session()->get('tahun'))
            ->where('sumberdana', $sumberdana)
            ->groupBy('klasifikasi_belanja') // Grup berdasarkan klasifikasi belanja
            ->get();
        return $data;
    }
}
