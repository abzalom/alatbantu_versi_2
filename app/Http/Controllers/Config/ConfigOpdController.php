<?php

namespace App\Http\Controllers\Config;

use App\Enums\PangkatEnums;
use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use App\Models\Nomenklatur\A2Bidang;
use App\Models\Tagging\Nomenklatur\OpdTagBidang;
use App\Services\OpdTagBidangService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Http\Client\ConnectionException;

class ConfigOpdController extends Controller
{
    protected OpdTagBidangService $opdTagBidangService;

    public function __construct(OpdTagBidangService $opdTagBidangService)
    {
        $this->opdTagBidangService = $opdTagBidangService;
    }

    public function config_opd()
    {
        $opds = Auth::user()->hasRole('user') ? Auth::user()->opds() : new Opd();
        $opds = $opds->with([
            'kepala_aktif',
            'tag_bidang.bidang',
        ])->orderBy('kode_opd')->get();
        $bidangs = A2Bidang::orderBy('kode_bidang')->get();
        // return $opds;
        return view('app.pengaturan.opd.pengaturan-opd', [
            'app' => [
                'title' => 'Pengaturan',
                'desc' => 'Pengaturan Perangkat Daerah',
            ],
            'opds' => $opds,
            'pangkats' => PangkatEnums::cases(),
            'bidangs' => $bidangs,
        ]);
    }

    public function config_sinkron_opd(Request $request)
    {
        if (Auth::user()->hasRole('user')) {
            return redirect()->to('/config/opd')->with('error', 'Unauthorized');
        }
        try {
            $response = Http::get('http://localhost:3000/api/local/get/data/skpd', [
                'tahun' => session()->get('tahun')
            ]);

            if ($response->failed()) {
                return redirect()->to('/config/opd')->with('error', 'Gagal mengambil data SKPD');
            }

            // Ambil data json
            $data = $response->json();

            $data_tag = [];

            foreach ($data as $opd) {
                $expKode = explode('.', $opd['kode_opd']);
                $urusan1 = $expKode[0] ?? null;
                $bidang1 = ($expKode[0] ?? '') . '.' . ($expKode[1] ?? '');
                $unik1 = $opd['tahun'] . '-' . $opd['kode_opd'] . '-' . $bidang1;

                $urusan2 = $expKode[2] ?? null;
                $bidang2 = ($expKode[2] ?? '') . '.' . ($expKode[3] ?? '');
                $unik2 = $opd['tahun'] . '-' . $opd['kode_opd'] . '-' . $bidang2;

                $urusan3 = $expKode[4] ?? null;
                $bidang3 = ($expKode[4] ?? '') . '.' . ($expKode[5] ?? '');
                $unik3 = $opd['tahun'] . '-' . $opd['kode_opd'] . '-' . $bidang3;

                // Tagging urusan pertama
                if (!isset($data_tag[$unik1])) {
                    $data_tag[$unik1] = [
                        'kode_unik_opd' => $opd['tahun'] . '-' . $opd['kode_opd'],
                        'kode_unik_opd_tag_bidang' => $unik1,
                        'kode_opd' => $opd['kode_opd'],
                        'kode_urusan' => $urusan1,
                        'kode_bidang' => $bidang1,
                        'tahun' => $opd['tahun'],
                    ];
                }

                // Urusan kedua (jika ada)
                if ($urusan2 && $urusan2 !== '0' && $urusan2 !== '0.00') {
                    if (!isset($data_tag[$unik2])) {
                        $data_tag[$unik2] = [
                            'kode_unik_opd' => $opd['tahun'] . '-' . $opd['kode_opd'],
                            'kode_unik_opd_tag_bidang' =>  $unik2,
                            'kode_opd' => $opd['kode_opd'],
                            'kode_urusan' => $urusan2,
                            'kode_bidang' => $bidang2,
                            'tahun' => $opd['tahun'],
                        ];
                    }
                }

                // Urusan ketiga (jika ada)
                if ($urusan3 && $urusan3 !== '0' && $urusan3 !== '0.00') {
                    if (!isset($data_tag[$unik3])) {
                        $data_tag[$unik3] = [
                            'kode_unik_opd' => $opd['tahun'] . '-' . $opd['kode_opd'],
                            'kode_unik_opd_tag_bidang' =>  $unik3,
                            'kode_opd' => $opd['kode_opd'],
                            'kode_urusan' => $urusan3,
                            'kode_bidang' => $bidang3,
                            'tahun' => $opd['tahun'],
                        ];
                    }
                }

                // Simpan OPD
                Opd::updateOrCreate(
                    [
                        'kode_unik_opd' => $opd['tahun'] . '-' . $opd['kode_opd'],
                    ],
                    [
                        'kode_opd' => $opd['kode_opd'],
                        'nama_opd' => $opd['nama_opd'],
                        'tahun' => $opd['tahun'],
                    ]
                );
            }

            // Simpan tagging bidang
            foreach (array_values($data_tag) as $tagging) {
                OpdTagBidang::updateOrCreate(
                    [
                        'kode_unik_opd_tag_bidang' => $tagging['kode_unik_opd_tag_bidang'],
                        'tahun' => $tagging['tahun'],
                    ],
                    [
                        'kode_unik_opd' => $tagging['kode_unik_opd'],
                        'kode_opd' => $tagging['kode_opd'],
                        'kode_urusan' => $tagging['kode_urusan'],
                        'kode_bidang' => $tagging['kode_bidang'],
                    ]
                );
            }

            return redirect()->back()->with('success', 'SKPD berhasil di sinkron!');
        } catch (ConnectionException $e) {
            return redirect()->to('/config/opd')->with('error', 'Tidak dapat terhubung ke server API');
        }
    }

    public function tag_bidang_opd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'opd_id' => 'required|exists:opds,id',
            'bidangs' => 'required|array|min:1|max:5',
            'bidangs.*' => 'exists:a2_bidangs,id',
        ], [
            'opd_id.required' => 'OPD tidak ditemukan.',
            'opd_id.exists' => 'OPD tidak ditemukan.',
            'bidangs.required' => 'Bidang wajib diisi.',
            'bidangs.array' => 'Bidang tidak valid.',
            'bidangs.min' => 'Pilih minimal 1 bidang.',
            'bidangs.max' => 'Pilih maksimal 5 bidang.',
            'bidangs.*.exists' => 'Bidang tidak valid.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan! data gagal disimpan.')
                ->withInput()
                ->withErrors($validator);
        }
        $bidangs = A2Bidang::whereIn('id', $request->bidangs)->orderBy('kode_bidang')->get();
        $opd = Opd::with([
            'tag_bidang' => fn($q) => $q->withTrashed(),
        ])->find($request->opd_id);
        if (!$opd) {
            return redirect()->back()
                ->with('error', 'OPD tidak ditemukan.');
        }
        $existing = $opd->tag_bidang->keyBy('kode_bidang');
        $toAdd = $bidangs->filter(fn($item) => !$existing->has($item->kode_bidang))->values();
        $toKeep = $bidangs->filter(fn($item) => $existing->has($item->kode_bidang))->values();
        $toDelete = $existing->filter(fn($item) => !in_array($item->kode_bidang, $bidangs->pluck('kode_bidang')->toArray()) && !$item->trashed())->values();

        try {
            DB::beginTransaction();
            // Proses tambah bidang
            foreach ($toAdd as $addBidang) {
                $dataAdd = [
                    'kode_unik_opd' => $opd->kode_unik_opd,
                    'kode_unik_opd_tag_bidang' => $opd->kode_unik_opd . '-' . $addBidang->kode_bidang,
                    'kode_opd' => $opd->kode_opd,
                    'kode_urusan' => $addBidang->kode_urusan,
                    'kode_bidang' => $addBidang->kode_bidang,
                    'tahun' => $opd->tahun,
                ];
                OpdTagBidang::create($dataAdd);
            }
            foreach ($toKeep as $keepBidang) {
                $tag = $existing->get($keepBidang->kode_bidang);
                if ($tag && $tag->trashed()) {
                    $tag->restore();
                }
            }
            foreach ($toDelete as $delBidang) {
                if ($delBidang && !$delBidang->trashed()) {
                    $delBidang->delete();
                }
            }
            DB::commit();
            return redirect()->back()
                ->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan! data gagal disimpan. ' . $e->getMessage())
                ->withInput();
        }
        // return $request->all();
    }
}
