<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const NEW_CATEGORIES = [
        'informasi-umum',
        'data-wajib-pajak',
        'data-pelanggan-listrik',
        'potensi-pajak',
        'target-dan-realisasi',
        'pembayaran-pajak',
        'tunggakan-pajak',
        'pengendalian-dan-verifikasi',
        'laporan-dan-statistik',
        'regulasi-dan-ketentuan',
    ];

    private const OLD_CATEGORIES = [
        'tentang-agenda',
        'jadwal',
        'lokasi',
        'publikasi',
        'lainnya',
    ];

    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            $categories = array_merge(self::OLD_CATEGORIES, self::NEW_CATEGORIES);
            DB::statement(
                "ALTER TABLE faqs MODIFY COLUMN kategori ENUM('" . implode("','", $categories) . "') NULL DEFAULT NULL"
            );
        }

        DB::table('faqs')
            ->whereNotNull('kategori')
            ->whereNotIn('kategori', self::NEW_CATEGORIES)
            ->update(['kategori' => 'informasi-umum']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE faqs MODIFY COLUMN kategori ENUM('" . implode("','", self::NEW_CATEGORIES) . "') NULL DEFAULT NULL"
            );
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            $categories = array_merge(self::OLD_CATEGORIES, self::NEW_CATEGORIES);
            DB::statement(
                "ALTER TABLE faqs MODIFY COLUMN kategori ENUM('" . implode("','", $categories) . "') NULL DEFAULT NULL"
            );
        }

        DB::table('faqs')
            ->whereNotNull('kategori')
            ->update(['kategori' => 'tentang-agenda']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE faqs MODIFY COLUMN kategori ENUM('" . implode("','", self::OLD_CATEGORIES) . "') NULL DEFAULT NULL"
            );
        }
    }
};
