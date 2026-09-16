<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum EvidenceStatus: string implements HasLabel, HasColor
{
    case Pending  = 'pending';
    case Valid = 'valid';
    case Invalid = 'invalid';

    public function getLabel(): ?string
    {
        return match($this) {
            self::Pending  => 'Belum Ditinjau',
            self::Valid => 'Ada',
            self::Invalid => 'Tidak Ada',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::Pending => 'gray',
            self::Valid => 'success',
            self::Invalid => 'danger',
        };
    }
}