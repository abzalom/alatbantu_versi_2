<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RapRipppCollection extends ResourceCollection
{
    public static $wrap = null;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        $grouped = $this->collection->groupBy(fn($rap) => $rap->tagging?->target_aktifitas?->kode_target_aktifitas);

        return $grouped->map(function ($raps, $kode) {
            $tagging = optional($raps->first()->tagging);
            $target = optional($tagging->target_aktifitas);

            return [
                'id_tagging' => $tagging?->id,
                'kode_target_aktifitas' => $kode,
                'uraian' => $target?->uraian,
                'satuan' => $target?->satuan,
                'volume' => $tagging?->volume,
                'raps' => RapRipppResource::collection($raps),
            ];
        })->values()->all(); // ->all() biar array, bukan Collection
    }
}
