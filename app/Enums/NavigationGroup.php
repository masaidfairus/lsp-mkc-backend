<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum NavigationGroup: string implements HasLabel
{
    case APL = 'APL';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::APL => 'APL',
        };
    }
}
