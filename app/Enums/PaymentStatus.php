<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PENDING_VERIFICATION = 'pending_verification';
    case PAID = 'paid';
    case REJECTED = 'rejected';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::UNPAID => 'Unpaid',
            self::PENDING_VERIFICATION => 'Pending Verification',
            self::PAID => 'Paid',
            self::REJECTED => 'Rejected',
        };
    }
}
