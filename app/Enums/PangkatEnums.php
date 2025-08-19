<?php

namespace App\Enums;

enum PangkatEnums: string
{
    case JuruMuda = 'Juru Muda (I/a)';
    case JuruMudaI = 'Juru Muda Tingkat I (I/b)';
    case Juru = 'Juru (I/c)';
    case JuruI = 'Juru Tingkat I (I/d)';
    case PengaturMuda = 'Pengatur Muda (II/a)';
    case PengaturMudaI = 'Pengatur Muda Tingkat I (II/b)';
    case Pengatur = 'Pengatur (II/c)';
    case PengaturI = 'Pengatur Tingkat I (II/d)';
    case PenataMuda = 'Penata Muda (III/a)';
    case PenataMudaI = 'Penata Muda Tingkat I (III/b)';
    case Penata = 'Penata (III/c)';
    case PenataI = 'Penata Tingkat I (III/d)';
    case Pembina = 'Pembina (IV/a)';
    case PembinaI = 'Pembina Tingkat I (IV/b)';
    case PembinaUtamaMuda = 'Pembina Utama Muda (IV/c)';
    case PembinaUtamaMadya = 'Pembina Utama Madya (IV/d)';
    case PembinaUtama = 'Pembina Utama (IV/e)';
}
