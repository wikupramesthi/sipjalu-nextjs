<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lampiran dokumen agenda (TOR, undangan, rundown, dsb).
     */
    public function up(): void
    {
        Schema::create('agenda_attachments', function (Blueprint $table) {
            $table->char('uuid', 36)->primary();
            $table->char('agenda_uuid', 36)->index();
            $table->string('file_path', 255);
            $table->string('nama_asli', 255);
            $table->unsignedBigInteger('ukuran')->default(0);
            $table->timestamps();

            $table->foreign('agenda_uuid')->references('uuid')->on('agendas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_attachments');
    }
};
