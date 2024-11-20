<?php

namespace App\Models\Nomenklatur;

use App\Models\Tagging\Nomenklatur\OpdTagBidang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class A1Urusan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function tag_opd(): BelongsTo
    {
        return $this->belongsTo(OpdTagBidang::class, 'kode_urusan', 'kode_urusan');
    }

    public function bidang(): HasMany
    {
        return $this->hasMany(A2Bidang::class, 'kode_urusan', 'kode_urusan');
    }

    public function program(): HasMany
    {
        return $this->hasMany(A3Program::class, 'kode_urusan', 'kode_urusan');
    }

    public function kegiatan(): HasMany
    {
        return $this->hasMany(A4Kegiatan::class, 'kode_urusan', 'kode_urusan');
    }

    public function subkegiatan(): HasMany
    {
        return $this->hasMany(A5Subkegiatan::class, 'kode_urusan', 'kode_urusan');
    }
}
