<?php

namespace App\Models\Otsus\Data;

use App\Models\Rap\RapOtsus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class B2ProgramPrioritasOtsus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getTextAttribute()
    {
        return $this->kode_tema . '. ' . strtoupper($this->uraian);
    }

    public function tema(): BelongsTo
    {
        return $this->belongsTo(B1TemaOtsus::class, 'kode_tema', 'kode_tema');
    }

    public function keluaran(): HasMany
    {
        return $this->hasMany(B3TargetKeluaranStrategisOtsus::class, 'kode_program', 'kode_program');
    }

    public function aktifitas(): HasMany
    {
        return $this->hasMany(B4AktifitasUtamaOtsus::class, 'kode_program', 'kode_program');
    }

    public function target_aktifitas(): HasMany
    {
        return $this->hasMany(B5TargetAktifitasUtamaOtsus::class, 'kode_program', 'kode_program');
    }

    public function raps(): HasMany
    {
        return $this->hasMany(RapOtsus::class, 'kode_program', 'kode_program');
    }
}
