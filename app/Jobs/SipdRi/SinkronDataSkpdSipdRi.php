<?php

namespace App\Jobs\SipdRi;

use App\Models\Data\Opd;
use App\Models\Tagging\Nomenklatur\OpdTagBidang;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SinkronDataSkpdSipdRi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels; // tambah traits ini

    public array $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();
        try {
            // Logic to synchronize SIPD RI master OPD data
            foreach ($this->data as $opd) {
                Opd::updateOrCreate(
                    [
                        'kode_unik_opd' => $opd['kode_unik_opd']
                    ],
                    [
                        'kode_opd' => $opd['kode_opd'],
                        'nama_opd' => $opd['nama_opd'],
                        'tahun' => $opd['tahun'],
                    ]
                );
                // insert or update opd_tag_bidang
                foreach ($opd['bidangs'] as $bidang) {
                    OpdTagBidang::updateOrCreate(
                        [
                            'kode_unik_opd_tag_bidang' => $bidang['kode_unik_opd_tag_bidang'],
                        ],
                        [
                            'kode_unik_opd' => $opd['kode_unik_opd'],
                            'kode_opd' => $opd['kode_opd'],
                            'kode_urusan' => $bidang['kode_urusan'],
                            'kode_bidang' => $bidang['kode_bidang'],
                            'tahun' => $opd['tahun'],
                        ]
                    );
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sinkron OPD gagal: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e; // biar kelihatan sebagai failed di queue
        }
    }
}
