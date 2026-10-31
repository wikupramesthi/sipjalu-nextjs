<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kepemilikan dokumen: OPD hanya melihat/mengelola miliknya sendiri.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->uuid('user_uuid')->nullable()->after('uuid');
            $table->foreign('user_uuid')->references('uuid')->on('users')->nullOnDelete();
            $table->index('user_uuid');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['user_uuid']);
            $table->dropIndex(['user_uuid']);
            $table->dropColumn('user_uuid');
        });
    }
};
