<?php

namespace App\Enums;

enum JabatanEnum: string
{
    case KEPALA = 'kepala';
    case DIREKTUR = 'direktur';
    case KEPALA_UNIT = 'kepala_unit';

    function label(): string
    {
        return match ($this) {
            self::KEPALA => 'Kepala',
            self::DIREKTUR => 'Direktur',
            self::KEPALA_UNIT => 'Kepala Unit',
        };
    }
}
