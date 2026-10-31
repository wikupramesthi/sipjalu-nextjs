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
        Schema::create('album_foto', function (Blueprint $table) {
            $table->uuid('album_uuid');
            $table->uuid('banner_uuid');
            $table->timestamps();

            $table->primary(['album_uuid', 'banner_uuid']);
            $table->index('banner_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('album_foto');
    }
};