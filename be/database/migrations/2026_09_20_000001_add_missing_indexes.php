<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aduans', function (Blueprint $table) {
            if (!$this->hasIndex('aduans', 'aduans_kategori_index')) {
                $table->index('kategori', 'aduans_kategori_index');
            }
            if (!$this->hasIndex('aduans', 'aduans_user_uuid_index')) {
                $table->index('user_uuid', 'aduans_user_uuid_index');
            }
            if (!$this->hasIndex('aduans', 'aduans_kategori_status_index')) {
                $table->index(['kategori', 'status'], 'aduans_kategori_status_index');
            }
        });

        Schema::table('visitor_logs', function (Blueprint $table) {
            if (!$this->hasIndex('visitor_logs', 'visitor_logs_device_type_index')) {
                $table->index('device_type', 'visitor_logs_device_type_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('aduans', function (Blueprint $table) {
            $table->dropIndex('aduans_kategori_index');
            $table->dropIndex('aduans_user_uuid_index');
            $table->dropIndex('aduans_kategori_status_index');
        });
        Schema::table('visitor_logs', function (Blueprint $table) {
            $table->dropIndex('visitor_logs_device_type_index');
        });
    }

    private function hasIndex(string $table, string $index): bool
    {
        try {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes($table);
            return isset($indexes[$index]) || isset($indexes[strtolower($index)]);
        } catch (\Throwable) {
            return false;
        }
    }
};
