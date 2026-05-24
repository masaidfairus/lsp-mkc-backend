<?php

namespace App\Enums;

enum Result: string
{
    case KOMPETEN = 'kompeten';
    case BELUM_KOMPETEN = 'belum_kompeten';

    /**
     * Mengambil label.
     *
     * @return ?string
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::KOMPETEN => 'Kompeten',
            self::BELUM_KOMPETEN => 'Belum Kompeten',
        };
    }

}
