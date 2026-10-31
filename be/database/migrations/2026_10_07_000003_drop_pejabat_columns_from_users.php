<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus kolom kepegawaian/pejabat dari users (modul pegawai dihapus total).
     */
    public function up(): void
    {
        // SQLite me-rebuild tabel per dropColumn dan gagal bila kolom
        // terindeks ikut dibuang: cabut indeksnya dulu, lalu drop per kolom.
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['nip']);
            });
        } catch (\Exception) {
            // Indeks sudah tidak ada — lanjut.
        }

        foreach (['nip', 'is_pejabat', 'urutan_pejabat', 'unit_kerja', 'riwayat'] as $column) {
            if (! Schema::hasColumn('users', $column)) {
                continue;
            }
            Schema::table('users', function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip', 30)->nullable()->after('no_hp');
            $table->boolean('is_pejabat')->nullable()->after('agama');
            $table->integer('urutan_pejabat')->nullable()->after('is_pejabat');
            $table->string('unit_kerja')->nullable()->after('urutan_pejabat');
            $table->text('riwayat')->nullable()->after('unit_kerja');
        });
    }
};
