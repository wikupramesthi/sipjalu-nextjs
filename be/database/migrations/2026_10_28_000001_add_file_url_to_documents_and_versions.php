<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('file', 2048)->nullable()->change();
            $table->string('file_url', 2048)->nullable()->after('file');
        });

        Schema::table('document_versions', function (Blueprint $table) {
            $table->string('file_url', 2048)->nullable()->after('file');
        });
    }

    public function down(): void
    {
        DB::table('documents')
            ->whereNull('file')
            ->whereNotNull('file_url')
            ->update(['file' => DB::raw('file_url')]);

        DB::table('document_versions')
            ->whereNull('file')
            ->whereNotNull('file_url')
            ->update(['file' => DB::raw('file_url')]);

        Schema::table('document_versions', function (Blueprint $table) {
            $table->dropColumn('file_url');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('file_url');
            $table->string('file', 2048)->nullable(false)->change();
        });
    }
};
