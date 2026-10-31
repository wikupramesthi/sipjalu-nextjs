<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('aduan_uuid')->nullable();
            $table->string('judul', 255);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_rencana')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['terjadwal', 'berjalan', 'selesai', 'tertunda'])->default('terjadwal');
            $table->uuid('petugas_uuid')->nullable();
            $table->string('prioritas', 20)->default('sedang');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('aduan_uuid')->references('uuid')->on('aduans')->nullOnDelete();
            $table->foreign('petugas_uuid')->references('uuid')->on('users')->nullOnDelete();
            $table->index(['status', 'tanggal_rencana']);
            $table->index('aduan_uuid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
