<?php

namespace App\Jobs\SipdRi;

use App\Models\Nomenklatur\A3Program;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SinkronMasterProgramSipdRi implements ShouldQueue
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
        // Logic to synchronize SIPD RI master program data
        foreach ($this->data as $program) {
            // Implement the synchronization logic here
            A3Program::updateOrCreate(
                $program['identifier'],
                $program['attributes']
            );
            // For example, update or create program records in the database
        }
    }
}
