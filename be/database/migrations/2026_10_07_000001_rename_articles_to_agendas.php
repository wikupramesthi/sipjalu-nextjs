<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename modul Artikel menjadi Agenda (lihat kode: Article -> Agenda).
     * Data 241 baris dipertahankan, hanya nama tabel/kolom/FK yang berubah.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // SQLite tak kenal DROP/ADD FOREIGN KEY & CHANGE: RENAME TABLE
            // dan RENAME COLUMN otomatis memperbaiki referensi FK.
            Schema::rename('articles', 'agendas');
            Schema::rename('article_images', 'agenda_images');
            DB::statement('ALTER TABLE `agenda_images` RENAME COLUMN `article_uuid` TO `agenda_uuid`');
            return;
        }

        DB::statement('ALTER TABLE `article_images` DROP FOREIGN KEY `article_images_article_uuid_foreign`');

        Schema::rename('articles', 'agendas');
        Schema::rename('article_images', 'agenda_images');

        DB::statement('ALTER TABLE `agenda_images` CHANGE `article_uuid` `agenda_uuid` CHAR(36) NOT NULL');

        DB::statement('ALTER TABLE `agenda_images` ADD CONSTRAINT `agenda_images_agenda_uuid_foreign` FOREIGN KEY (`agenda_uuid`) REFERENCES `agendas` (`uuid`) ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('ALTER TABLE `agenda_images` RENAME COLUMN `agenda_uuid` TO `article_uuid`');

            Schema::rename('agenda_images', 'article_images');
            Schema::rename('agendas', 'articles');
            return;
        }

        DB::statement('ALTER TABLE `agenda_images` DROP FOREIGN KEY `agenda_images_agenda_uuid_foreign`');

        DB::statement('ALTER TABLE `agenda_images` CHANGE `agenda_uuid` `article_uuid` CHAR(36) NOT NULL');

        Schema::rename('agenda_images', 'article_images');
        Schema::rename('agendas', 'articles');

        DB::statement('ALTER TABLE `article_images` ADD CONSTRAINT `article_images_article_uuid_foreign` FOREIGN KEY (`article_uuid`) REFERENCES `articles` (`uuid`) ON DELETE CASCADE');
    }
};
