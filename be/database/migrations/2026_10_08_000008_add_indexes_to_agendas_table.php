<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->index('status');
            $table->index('waktu_mulai');
            $table->index('scheduled_at');
            $table->index('is_popular');
            $table->index('jenis_agenda');
        });
    }

    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['waktu_mulai']);
            $table->dropIndex(['scheduled_at']);
            $table->dropIndex(['is_popular']);
            $table->dropIndex(['jenis_agenda']);
            $table->dropIndex(['category_uuid']);
        });
    }
};
