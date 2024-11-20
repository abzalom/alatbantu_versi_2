<?php

namespace App\Models\Tagging\Otsus;

use App\Models\Data\Opd;
use App\Models\Otsus\Data\B1TemaOtsus;
use App\Models\Otsus\Data\B2ProgramPrioritasOtsus;
use App\Models\Otsus\Data\B3TargetKeluaranStrategisOtsus;
use App\Models\Otsus\Data\B4AktifitasUtamaOtsus;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use App\Models\Rap\RapOtsus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpdTagOtsus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class, 'kode_unik_opd', 'kode_unik_opd');
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

    public function target_aktifitas(): BelongsTo
    {
        return $this->belongsTo(B5TargetAktifitasUtamaOtsus::class, 'kode_target_aktifitas', 'kode_target_aktifitas');
    }

    public function raps(): HasMany
    {
        return $this->hasMany(RapOtsus::class, 'kode_unik_opd_tag_otsus', 'kode_unik_opd_tag_otsus');
    }
}
