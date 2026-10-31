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
        Schema::create('poll_votes', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('poll_uuid'); 
            $table->string('option');
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->foreign('poll_uuid')
                ->references('uuid')
                ->on('polls')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poll_votes');
    }
};
