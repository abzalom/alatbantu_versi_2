<?php

namespace App\Models\Tagging\Nomenklatur;

use App\Models\Nomenklatur\A1Urusan;
use App\Models\Nomenklatur\A2Bidang;
use App\Models\Nomenklatur\A3Program;
use App\Models\Nomenklatur\A4Kegiatan;
use App\Models\Nomenklatur\A5Subkegiatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpdTagBidang extends Model
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
