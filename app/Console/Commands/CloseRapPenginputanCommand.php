<?php

namespace App\Console\Commands;

use App\Models\Config\Schedule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CloseRapPenginputanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rap:close-penginputan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kunci penginputan secara otomatis jika jadwal RAP sudah selesai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        DB::transaction(function () use ($now) {
            // Kunci penginputan untuk jadwal RAP yang sudah selesai
            $schedule = Schedule::where('status', true)->first();
            if ($now > $schedule->selesai) {
                $schedule->penginputan = false;
                $schedule->save();
            }
        });
        $this->info('Selesai: penginputan ditutup untuk jadwal yang sudah berakhir.');
        return self::SUCCESS;
    }
}
