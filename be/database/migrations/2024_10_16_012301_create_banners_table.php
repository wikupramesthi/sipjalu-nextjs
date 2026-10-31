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
        Schema::create('banner', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('link');
            $table->string('gambar');
            $table->enum('posisi', ['slider','pengumuman','infografis','prestasi','popup','mitra', 'lainnya']);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner');
    }
};
