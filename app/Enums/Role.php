<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case ASESI = 'asesi';
    case ASESOR = 'asesor';
    case VALIDATOR = 'validator';
    case PENYUSUN = 'penyusun';
    case SUPERADMIN = 'superadmin';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::ASESI => 'Asesi',
            self::ASESOR => 'Asesor',
            self::VALIDATOR => 'Validator',
            self::PENYUSUN => 'Penyusun',
            self::SUPERADMIN => 'Superadmin',
        };
    }

}
