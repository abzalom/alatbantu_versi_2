<?php

namespace App\Models\Data\Perencanaan;

use App\Models\Nomenklatur\A2Bidang;
use App\Models\TargetIndikatorUrusan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class IndikatorUrusanPemda extends Model
{
    use HasFactory;

    public function target(): HasOne
    {
        return $this->hasOne(TargetIndikatorUrusan::class);
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(A2Bidang::class, 'kode_bidang', 'kode_bidang');
    }
}
