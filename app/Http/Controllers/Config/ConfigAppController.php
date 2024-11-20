<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigAppController extends Controller
{
    public function config_app_session_tahun(Request $request)
    {
        session([
            'tahun' => (int) $request->tahun,
        ]);
        return redirect()->back()->with('success', 'Tahun berhasil diganti ke ' . session()->get('tahun'));
    }
}
