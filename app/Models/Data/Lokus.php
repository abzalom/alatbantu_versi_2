<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    function getTextAttribute()
    {
        return $this->kecamatan . ' | ' . $this->kampung;
    }
}
