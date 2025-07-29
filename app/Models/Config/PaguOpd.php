<?php

namespace App\Models\Config;

use App\Models\Scopes\TahunScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy(TahunScope::class)]
class PaguOpd extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
