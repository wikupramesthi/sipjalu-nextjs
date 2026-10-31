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
        Schema::table('users', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('agama', [
                'Islam',
                'Kristen',
                'Katolik',
                'Hindu',
                'Buddha',
                'Konghucu',
                'Lainnya'
            ])->nullable();
            $table->string('jabatan')->nullable();
            $table->timestamp('is_active')->nullable();
            $table->enum('kepegawaian', [
                'asn',
                'honorer',
                'magang',
                'lainnya'
            ])->nullable();
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->onDelete('set null');
            $table->foreign('kelurahan_id')->references('id')->on('kelurahans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['kelurahan_id']);
            $table->dropColumn([
                'nisn',
                'nama_lengkap',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'agama',
                'alamat',
                'file_pendukung',
                'kecamatan_id',
                'kelurahan_id'
            ]);
        });
    }
};
