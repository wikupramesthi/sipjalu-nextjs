<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_versions', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('document_uuid');
            $table->string('title');
            $table->string('file')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('keterangan')->nullable();
            $table->uuid('user_uuid')->nullable();
            $table->unsignedInteger('versi')->default(1);
            $table->timestamps();

            $table->foreign('document_uuid')->references('uuid')->on('documents')->cascadeOnDelete();
            $table->foreign('user_uuid')->references('uuid')->on('users')->nullOnDelete();
            $table->index(['document_uuid', 'versi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_versions');
    }
};
