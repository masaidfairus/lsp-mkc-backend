<?php

namespace App\Enums;

enum CertificationPurpose: string
{
    case SERTIFIKASI = 'sertifikasi';
    case PKT = 'pengakuan_kompetensi_terkini';
    case RPL = 'rekognisi_pembelajaran_lampau';
    case LAINNYA = 'lainnya';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SERTIFIKASI => 'Sertifikasi',
            self::PKT => 'Pengakuan Kompetensi Terkini',
            self::RPL => 'Rekognisi Pembelajaran Lampau',
            self::LAINNYA => 'Lainnya',
        };
    }

}
