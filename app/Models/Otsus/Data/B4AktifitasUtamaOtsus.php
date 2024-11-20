<?php

namespace App\Models\Otsus\Data;

use App\Models\Rap\RapOtsus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class B4AktifitasUtamaOtsus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

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

    public function target_aktifitas(): HasMany
    {
        return $this->hasMany(B5TargetAktifitasUtamaOtsus::class, 'kode_aktifitas', 'kode_aktifitas');
    }

    public function raps(): HasMany
    {
        return $this->hasMany(RapOtsus::class, 'kode_aktifitas', 'kode_aktifitas');
    }
}
