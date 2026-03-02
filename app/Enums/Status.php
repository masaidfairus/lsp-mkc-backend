<?php

namespace App\Enums;

enum Status: string
{
    case NOT_STARTED = 'not_started';
    case PENDING = 'pending';
    case APPROVED = 'approved';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NOT_STARTED => 'Not Started',
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
        };
    }

}
