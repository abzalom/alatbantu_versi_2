<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Data\Lokus;
use App\Models\Data\Opd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRapController extends Controller
{
    public function skpd_user(Request $request)
    {
        $data = Opd::whereHas('users', function ($q) {
            $q->where('id', Auth::user()->id);
        })
            ->withSum([
                'raps as pagu_bg' => fn($q) => $q->where('rap_otsuses.sumberdana', 'Otsus 1%')
            ], 'anggaran')
            ->withSum([
                'raps as pagu_sg' => fn($q) => $q->where('rap_otsuses.sumberdana', 'Otsus 1,25%')
            ], 'anggaran')
            ->withSum([
                'raps as pagu_dti' => fn($q) => $q->where('rap_otsuses.sumberdana', 'DTI')
            ], 'anggaran')
            ->withSum('raps as pagu_rap', 'anggaran')
            ->withCount('raps as jumlah_rap')
            ->get();
        // return $data;
        return view('user.user-skpd', [
            'app' => [
                'title' => 'RAP-APP | SKPD',
                'desc' => 'Perangkat Daerah',
            ],
            'data' => $data,
        ]);
    }

    public function rap_user(Request $request, $opd_id)
    {
        $exists = DB::table('opd_user')->where([
            'user_id' => Auth::user()->id,
            'opd_id' => $opd_id,
        ]);

        // return $exists->get();

        if (!$exists->exists()) {
            return redirect()->to('/user/rap/skpd')->with('error', 'Perangkat Daerah tidak ditemukan!');
        }

        $data = Opd::with([
            'raps' => fn($q) => $q->orderBy('kode_subkegiatan')
        ])
            ->find($opd_id);
        // return $data;
        if (!$data) {
            return redirect()->to('/user/rap/skpd')->with('error', 'Perangkat Daerah tidak ditemukan!');
        }
        $sumberdanas = [];
        $klasBel = [];
        if ($data->raps) {
            foreach ($data->raps as $setItem) {
                if (!isset($sumberdanas[$setItem->sumberdana])) {
                    $sumberdanas[$setItem->sumberdana] = [
                        'nama_dana' => $setItem->sumberdana === 'Otsus 1%' ? 'Otsus Block Grant 1%' : ($setItem->sumberdana === 'Otsus 1,25%' ? 'Otsus Spesific Grant 1,25%' : 'Dana Tambahan Infrastruktur'),
                        'pagu_dana' => 0,
                    ];
                }
                $sumberdanas[$setItem->sumberdana]['pagu_dana'] += $setItem->anggaran;

                if (!isset($klasBel[$setItem->klasifikasi_belanja])) {
                    $klasBel[$setItem->klasifikasi_belanja] = [
                        'klasifikasi_belanja' => $setItem->klasifikasi_belanja,
                        'pagu_alokasi' => 0,
                    ];
                }
                $klasBel[$setItem->klasifikasi_belanja]['pagu_alokasi'] += $setItem->anggaran;
            }
        }
        $lokasi = Lokus::get();
        // return $data;
        return view('user.rap-user', [
            'app' => [
                'title' => 'RAP',
                'desc' => $data->text,
            ],
            'data' => $data,
            'sumberdanas' => $sumberdanas,
            'klasBel' => $klasBel,
            'lokasi' => $lokasi,
            'opd_id' => $opd_id,
        ]);
    }
}
