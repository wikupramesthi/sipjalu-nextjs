<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah status 'pending' untuk alur approval:
     * OPD input -> pending -> admin approve (published) / reject (draft).
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `agendas` MODIFY `status` ENUM('draft','published','scheduled','pending') NOT NULL DEFAULT 'draft'");

            return;
        }

        // SQLite/Postgres: kolom enum disertai CHECK constraint 3 nilai lama
        // (dari tabel `articles`), jadi harus ikut diubah agar 'pending' diterima.
        Schema::table('agendas', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published', 'scheduled', 'pending'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('agendas')->where('status', 'pending')->update(['status' => 'draft']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `agendas` MODIFY `status` ENUM('draft','published','scheduled') NOT NULL DEFAULT 'draft'");

            return;
        }

        Schema::table('agendas', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft')->change();
        });
    }
};
