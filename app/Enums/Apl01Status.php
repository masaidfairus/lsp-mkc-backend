<?php

namespace App\Enums;

enum Apl01Status: string
{
    case NOT_STARTED = 'not_started';
    case PENDING = 'pending';
    case PAID = 'paid';
    case APPROVED = 'approved';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NOT_STARTED => 'Not Started',
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::APPROVED => 'Approved',
        };
    }

}
