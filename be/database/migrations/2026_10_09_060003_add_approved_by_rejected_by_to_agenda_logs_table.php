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
        Schema::table('agenda_logs', function (Blueprint $table) {
            $table->uuid('approved_by')->nullable()->after('user_uuid');
            $table->uuid('rejected_by')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agenda_logs', function (Blueprint $table) {
            //
        });
    }
};
