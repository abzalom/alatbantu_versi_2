<?php

namespace App\Models\Data;

use App\Models\Nomenklatur\A2Bidang;
use App\Models\Nomenklatur\A5Subkegiatan;
use App\Models\Otsus\Data\B5TargetAktifitasUtamaOtsus;
use App\Models\Rap\RapOtsus;
use App\Models\Scopes\TahunScope;
use App\Models\Tagging\Nomenklatur\OpdTagBidang;
use App\Models\Tagging\Otsus\OpdTagOtsus;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[ScopedBy(TahunScope::class)]
class Opd extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getTextAttribute()
    {
        return $this->kode_opd . ' ' . $this->nama_opd;
    }

    /**
     * Get the user that owns the Opd
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    public function tag_bidang(): HasMany
    {
        return $this->hasMany(OpdTagBidang::class, 'kode_unik_opd', 'kode_unik_opd');
    }

    public function tag_otsus(): HasMany
    {
        return $this->hasMany(OpdTagOtsus::class, 'kode_unik_opd', 'kode_unik_opd');
    }

    public function target_aktifitas(): HasManyThrough
    {
        return $this->hasManyThrough(
            B5TargetAktifitasUtamaOtsus::class, // Model tujuan akhir
            OpdTagOtsus::class, // Model perantara
            'kode_unik_opd', // Foreign key di tabel perantara (OpdTagOtsus) yang merujuk ke tabel Opd
            'kode_target_aktifitas', // Foreign key di model tujuan (B5TargetAktifitasUtamaOtsus) yang merujuk ke tabel perantara
            'kode_unik_opd', // Local key di tabel Opd
            'kode_target_aktifitas' // Local key di tabel perantara (OpdTagOtsus) yang merujuk ke model tujuan
        );
    }

    public function subkegiatans(): HasManyThrough
    {
        return $this->hasManyThrough(
            A5Subkegiatan::class, // Model tujuan akhir
            OpdTagBidang::class, // Model perantara
            'kode_opd', // Foreign key di tabel perantara (OpdTagBidang) yang merujuk ke tabel Opd
            'kode_bidang', // Foreign key di model tujuan (A5Subkegiatan) yang merujuk ke tabel perantara
            'kode_opd', // Local key di tabel Opd
            'kode_bidang' // Local key di tabel perantara (OpdTagBidang) yang merujuk ke model tujuan
        );
    }

    public function raps(): HasManyThrough
    {
        return $this->hasManyThrough(
            RapOtsus::class, // Model tujuan akhir
            OpdTagOtsus::class, // Model perantara
            'kode_unik_opd', // Foreign key di tabel perantara (OpdTagOtsus) yang merujuk ke tabel Opd
            'kode_unik_opd_tag_otsus', // Foreign key di model tujuan (RapOtsus) yang merujuk ke tabel perantara
            'kode_unik_opd', // Local key di tabel Opd
            'kode_unik_opd_tag_otsus' // Local key di tabel perantara (OpdTagOtsus) yang merujuk ke model tujuan
        );
    }
}
