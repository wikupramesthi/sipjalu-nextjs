<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ubah kolom jenis_agenda dari varchar menjadi ENUM('offline','online').
     */
    public function up(): void
    {
        // Normalisasi dulu data liar (kalau ada) agar ALTER tidak gagal.
        DB::table('agendas')
            ->whereNotIn('jenis_agenda', ['offline', 'online'])
            ->orWhereNull('jenis_agenda')
            ->update(['jenis_agenda' => 'offline']);

        if (DB::getDriverName() !== 'mysql') {
            // SQLite bertipe dinamis: cukup normalisasi data di atas.
            return;
        }
        DB::statement("ALTER TABLE `agendas` MODIFY COLUMN `jenis_agenda` ENUM('offline','online') NOT NULL DEFAULT 'offline' AFTER `scheduled_at`");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        DB::statement('ALTER TABLE `agendas` MODIFY COLUMN `jenis_agenda` VARCHAR(20) NOT NULL DEFAULT \'offline\' AFTER `scheduled_at`');
    }
};
