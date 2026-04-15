<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum ValidationResult: string implements HasLabel, HasColor, HasIcon
{
    case MS = 'ms';       // Memenuhi Syarat
    case TMS = 'tms';     // Tidak Memenuhi Syarat
    case Pending = 'pending'; // Default/Gray Stripes

    public function getLabel(): ?string
    {
        return match($this) {
            self::MS => 'Memenuhi Syarat (MS)',
            self::TMS => 'Tidak Memenuhi Syarat (TMS)',
            self::Pending => 'Belum Ditentukan',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::MS => 'heroicon-o-check-circle',
            self::TMS => 'heroicon-o-x-circle',
            self::Pending => 'heroicon-o-minus-circle',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::MS => 'success',
            self::TMS => 'danger',
            self::Pending => 'gray',
        };
    }
}
