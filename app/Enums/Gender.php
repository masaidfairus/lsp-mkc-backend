<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Gender: string implements HasLabel
{
    case MALE = 'male';
    case FEMALE = 'female';

    /**
     * Mengambil label.
     *
     * @return ?string
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::MALE => 'Laki-laki',
            self::FEMALE => 'Perempuan',
        };
    }

}
