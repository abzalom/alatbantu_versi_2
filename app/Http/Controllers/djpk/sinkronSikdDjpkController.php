<?php

namespace App\Http\Controllers\djpk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class sinkronSikdDjpkController extends Controller
{
    public $tableName = 'request_sikd_djpks';

    public function request_sikd_djpk(Request $request, $jenis)
    {
        if ($jenis !== 'nomenklatur' && $jenis !== 'rap') {
            return redirect()->to('/sinkron/djpk/sikd/nomenklatur');
        }
        $data = DB::table($this->tableName)
            ->where('jenis', $jenis)
            ->where('tahun', session()->get('tahun'))
            ->get();
        return view('sinkron-data.djpk.sinkron-data-djpk-sikd', [
            'app' => [
                'title' => 'Sinkron Data',
                'desc' => 'Sinkron Data ' . ucfirst($jenis) . ' SIKD DJPK',
            ],
            'data' => $data,
            'jenisRequest' => $jenis,
        ]);
    }

    public function request_sumberdana_sikd_djpk(Request $request, $jenis, $jenis_dana)
    {
        // return $jenis_dana;
        if ($jenis !== 'nomenklatur' && $jenis !== 'rap') {
            return redirect()->to('/sinkron/djpk/sikd/nomenklatur');
        }
        if ($jenis_dana !== 'bg' && $jenis_dana !== 'sg' && $jenis_dana !== 'dti') {
            return redirect()->to('/sinkron/djpk/sikd/nomenklatur');
        }
        $data = DB::table($this->tableName)
            ->where('jenis', $jenis)
            ->where('sumberdana', $jenis_dana)
            ->where('tahun', session()->get('tahun'))
            ->get();

        // return $data;

        return view('sinkron-data.djpk.sinkron-jenis-data-djpk-sikd', [
            'app' => [
                'title' => 'Sinkron Data',
                'desc' => 'Sinkron Data ' . ucfirst($jenis) . ' SIKD DJPK',
            ],
            'data' => $data,
            'jenisRequest' => $jenis,
            'jenis_dana' => $jenis_dana,
        ]);
    }

    public function create_link_sikd_djpk(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:' . $this->tableName . ',name',
                'sumberdana' => 'required|in:bg,sg,dti',
                'jenis' => 'required|in:nomenklatur,rap',
                'method' => 'required|in:get,post',
                'url' => 'required|url',
                'param_key' => 'required',
                'param_value' => 'required',
            ],
            [
                'method.required' => 'Method tidak boleh kosong!',
                'method.in' => 'Method hanya boleh GET atau POST!',

                'sumberdana.required' => 'Sumber Dana tidak boleh kosong!',
                'sumberdana.in' => 'Sumber Dana tidak valid!',

                'jenis.required' => 'Jenis tidak boleh kosong!',
                'jenis.in' => 'Jenis tidak valid!',

                'url.required' => 'URL tidak boleh kosong!',
                'url.url' => 'URL tidak valid!',

                'param_key.required' => 'Param Key tidak boleh kosong!',
                'param_value.required' => 'Param Value tidak boleh kosong!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Terjadi kesalahan')
                ->withErrors($validator);
        }
        DB::table($this->tableName)->insert([
            'name' => $request->name,
            'sumberdana' => $request->sumberdana,
            'jenis' => $request->jenis,
            'url' => $request->url,
            'param_key' => $request->param_key,
            'param_value' => $request->param_value,
            'method' => $request->method,
            'tahun' => session()->get('tahun'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Data berhasil di simpan!');
    }

    public function update_link_sikd_djpk(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:' . $this->tableName . ',name,' . $request->id,
                'sumberdana' => 'required|in:bg,sg,dti',
                'jenis' => 'required|in:nomenklatur,rap',
                'method' => 'required|in:get,post',
                'url' => 'required|url',
                'param_key' => 'required',
                'param_value' => 'required',
            ],
            [
                'method.required' => 'Method tidak boleh kosong!',
                'method.in' => 'Method hanya boleh GET atau POST!',

                'sumberdana.required' => 'Sumber Dana tidak boleh kosong!',
                'sumberdana.in' => 'Sumber Dana tidak valid!',

                'jenis.required' => 'Jenis tidak boleh kosong!',
                'jenis.in' => 'Jenis tidak valid!',

                'url.required' => 'URL tidak boleh kosong!',
                'url.url' => 'URL tidak valid!',

                'param_key.required' => 'Param Key tidak boleh kosong!',
                'param_value.required' => 'Param Value tidak boleh kosong!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Terjadi kesalahan')
                ->withErrors($validator);
        }
        $data = DB::table($this->tableName)->find($request->id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data gagal tersimpan!');
        }
        DB::table($this->tableName)
            ->where('id', $request->id)
            ->update([
                'name' => $request->name,
                'sumberdana' => $request->sumberdana,
                'jenis' => $request->jenis,
                'method' => $request->method,
                'url' => $request->url,
                'param_key' => $request->param_key,
                'param_value' => $request->param_value,
                'updated_at' => now(),
            ]);
        return redirect()->back()->with('success', 'Data berhasil di simpan!');
    }

    public function insert_raquest_sumberdana_sikd_djpk(Request $request)
    {
        return $request->all();
    }
}
