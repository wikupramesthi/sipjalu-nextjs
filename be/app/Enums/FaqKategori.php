<?php

namespace App\Enums;

enum FaqKategori: string
{
    case TENTANG_AGENDA = 'tentang-agenda';
    case JADWAL = 'jadwal';
    case LOKASI = 'lokasi';
    case PUBLIKASI = 'publikasi';
    case LAINNYA = 'lainnya';

    public function label(): string
    {
        return match ($this) {
            self::TENTANG_AGENDA => 'Tentang Agenda',
            self::JADWAL => 'Jadwal',
            self::LOKASI => 'Lokasi',
            self::PUBLIKASI => 'Publikasi',
            self::LAINNYA => 'Lainnya',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
