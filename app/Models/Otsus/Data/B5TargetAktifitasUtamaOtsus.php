<?php

namespace App\Models\Otsus\Data;

use App\Models\Data\Opd;
use App\Models\Rap\RapOtsus;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class B5TargetAktifitasUtamaOtsus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    function getTextAttribute()
    {
        return $this->kode_target_aktifitas . ' ' . $this->uraian;
    }

    public function tema(): BelongsTo
    {
        return $this->belongsTo(B1TemaOtsus::class, 'kode_tema', 'kode_tema');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(B2ProgramPrioritasOtsus::class, 'kode_program', 'kode_program');
    }

    public function keluaran(): BelongsTo
    {
        return $this->belongsTo(B3TargetKeluaranStrategisOtsus::class, 'kode_keluaran', 'kode_keluaran');
    }

    public function aktifitas(): BelongsTo
    {
        return $this->belongsTo(B4AktifitasUtamaOtsus::class, 'kode_aktifitas', 'kode_aktifitas');
    }

    public function opds(): HasManyThrough
    {
        return $this->hasManyThrough(
            Opd::class,
            OpdTagOtsus::class,
            'kode_target_aktifitas',
            'kode_opd',
            'kode_target_aktifitas',
            'kode_opd',
        );
    }

    public function raps(): HasMany
    {
        return $this->hasMany(RapOtsus::class, 'kode_target_aktifitas', 'kode_target_aktifitas');
    }
}
