<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiTaggingController extends Controller
{
    public function indikator_target_aktifitas_tag_rap(Request $request)
    {
        $data = new B5TargetAktifitasUtamaOtsus();
        $data = $data->with('raps');
        // $data = B5TargetAktifitasUtamaOtsus::with('raps');
        if ($request->has('kode_opd')) {
            $data = $data->with([
                'raps' => fn($q) => $q->where('kode_opd', $request->kode_opd)
            ]);
        }
        if ($request->has('id')) {
            $data = $data->with('raps')->where('id', $request->id);
        }
        if ($request->has('kode_target_aktifitas')) {
            $data = $data->with('raps')->where('kode_target_aktifitas', $request->kode_target_aktifitas);
        }
        $data = $data->get();

        return $data;
    }
}
