<?php

namespace App\Enums;

enum FaqKategori: string
{
    case INFORMASI_UMUM = 'informasi-umum';
    case DATA_WAJIB_PAJAK = 'data-wajib-pajak';
    case DATA_PELANGGAN_LISTRIK = 'data-pelanggan-listrik';
    case POTENSI_PAJAK = 'potensi-pajak';
    case TARGET_DAN_REALISASI = 'target-dan-realisasi';
    case PEMBAYARAN_PAJAK = 'pembayaran-pajak';
    case TUNGGAKAN_PAJAK = 'tunggakan-pajak';
    case PENGENDALIAN_DAN_VERIFIKASI = 'pengendalian-dan-verifikasi';
    case LAPORAN_DAN_STATISTIK = 'laporan-dan-statistik';
    case REGULASI_DAN_KETENTUAN = 'regulasi-dan-ketentuan';

    public function label(): string
    {
        return match ($this) {
            self::INFORMASI_UMUM => 'Informasi Umum',
            self::DATA_WAJIB_PAJAK => 'Data Wajib Pajak',
            self::DATA_PELANGGAN_LISTRIK => 'Data Pelanggan Listrik',
            self::POTENSI_PAJAK => 'Potensi Pajak',
            self::TARGET_DAN_REALISASI => 'Target dan Realisasi',
            self::PEMBAYARAN_PAJAK => 'Pembayaran Pajak',
            self::TUNGGAKAN_PAJAK => 'Tunggakan Pajak',
            self::PENGENDALIAN_DAN_VERIFIKASI => 'Pengendalian dan Verifikasi',
            self::LAPORAN_DAN_STATISTIK => 'Laporan dan Statistik',
            self::REGULASI_DAN_KETENTUAN => 'Regulasi dan Ketentuan',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
