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
        Schema::create('kontak', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('nama')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->text('isi')->nullable();
            $table->text('keluhan');
            $table->text('respon')->nullable();
            $table->enum('status', [
                'open',
                'in_progress',
                'resolved',
                'closed',
                'rejected'
            ])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontak');
    }
};
