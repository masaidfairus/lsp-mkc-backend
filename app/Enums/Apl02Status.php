<?php

namespace App\Enums;

enum Apl02Status: string
{
    case NOT_STARTED = 'not_started';
    case SUBMITTED = 'submitted';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NOT_STARTED => 'Not Started',
            self::SUBMITTED => 'Sumbitted',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }

}
