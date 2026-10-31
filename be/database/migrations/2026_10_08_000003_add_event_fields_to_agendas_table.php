<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah field pelaksanaan agenda: jenis (offline/online),
     * lokasi, waktu mulai/akhir, dan URL (link online).
     */
    public function up(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->string('jenis_agenda', 20)->default('offline')->after('scheduled_at');
            $table->string('lokasi', 255)->nullable()->after('jenis_agenda');
            $table->dateTime('waktu_mulai')->nullable()->after('lokasi');
            $table->dateTime('waktu_akhir')->nullable()->after('waktu_mulai');
            $table->string('url', 500)->nullable()->after('waktu_akhir');
        });
    }

    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropColumn(['jenis_agenda', 'lokasi', 'waktu_mulai', 'waktu_akhir', 'url']);
        });
    }
};
