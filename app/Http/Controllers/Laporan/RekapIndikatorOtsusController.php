<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Otsus\Data\B1TemaOtsus;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RekapIndikatorOtsusController extends Controller
{
    public array $menuRekapan;

    public function __construct()
    {
        $arrayMenu = json_decode(Storage::disk('public')->get('/app/rekapan-indikator/menu-rekapan-indikator.json'), true);
        foreach ($arrayMenu as $item) {
            $this->menuRekapan[] = [
                'name' => $item['name'],
                'link' => request()->is($item['link']) ? '#' : '/' . $item['link'],
                'active' => request()->is($item['link']) ? 'active' : '',
            ];
        }
    }

    public function rekap_indikator(Request $request)
    {
        $getData = B1TemaOtsus::with([
            'program' => fn($q) => $q->whereHas('taggings', fn($q) => $q->where('tahun', session()->get('tahun'))),
            'program.keluaran' => fn($q) => $q->whereHas('taggings', fn($q) => $q->where('tahun', session()->get('tahun'))),
            'program.keluaran.aktifitas' => fn($q) => $q->whereHas('taggings', fn($q) => $q->where('tahun', session()->get('tahun'))),
            'program.keluaran.aktifitas.target_aktifitas' => fn($q) => $q->whereHas('taggings', fn($q) => $q->where('tahun', session()->get('tahun')))
                ->withSum([
                    'taggings as volume' => fn($q) => $q->where('tahun', session()->get('tahun'))->whereHas('raps')
                ], 'volume')
                ->withSum([
                    'raps as anggaran' => fn($q) => $q->where('tahun', session()->get('tahun'))
                ], 'anggaran'),
            'program.keluaran.aktifitas.target_aktifitas.raps' => fn($q) => $q->where('tahun', session()->get('tahun'))->select('kode_target_aktifitas', 'sumberdana')
        ])->whereHas('raps')
            ->get();
        // return $getData;
        return view('rekapan.rekapan-indikator-otsus', [
            'app' => [
                'title' => 'Rekapan',
                'desc' => 'Rekapan Indikator Otsus',
            ],
            'data' => $getData,
            'menuRekapan' => $this->menuRekapan,
        ]);
    }

    public function rekap_rap_indikator(Request $request)
    {
        $data = B5TargetAktifitasUtamaOtsus::whereHas('raps', fn($q) => $q->where('tahun', session()->get('tahun')))
            ->withSum([
                'taggings as volume' => fn($q) => $q->where('tahun', session()->get('tahun'))->whereHas('raps')
            ], 'volume')
            ->withSum([
                'raps as anggaran' => fn($q) => $q->where('tahun', session()->get('tahun'))
            ], 'anggaran')
            ->with([
                'raps' => fn($q) => $q->where('tahun', session()->get('tahun'))->select([
                    'kode_target_aktifitas',
                    'kode_subkegiatan',
                    'nama_subkegiatan',
                    'vol_subkeg',
                    'satuan_subkegiatan',
                    'klasifikasi_belanja',
                    'sumberdana',
                    'anggaran',
                ])
            ])
            ->get();
        // return $data;
        return view('rekapan.rekapan-indikator-otsus-rap', [
            'app' => [
                'title' => 'Rekapan',
                'desc' => 'Rekapan Indikator Otsus',
            ],
            'data' => $data,
            'menuRekapan' => $this->menuRekapan,
        ]);
    }
}
