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
        Schema::table('articles', function (Blueprint $table) {
            $table->string('tagging')->nullable()->after('views'); // bisa simpan comma separated tags
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft')->after('tagging');
            $table->enum('search_engine', ['index', 'noindex'])->default('index')->after('status');
            $table->softDeletes()->after('updated_at'); // kalau mau soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['tagging', 'status', 'search_engine', 'deleted_at']);
        });
    }
};
