<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_categories', function (Blueprint $table) {
            $table->uuid('parent_uuid')->nullable()->after('uuid');
            $table->foreign('parent_uuid')
                ->references('uuid')
                ->on('document_categories')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('document_categories', function (Blueprint $table) {
            $table->dropForeign(['parent_uuid']);
            $table->dropColumn('parent_uuid');
        });
    }
};
