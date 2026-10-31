<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Riwayat audit modul agenda: siapa melakukan apa dan kapan.
     */
    public function up(): void
    {
        Schema::create('agenda_logs', function (Blueprint $table) {
            $table->char('uuid', 36)->primary();
            $table->char('agenda_uuid', 36)->index();
            $table->char('user_uuid', 36)->nullable()->index();
            $table->string('aksi', 30);
            $table->string('catatan', 255)->nullable();
            $table->timestamps();

            $table->foreign('agenda_uuid')->references('uuid')->on('agendas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_logs');
    }
};
