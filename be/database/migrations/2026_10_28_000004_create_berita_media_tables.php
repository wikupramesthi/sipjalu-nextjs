<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_images', function (Blueprint $table) {
            $table->char('uuid', 36)->primary();
            $table->char('berita_uuid', 36)->index();
            $table->string('image_path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('berita_uuid')->references('uuid')->on('beritas')->cascadeOnDelete();
        });

        Schema::create('berita_attachments', function (Blueprint $table) {
            $table->char('uuid', 36)->primary();
            $table->char('berita_uuid', 36)->index();
            $table->string('file_path');
            $table->string('nama_asli');
            $table->unsignedBigInteger('ukuran')->default(0);
            $table->timestamps();

            $table->foreign('berita_uuid')->references('uuid')->on('beritas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_attachments');
        Schema::dropIfExists('berita_images');
    }
};
