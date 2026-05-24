<?php
 
namespace App\Enums;
 
enum AppealStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
 
    /**
     * Mengambil label.
     *
     * @return ?string
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }

    /**
     * Is approved.
     *
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this === self::APPROVED;
    }
}
