<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
    public function set_timezone(Request $request)
    {
        $request->validate([
            'timezone' => 'required|string'
        ]);

        // Simpan ke session
        Session::put('user_timezone', $request->timezone);
    }

    public function tahun(Request $request)
    {
        $tahuns = DB::table('tahun_anggaran')->select('id', 'tahun')->get();
        if (!session()->has('tahun') || session()->get('tahun') == null) {
            return view('v1-1.config.session.config-session-tahun', [
                'app' => [
                    'title' => 'Pilih Tahun',
                ],
                'tahuns' => $tahuns,
            ]);
        }

        return redirect()->back()->with('success', 'Tahun sudah dipilih: ' . session()->get('tahun'));
    }
    public function set_tahun(Request $request)
    {
        $request->validate(
            [
                'tahun' => 'required|integer|min:2022|max:' . (date('Y') + 1),
            ],
            [
                'tahun.required' => 'Tahun harus dipilih.',
                'tahun.integer' => 'Tahun harus berupa angka.',
                'tahun.min' => 'Tahun minimal adalah 2022.',
                'tahun.max' => 'Tahun maksimal adalah tahun depan.',
            ]
        );

        // Simpan ke session
        Session::put('tahun', $request->tahun);

        return redirect()->to('/')->with('success', 'Tahun berhasil disimpan.');
    }
}
