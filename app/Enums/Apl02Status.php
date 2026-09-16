<?php
 
namespace App\Enums;
 
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
 
enum Apl02Status: string implements HasLabel, HasColor
{
    case NOT_STARTED = 'not_started';
    case SUBMITTED = 'submitted';
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
            self::NOT_STARTED => 'Belum Dimulai',
            self::SUBMITTED => 'Dikirim',
            self::APPROVED => 'Diterima',
            self::REJECTED => 'Tidak Diterima',
        };
    }
 
    /**
     * Mengambil color.
     *
     * @return string|array|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NOT_STARTED => 'gray',
            self::SUBMITTED => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
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
