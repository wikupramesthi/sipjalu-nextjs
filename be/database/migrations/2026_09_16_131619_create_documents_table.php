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
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('category_uuid');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();

            $table->date('published_at')->nullable();
            $table->string('file');
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            $table->foreign('category_uuid')
            ->references('uuid')
            ->on('document_categories')
            ->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
