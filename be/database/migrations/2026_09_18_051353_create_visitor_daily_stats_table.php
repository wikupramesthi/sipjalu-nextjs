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
        Schema::create('visitor_daily_stats', function (Blueprint $table) {
            $table->date('date')->primary();
            $table->unsignedBigInteger('total_visits')->default(0);
            $table->unsignedBigInteger('unique_visitors')->default(0);
            $table->unsignedBigInteger('desktop_visits')->default(0);
            $table->unsignedBigInteger('mobile_visits')->default(0);
            $table->unsignedBigInteger('tablet_visits')->default(0);
            $table->json('top_pages')->nullable(); // Top 10 pages JSON
            $table->json('top_referrers')->nullable(); // Top 10 referrers JSON
            $table->json('top_countries')->nullable(); // Top 10 countries JSON
            $table->json('browsers')->nullable(); // Browser breakdown JSON
            $table->json('os')->nullable(); // OS breakdown JSON
            $table->timestamps();

            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_daily_stats');
    }
};
