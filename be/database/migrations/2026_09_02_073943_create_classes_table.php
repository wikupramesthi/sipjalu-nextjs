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
        Schema::create('classes', function (Blueprint $table) {
            $table->uuid('uuid')->primary();

            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();

            // Harga jika membeli kelas langsung
            $table->decimal('price', 15, 2)->default(0);

            // Quota yang dikurangi dari membership
            $table->unsignedInteger('quota_cost')->default(1);

            // Instructor dari tabel users
            $table->uuid('instructor_uuid')->nullable();

            $table->enum('is_active', [
                'active',
                'inactive',
            ])->default('active');

            $table->timestamps();

            $table->foreign('instructor_uuid')
                ->references('uuid')
                ->on('users')
                ->nullOnDelete();

            $table->index('instructor_uuid');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
