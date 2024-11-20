<?php

namespace App\Models\Otsus\Data;

use App\Models\Rap\RapOtsus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class B1TemaOtsus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getTextAttribute()
    {
        return $this->kode_tema . '. ' . strtoupper($this->uraian);
    }

    public function indikator(): HasMany
    {
        return $this->hasMany(B1IndikatorTemaOtsus::class, 'kode_tema', 'kode_tema');
    }

    public function program(): HasMany
    {
        return $this->hasMany(B2ProgramPrioritasOtsus::class, 'kode_tema', 'kode_tema');
    }

    public function keluaran(): HasMany
    {
        return $this->hasMany(B3TargetKeluaranStrategisOtsus::class, 'kode_tema', 'kode_tema');
    }

    public function aktifitas(): HasMany
    {
        return $this->hasMany(B4AktifitasUtamaOtsus::class, 'kode_tema', 'kode_tema');
    }

    public function target_aktifitas(): HasMany
    {
        return $this->hasMany(B5TargetAktifitasUtamaOtsus::class, 'kode_tema', 'kode_tema');
    }

    public function raps(): HasMany
    {
        return $this->hasMany(RapOtsus::class, 'kode_tema', 'kode_tema');
    }
}
