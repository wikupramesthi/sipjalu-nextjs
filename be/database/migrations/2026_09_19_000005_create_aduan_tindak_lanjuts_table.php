<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aduan_tindak_lanjuts', function (Blueprint $table) {
            $table->uuid('uuid')->primary();

            $table->uuid('aduan_uuid');
            $table->uuid('user_uuid')->nullable();

            $table->enum('status', [
                'menunggu',
                'diverifikasi',
                'diproses',
                'selesai',
                'ditolak',
            ])->nullable();

            $table->text('catatan');
            $table->string('foto_1')->nullable();
            $table->string('foto_2')->nullable();
            $table->dateTime('tanggal')->nullable();

            $table->timestamps();

            $table->foreign('aduan_uuid')
                ->references('uuid')
                ->on('aduans')
                ->cascadeOnDelete();

            $table->foreign('user_uuid')
                ->references('uuid')
                ->on('users')
                ->nullOnDelete();

            $table->index('aduan_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aduan_tindak_lanjuts');
    }
};
