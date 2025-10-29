<?php

namespace App\Models\Config;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleMonev extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];


    public function getAktifAttribute()
    {
        $mulai   = Carbon::parse($this->mulai);
        $selesai = Carbon::parse($this->selesai);
        $now     = Carbon::now();

        return $now->between($mulai, $selesai);
    }

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_create_id', 'id');
    }

    public function updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_update_id', 'id');
    }
}
