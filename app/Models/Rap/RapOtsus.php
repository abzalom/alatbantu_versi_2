<?php

namespace App\Models\Rap;

use App\Models\Data\Opd;
use App\Models\Nomenklatur\A5Subkegiatan;
use App\Models\Nomenklatur\NomenklaturSikd;
use App\Models\Otsus\Data\B1TemaOtsus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Otsus\Data\B4AktifitasUtamaOtsus;
use App\Models\Otsus\Data\B2ProgramPrioritasOtsus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Otsus\Data\B3TargetKeluaranStrategisOtsus;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class RapOtsus extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'dana_lain' => 'array',
        'lokus' => 'array',
    ];

    public function getTextSubkegiatan()
    {
        return $this->kode_subkegiatan . ' ' . $this->nama_subkegiatan;
    }

    public function getKinerjaSubkegiatanAttribute()
    {
        return $this->vol_subkeg . ' ' . $this->satuan_subkegiatan;
    }

    public function getDanaLainTextAttribute()
    {
        $jsonData = json_decode($this->dana_lain, true);
        if (is_array($jsonData) && !empty($jsonData)) {
            return collect($jsonData)->map(function ($item) {
                return $item['uraian'];
            })->implode(', ');
        }

        return null;
    }

    function getLokasiTextAttribute()
    {
        $jsonData = json_decode($this->lokus, true);
        if (is_array($jsonData) && !empty($jsonData)) {
            return collect($jsonData)->map(function ($item) {
                return $item['kampung'];
            })->implode(', ');
        }

        return null;
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class, 'kode_unik_opd', 'kode_unik_opd');
    }

    public function tagging(): BelongsTo
    {
        return $this->belongsTo(OpdTagOtsus::class, 'kode_unik_opd_tag_otsus', 'kode_unik_opd_tag_otsus');
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

    public function subkegiatan(): BelongsTo
    {
        return $this->belongsTo(A5Subkegiatan::class, 'kode_subkegiatan', 'kode_subkegiatan');
    }

    public function nomen_sikd(): BelongsTo
    {
        return $this->belongsTo(NomenklaturSikd::class, 'kode_unik_sikd', 'kode_unik_subkegiatan');
    }
}
