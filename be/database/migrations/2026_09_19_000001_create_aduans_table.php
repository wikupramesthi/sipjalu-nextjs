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
        Schema::create('aduans', function (Blueprint $table) {
            $table->uuid('uuid')->primary();

            $table->string('nomor_aduan', 50)->unique();
            $table->uuid('user_uuid')->nullable();

            $table->string('kategori', 100);
            $table->string('judul', 255);
            $table->text('isi_aduan');
            $table->string('lokasi', 255)->nullable();

            $table->unsignedBigInteger('kecamatan_id')->nullable();
            $table->unsignedBigInteger('kelurahan_id')->nullable();

            $table->string('foto_1')->nullable();
            $table->string('foto_2')->nullable();
            $table->string('foto_3')->nullable();

            $table->date('tanggal_kejadian')->nullable();
            $table->dateTime('tanggal_pengaduan')->nullable();

            $table->enum('status', [
                'menunggu',
                'diverifikasi',
                'diproses',
                'selesai',
                'ditolak',
            ])->default('menunggu');

            $table->enum('prioritas', [
                'rendah',
                'sedang',
                'tinggi',
                'darurat',
            ])->default('sedang');

            $table->enum('sifat', [
                'biasa',
                'penting',
                'segera',
                'rahasia',
            ])->default('biasa');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();

            $table->foreign('user_uuid')
                ->references('uuid')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('kecamatan_id')
                ->references('id')
                ->on('kecamatans')
                ->nullOnDelete();

            $table->foreign('kelurahan_id')
                ->references('id')
                ->on('kelurahans')
                ->nullOnDelete();

            $table->index(['status', 'prioritas']);
            $table->index('tanggal_pengaduan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aduans');
    }
};
