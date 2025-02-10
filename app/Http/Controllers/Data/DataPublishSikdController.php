<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Data\Sikd\Publish\SikdPublishRap;
use Illuminate\Http\Request;

class DataPublishSikdController extends Controller
{
    public function rap_otsus_bg(Request $request, $sumberdana)
    {
        if (!in_array($sumberdana, ['bg', 'sg', 'dti'])) {
            return redirect()->to('/')->with('error', 'Sumber Dana tidak ditemukan!');
        }
        $data = SikdPublishRap::where('sumberdana', $sumberdana)
            ->where('tahun', tahun())
            ->get();
        return view('data/publish/rap/otsus-bg', [
            'app' => [
                'title' => 'DATA PUBLLISH',
                'desc' => 'Data Publish RAP pada Aplikasi SIKD Otsus DJPK',
            ],
            'data' => $data,
            'pendanaan' => $sumberdana == 'bg' ? 'Dana Otonomi Khusus Block Grant 1%' : ($sumberdana == 'sg' ? 'Dana Otonomi Khusus Spesific Grant 1%' : 'Dana Tambahan Infrastruktur'),
        ]);
    }
}
