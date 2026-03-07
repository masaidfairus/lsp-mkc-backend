<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case NOT_ACTIVE = 'not_active';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::NOT_ACTIVE => 'Not Active',
        };
    }

}
