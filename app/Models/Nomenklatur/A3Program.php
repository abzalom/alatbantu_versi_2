<?php

namespace App\Models\Nomenklatur;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class A3Program extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function urusan(): BelongsTo
    {
        return $this->belongsTo(A1Urusan::class, 'kode_urusan', 'kode_urusan');
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(A2Bidang::class, 'kode_bidang', 'kode_bidang');
    }

    public function kegiatan(): HasMany
    {
        return $this->hasMany(A4Kegiatan::class, 'kode_program', 'kode_program');
    }

    public function subkegiatan(): HasMany
    {
        return $this->hasMany(A5Subkegiatan::class, 'kode_program', 'kode_program');
    }
}
