<?php

namespace App\Models\Otsus\Data;

use App\Models\Rap\RapOtsus;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class B3TargetKeluaranStrategisOtsus extends Model
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

    public function aktifitas(): HasMany
    {
        return $this->hasMany(B4AktifitasUtamaOtsus::class, 'kode_keluaran', 'kode_keluaran');
    }

    public function taggings(): HasMany
    {
        return $this->hasMany(OpdTagOtsus::class, 'kode_keluaran', 'kode_keluaran');
    }

    public function target_aktifitas(): HasMany
    {
        return $this->hasMany(B5TargetAktifitasUtamaOtsus::class, 'kode_keluaran', 'kode_keluaran');
    }

    public function raps(): HasMany
    {
        return $this->hasMany(RapOtsus::class, 'kode_keluaran', 'kode_keluaran');
    }
}
