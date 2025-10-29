<?php

namespace App\Models\Config;

use App\Models\Data\Opd;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use App\Models\Scopes\TahunScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy(TahunScope::class)]
class TimPembahasOpd extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get the opd that owns the TimPembahasOpd
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }
}
