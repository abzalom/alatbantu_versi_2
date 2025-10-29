<?php

namespace App\Observers;

use App\Models\Tagging\Nomenklatur\OpdTagBidang;

class OpdTagBidangObserver
{
    /**
     * Handle the OpdTagBidang "created" event.
     */
    public function created(OpdTagBidang $opdTagBidang): void
    {
        // return;
    }

    /**
     * Handle the OpdTagBidang "updated" event.
     */
    public function updated(OpdTagBidang $opdTagBidang): void
    {
        // return;
    }

    /**
     * Handle the OpdTagBidang "deleted" event.
     */
    public function deleted(OpdTagBidang $opdTagBidang): void
    {
        // if ($opdTagBidang->trashed()) return;
        // Pastikan ada kode unik
        if (! $opdTagBidang->kode_unik_opd_tag_bidang) {
            return;
        }

        // Hapus (soft delete) relasi RapOtsus
        $opdTagBidang->raps()->delete();
    }

    /**
     * Handle the OpdTagBidang "restored" event.
     */
    public function restored(OpdTagBidang $opdTagBidang): void
    {
        // $kodeUnik = $opdTagBidang->kode_unik_opd_tag_bidang;
        // if (!$kodeUnik) return;

        // // Restore related rapOtsus (yang soft-deleted)
        // $opdTagBidang->raps()->withTrashed()->restore();
    }

    /**
     * Handle the OpdTagBidang "force deleted" event.
     */
    public function forceDeleted(OpdTagBidang $opdTagBidang): void
    {
        // return;
    }
}
