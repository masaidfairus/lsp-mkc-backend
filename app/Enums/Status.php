<?php

namespace App\Enums;

enum Status: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
        };
    }

}
