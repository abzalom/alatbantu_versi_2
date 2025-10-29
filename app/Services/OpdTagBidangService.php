<?php

namespace App\Services;

use App\Models\Data\Opd;
use Illuminate\Support\Facades\DB;

class OpdTagBidangService
{
    /**
     * Update tag_bidang untuk sebuah OPD.
     *
     * @param int $opdId
     * @param array $kodeBidangs  // array of A2Bidang IDs
     * @return array summary
     * @throws \Throwable
     */

    public function syncTagBidang(int $opdId, array $kodeBidangs)
    {
        return DB::transaction(function () use ($opdId, $kodeBidangs) {
            $opd = Opd::with([
                'tag_bidang' => fn($q) => $q->withTrashed(),
            ])->findOrFail($opdId);
        });
    }
}
