<?php

namespace App\Http\Controllers\Cetak;

use App\Models\Data\Opd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config\TimPembahas;
use App\Models\Rap\RapOtsus;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;

class CetakRapController extends Controller
{
    public function cetak_rap(Request $request)
    {
        if (auth()->user()->hasRole('user')) {
            abort(404);
        }
        $opd = Opd::whereHas('tag_otsus', function ($q) use ($request) {
            $q->where('alias_dana', $request->jenis)
                ->where('pembahasan', 'setujui')
                ->where('validasi', true)
            ;
        })->with([
            'tag_otsus' => function ($q) use ($request) {
                $q->where('alias_dana', $request->jenis)
                    ->where('pembahasan', 'setujui')
                    ->where('validasi', true);
            },
            'tag_otsus.target_aktifitas.aktifitas.program.tema',
            'tag_otsus.raps' => function ($q) use ($request) {
                $q->where('rap_otsuses.alias_dana', $request->jenis)
                    ->where('rap_otsuses.pembahasan', 'setujui')
                    ->where('rap_otsuses.validasi', true)
                ;
            },
            'kepala_aktif',
            'pagu',
            'tim_pembahas',
        ])
            ->withSum(['raps as pagu_usulan' => function ($q) use ($request) {
                $q->where('rap_otsuses.alias_dana', $request->jenis)
                    ->where('rap_otsuses.pembahasan', 'setujui')
                    ->where('rap_otsuses.validasi', true);
            }], 'anggaran')
            ->find($request->opd);
        if (!$opd) {
            return redirect()->back()->with('error', 'Data OPD tidak ditemukan atau belum disetujui.');
        }
        $klasfikasi_belanja = [];
        foreach ($opd->tag_otsus as $tag) {
            if (!isset($tag->raps) || $tag->raps->isEmpty()) {
                continue;
            }
            foreach ($tag->raps as $rap) {
                if (!isset($klasfikasi_belanja[$rap->klasifikasi_belanja])) {
                    $klasfikasi_belanja[$rap->klasifikasi_belanja] = [
                        'nama' => $rap->klasifikasi_belanja,
                        'total' => 0,
                    ];
                }
                $klasfikasi_belanja[$rap->klasifikasi_belanja]['total'] += $rap->anggaran;
            }
        }
        $opd->klasfikasi_belanja = array_values($klasfikasi_belanja);
        $tema_program = [];
        foreach ($opd->tag_otsus as $tag) {
            if (!isset($tema_program[$tag->target_aktifitas->aktifitas->program->tema->kode_tema])) {
                $tema_program[$tag->target_aktifitas->aktifitas->program->tema->kode_tema] = [
                    'kode_tema' => $tag->target_aktifitas->aktifitas->program->tema->kode_tema,
                    'uraian' => $tag->target_aktifitas->aktifitas->program->tema->uraian,
                    'text' => $tag->target_aktifitas->aktifitas->program->tema->kode_tema . ' - ' . $tag->target_aktifitas->aktifitas->program->tema->uraian,
                    'program' => [],
                ];
            }
            if (!isset($tema_program[$tag->target_aktifitas->aktifitas->program->tema->kode_tema]['program'][$tag->target_aktifitas->aktifitas->program->kode_program])) {
                $tema_program[$tag->target_aktifitas->aktifitas->program->tema->kode_tema]['program'][$tag->target_aktifitas->aktifitas->program->kode_program] = [
                    'kode_program' => $tag->target_aktifitas->aktifitas->program->kode_program,
                    'uraian' => $tag->target_aktifitas->aktifitas->program->uraian,
                    'text' => $tag->target_aktifitas->aktifitas->program->kode_program . ' - ' . $tag->target_aktifitas->aktifitas->program->uraian,
                    'aktifitas' => [],
                ];
            }
            if (!isset($tema_program[$tag->target_aktifitas->aktifitas->program->tema->kode_tema]['program'][$tag->target_aktifitas->aktifitas->program->kode_program]['aktifitas'][$tag->target_aktifitas->aktifitas->kode_aktifitas])) {
                $tema_program[$tag->target_aktifitas->aktifitas->program->tema->kode_tema]['program'][$tag->target_aktifitas->aktifitas->program->kode_program]['aktifitas'][$tag->target_aktifitas->aktifitas->kode_aktifitas] = [
                    'kode_aktifitas' => $tag->target_aktifitas->aktifitas->kode_aktifitas,
                    'uraian' => $tag->target_aktifitas->aktifitas->uraian,
                    'text' => $tag->target_aktifitas->aktifitas->kode_aktifitas . ' - ' . $tag->target_aktifitas->aktifitas->uraian,
                    'target_aktifitas' => [],
                ];
            }
            if (!isset($tema_program[$tag->target_aktifitas->aktifitas->program->tema->kode_tema]['program'][$tag->target_aktifitas->aktifitas->program->kode_program]['aktifitas'][$tag->target_aktifitas->aktifitas->kode_aktifitas]['target_aktifitas'][$tag->target_aktifitas->kode_target_aktifitas])) {
                $tema_program[$tag->target_aktifitas->aktifitas->program->tema->kode_tema]['program'][$tag->target_aktifitas->aktifitas->program->kode_program]['aktifitas'][$tag->target_aktifitas->aktifitas->kode_aktifitas]['target_aktifitas'][$tag->target_aktifitas->kode_target_aktifitas] = [
                    'kode_target_aktifitas' => $tag->target_aktifitas->kode_target_aktifitas,
                    'uraian' => $tag->target_aktifitas->uraian,
                    'text' => $tag->target_aktifitas->kode_target_aktifitas . ' - ' . $tag->target_aktifitas->uraian,
                    'target' => formatNumber($tag->volume) . ' ' . $tag->target_aktifitas->satuan,
                    // 'sumber_pendanaan' => $tag->sumberdana,
                    'pembahasan' => $tag->pembahasan == 'setujui' ? 'Disetujui' : ($tag->pembahasan == 'perbaikan' ? 'Perlu Perbaikan' : 'Tidak Disetujui'),
                    'catatan' => $tag->catatan ? $tag->catatan : '-',
                ];
            }
        }
        // array value $tema_program
        foreach ($tema_program as $k => $tema) {
            $tema_program[$k]['program'] = array_values($tema['program']);
            foreach ($tema_program[$k]['program'] as $kk => $program) {
                $tema_program[$k]['program'][$kk]['aktifitas'] = array_values($program['aktifitas']);
                foreach ($tema_program[$k]['program'][$kk]['aktifitas'] as $kkk => $aktifitas) {
                    $tema_program[$k]['program'][$kk]['aktifitas'][$kkk]['target_aktifitas'] = array_values($aktifitas['target_aktifitas']);
                }
            }
        }
        $tema_program = array_values($tema_program);
        // return $tema_program;
        $fileName = 'rap-' . strtolower(str_replace(' ', '_', $opd->nama_opd));
        $ketuaTimPembahas = TimPembahas::where([
            'role' => 'ketua',
            'tahun' => session('tahun'),
        ])
            ->first();
        $tim_pembahas = [
            'bappeda' => [],
            'opd' => [],
        ];
        foreach ($opd->tim_pembahas as $timBappeda) {
            $tim_pembahas['bappeda'][] = [
                'nama' => $timBappeda->nama,
                'nip' => $timBappeda->nip,
            ];
        }
        foreach ($opd->tim_pembahas_opd as $timOpd) {
            $tim_pembahas['opd'][] = [
                'nama' => $timOpd->nama,
                'nip' => $timOpd->nip,
            ];
        }
        // return $opd;
        // return $tim_pembahas;
        // return $opd->tim_pembahas;
        // return $ketuaTimPembahas;
        $view = view('v1-1.cetak.cetak-rap', [
            'app' => [
                'title' => $fileName,
            ],
            'header_sumberdana' => $request->jenis === 'bg' ? 'DANA OTNOMONI KHUSUS YANG BERSIFAT UMUM ( BLOCK GRANT 1% )' : ($request->jenis === 'sg' ? 'DANA OTONOMI KHUSUS YANG TELAH DITENTUKAN PENGGUNAANNYA ( SPESIFIC GRANT 1,25% )' : 'DANA TAMBAHAN INFRASTRUKTUR ( DTI )'),
            'opd' => $opd,
            'tema_program' => $tema_program,
            'ketua_tim_pembahas' => $ketuaTimPembahas,
            'tim_pembahas' => $tim_pembahas,
            'inlineCss' => file_get_contents(public_path('vendors/bootstrap-5.3.3-dist/css/bootstrap.min.css')),
        ]);
        return $view;
        $view = $view->render();
        $dir = storage_path('app/files/pdf');
        File::ensureDirectoryExists($dir);

        $pdfBinary = Browsershot::html($view)
            ->showBackground()
            ->emulateMedia('screen')
            ->waitUntilLoad()        // ganti: tidak perlu networkidle
            ->timeout(120000)        // 120 detik, opsional
            ->paperSize(8.5, 14, 'in')   // ukuran Legal/F4 dalam inci
            ->landscape()                // atur orientasi landscape
            ->margins(10, 10, 10, 10, 'mm') // margin opsional
            ->pdf();                  // hasilnya dalam bentuk binary string

        return response($pdfBinary)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName  . '.pdf"');
    }
}
