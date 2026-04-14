<?php

namespace App\Enums;

enum EvidenceStatus: string
{
    case Pending  = 'pending';
    case Valid = 'valid';
    case Invalid = 'invalid';

    public function label(): string
    {
        return match($this) {
            self::Pending  => 'Menunggu',
            self::Valid => 'Disetujui',
            self::Invalid => 'Ditolak',
        };
    }
}