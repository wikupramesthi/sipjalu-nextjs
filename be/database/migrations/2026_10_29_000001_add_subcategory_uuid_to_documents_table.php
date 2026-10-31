<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->uuid('subcategory_uuid')->nullable()->after('category_uuid');
            $table->foreign('subcategory_uuid')
                ->references('uuid')
                ->on('document_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['subcategory_uuid']);
            $table->dropColumn('subcategory_uuid');
        });
    }
};
