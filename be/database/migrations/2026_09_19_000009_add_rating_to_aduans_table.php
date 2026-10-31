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
        Schema::table('aduans', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating')->nullable()->after('archived_at');
            $table->text('ulasan')->nullable()->after('rating');
            $table->timestamp('rated_at')->nullable()->after('ulasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aduans', function (Blueprint $table) {
            $table->dropColumn(['rating', 'ulasan', 'rated_at']);
        });
    }
};
