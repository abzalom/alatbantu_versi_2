<?php

namespace App\Models\Nomenklatur;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class A5Subkegiatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    function getTextAttribute()
    {
        return $this->kode_subkegiatan . ' ' . $this->uraian;
    }

    public function urusan(): BelongsTo
    {
        return $this->belongsTo(A1Urusan::class, 'kode_urusan', 'kode_urusan');
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(A2Bidang::class, 'kode_bidang', 'kode_bidang');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(A3Program::class, 'kode_program', 'kode_program');
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(A4Kegiatan::class, 'kode_kegiatan', 'kode_kegiatan');
    }
}
