<?php

namespace App\Models\Data;

use App\Enums\JabatanEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KepalaOpd extends Model
{
    use HasFactory;

    protected $primaryKey = 'nip';      // ubah primary key jadi nip
    public $incrementing = false;       // karena nip bukan auto increment
    protected $keyType = 'string';      // kalau nip berupa string/char
    protected $fillable = [
        'nip',
        'nama',
        'pangkat',
        'jabatan',
        'status_jabatan',
        'kode_unik_opd',
        'tahun',
        'status',
    ];
    protected $casts = [
        'jabatan' => JabatanEnum::class
    ];

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class, 'kode_unik_opd', 'kode_unik_opd');
    }
}
