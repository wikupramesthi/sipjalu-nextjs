<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * User memakai UUID sebagai primary key, sehingga kolom morph
     * bawaan paket (bigint) harus disesuaikan. Tanpa ini:
     * - Sanctum createToken() gagal (tokenable_id terpotong),
     * - pemberian permission langsung ke user gagal (model_id terpotong).
     * (model_has_roles sudah uuid sejak awal.)
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            // SQLite bertipe dinamis: string UUID tersimpan utuh di kolom
            // integer tanpa terpotong, jadi tak perlu ubah tipe.
            return;
        }
        DB::statement('ALTER TABLE `personal_access_tokens` MODIFY `tokenable_id` CHAR(36) NOT NULL');
        DB::statement('ALTER TABLE `model_has_permissions` MODIFY `model_id` CHAR(36) NOT NULL');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        DB::statement('ALTER TABLE `model_has_permissions` MODIFY `model_id` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `personal_access_tokens` MODIFY `tokenable_id` BIGINT UNSIGNED NOT NULL');
    }
};
