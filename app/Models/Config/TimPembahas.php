<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use App\Models\Scopes\TahunScope;
use Database\Factories\Config\TimPembahasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(TahunScope::class)]
class TimPembahas extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return TimPembahasFactory::new();
    }
}
