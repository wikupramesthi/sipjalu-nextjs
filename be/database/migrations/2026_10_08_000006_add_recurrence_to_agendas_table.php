<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Event berulang: anak seri menunjuk ke induk via parent_uuid.
     */
    public function up(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->char('parent_uuid', 36)->nullable()->index()->after('url');
            $table->string('recurrence_rule', 20)->nullable()->after('parent_uuid');
            $table->date('recurrence_end')->nullable()->after('recurrence_rule');
        });
    }

    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropColumn(['parent_uuid', 'recurrence_rule', 'recurrence_end']);
        });
    }
};
