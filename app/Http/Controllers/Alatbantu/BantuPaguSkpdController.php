<?php

namespace App\Http\Controllers\Alatbantu;

use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use Illuminate\Http\Request;

class BantuPaguSkpdController extends Controller
{
    public function pagu_alatbantu(Request $request)
    {
        $sumberdanas = [
            [
                'name' => 'PAD',
                'value' => 'pad',
                'selected' => false,
            ],
            [
                'name' => 'DBH Provinsi',
                'value' => 'dbh_prov',
                'selected' => false,
            ],
            [
                'name' => 'DBH Pusat',
                'value' => 'dbh_pusat',
                'selected' => false,
            ],
            [
                'name' => 'DAU Umum',
                'value' => 'dau_umum',
                'selected' => false,
            ],
            [
                'name' => 'DAU PKKK',
                'value' => 'dau_pkkk',
                'selected' => false,
            ],
            [
                'name' => 'DAU Pendidikan',
                'value' => 'dau_pendidikan',
                'selected' => false,
            ],
            [
                'name' => 'DAU Kesehatan',
                'value' => 'dau_kesehatan',
                'selected' => false,
            ],
            [
                'name' => 'DAU Infrastruktur',
                'value' => 'dau_infra',
                'selected' => false,
            ],
            [
                'name' => 'DAK FIsik',
                'value' => 'dak_fisik',
                'selected' => false,
            ],
            [
                'name' => 'DAK Non FIsik',
                'value' => 'dak_nonfisik',
                'selected' => false,
            ],
            [
                'name' => 'OTSUS BG 1%',
                'value' => 'otsus_bg',
                'selected' => false,
            ],
            [
                'name' => 'OTSUS SG 1,25%',
                'value' => 'otsus_sg',
                'selected' => false,
            ],
            [
                'name' => 'DTI',
                'value' => 'dti',
                'selected' => false,
            ],
            [
                'name' => 'Dana Desa',
                'value' => 'dana_desa',
                'selected' => false,
            ],
        ];
        $opds = Opd::get();
        return view('alatbantu.alatbantu-pagu-skpd.alatbantu-pagu-skpd', [
            'app' => [
                'title' => 'Alat Bantu',
                'desc' => 'Alat Bantu Pagu SKPD',
            ],
            'opds' => $opds,
            'sumberdanas' => $sumberdanas,
        ]);
    }
}
