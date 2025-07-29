<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleMonev extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
}
