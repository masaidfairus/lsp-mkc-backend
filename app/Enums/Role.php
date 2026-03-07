<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case ASESI = 'asesi';
    case ASESOR = 'asesor';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::ASESI => 'Asesi',
            self::ASESOR => 'Asesor',
        };
    }

}
