<?php

namespace App\Models\Nomenklatur;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class A2Bidang extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function urusan(): BelongsTo
    {
        return $this->belongsTo(A1Urusan::class, 'kode_urusan', 'kode_urusan');
    }

    public function program(): HasMany
    {
        return $this->hasMany(A3Program::class, 'kode_bidang', 'kode_bidang');
    }

    public function kegiatan(): HasMany
    {
        return $this->hasMany(A4Kegiatan::class, 'kode_bidang', 'kode_bidang');
    }

    public function subkegiatan(): HasMany
    {
        return $this->hasMany(A5Subkegiatan::class, 'kode_bidang', 'kode_bidang');
    }
}
