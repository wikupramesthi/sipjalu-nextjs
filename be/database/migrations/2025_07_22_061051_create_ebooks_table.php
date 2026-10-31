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
        Schema::create('ebooks', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('judul');
            $table->enum('kategori', [
                'Buku Pelajaran',
                'Buku Cerita Anak',
                'Modul Guru',
                'Panduan Orang Tua',
                'Majalah Sekolah',
                'Keterampilan & Kreativitas',
                'Kesehatan & Terapi',
                'Agama',
                'Umum'
            ])->nullable();
            $table->text('deskripsi')->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->string('isbn', 50)->nullable();
            $table->string('file_path');          // simpan path file (PDF/EPUB)
            $table->string('cover_image')->nullable();
            $table->string('link')->nullable();   // link eksternal (misal Google Drive / viewer)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
