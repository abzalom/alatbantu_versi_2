<?php

namespace App\Jobs\SipdRi;

use App\Models\Nomenklatur\A4Kegiatan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SinkronMasterKegiatanSipdRi implements ShouldQueue
{
    use Queueable;

    protected $data;

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
        // Logic to synchronize SIPD RI master kegiatan data
        foreach ($this->data as $kegiatan) {
            // Implement the synchronization logic here
            // For example, update or create kegiatan records in the database
            A4Kegiatan::updateOrCreate(
                $kegiatan['identifier'],
                $kegiatan['attributes']
            );
        }
    }
}
