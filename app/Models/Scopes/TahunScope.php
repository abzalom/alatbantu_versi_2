<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TahunScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $tahun = session('tahun') ?? now()->year;

        // Prefix pakai nama tabel model terkait
        $table = $model->getTable();

        // Jika semua model yang memakai scope PASTI punya kolom 'tahun':
        $builder->where("$table.tahun", $tahun);

        // Kalau tidak semua model punya kolom 'tahun', pakai opt-in flag:
        // if (property_exists($model, 'usesTahun') && $model->usesTahun) {
        //     $builder->where("$table.tahun", $tahun);
        // }
    }
}
