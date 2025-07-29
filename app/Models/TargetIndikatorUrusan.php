<?php

namespace App\Models;

use App\Models\Scopes\TahunScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(TahunScope::class)]
class TargetIndikatorUrusan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
