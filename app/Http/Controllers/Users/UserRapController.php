<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRapController extends Controller
{
    public function rap_user(Request $request)
    {
        $data = Opd::with('raps')
            // ->where('tahun', session()->get('tahun'))
            ->find(Auth::user()->opd->id);
        $sumberdanas = [];
        $klasBel = [];
        foreach ($data->raps as $setItem) {
            if (!isset($sumberdanas[$setItem->sumberdana])) {
                $sumberdanas[$setItem->sumberdana] = [
                    'nama_dana' => $setItem->sumberdana === 'otsus 1%' ? 'Otsus Block Grant 1%' : ($setItem->sumberdana === 'otsus 1,25%' ? 'Otsus Spesific Grant 1,25%' : 'Dana Tambahan Infrastruktur'),
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
        // return $sumberdanas;
        return view('user.rap-user', [
            'app' => [
                'title' => 'RAP',
                'desc' => $data->text,
            ],
            'data' => $data,
            'sumberdanas' => $sumberdanas,
            'klasBel' => $klasBel,
        ]);
    }
}
