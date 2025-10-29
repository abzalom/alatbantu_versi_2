<?php

namespace App\Models\Config;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function user_create(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function user_update(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function getAktifAttribute()
    {
        $mulai   = Carbon::parse($this->mulai);
        $selesai = Carbon::parse($this->selesai);
        $now     = Carbon::now();

        return $now->between($mulai, $selesai);
    }
}
