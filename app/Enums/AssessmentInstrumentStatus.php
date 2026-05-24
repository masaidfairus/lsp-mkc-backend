<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum AssessmentInstrumentStatus: string implements HasLabel, HasColor
{
    case PENDING  = 'pending';
    case ONGOING = 'ongoing';
    case COMPLETED = 'completed';

    /**
     * Mengambil label.
     *
     * @return ?string
     */
    public function getLabel(): ?string
    {
        return match($this) {
            self::PENDING  => 'Belum Ditinjau',
            self::ONGOING => 'Sedang Ditinjau',
            self::COMPLETED => 'Selesai',
        };
    }

    /**
     * Mengambil color.
     *
     * @return string|array|null
     */
    public function getColor(): string|array|null
    {
        return match($this) {
            self::PENDING => 'danger',
            self::ONGOING => 'warning',
            self::COMPLETED => 'success',
        };
    }
}