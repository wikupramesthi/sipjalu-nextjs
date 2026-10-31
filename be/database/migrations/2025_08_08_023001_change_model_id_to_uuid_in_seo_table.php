<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah morph `seo` dari bigint ke uuid.
     *
     * Idempotent: aman dijalankan ulang walau migrasi sempat gagal di tengah,
     * dan aman di fresh install (MySQL/MariaDB maupun SQLite).
     */
    public function up(): void
    {
        // Buang kolom morph lama (bigint) bila ada.
        if (Schema::hasColumn('seo', 'model_id') || Schema::hasColumn('seo', 'model_type')) {
            try {
                Schema::table('seo', function (Blueprint $table) {
                    $table->dropIndex(['model_type', 'model_id']);
                });
            } catch (\Throwable) {
                // indeks mungkin sudah tidak ada
            }

            $columns = array_values(array_filter(
                ['model_id', 'model_type'],
                fn (string $column): bool => Schema::hasColumn('seo', $column),
            ));

            if ($columns !== []) {
                Schema::table('seo', function (Blueprint $table) use ($columns) {
                    $table->dropColumn($columns);
                });
            }
        }

        // Buat ulang sebagai uuid morph bila belum ada.
        if (! Schema::hasColumn('seo', 'model_id') && ! Schema::hasColumn('seo', 'model_type')) {
            Schema::table('seo', function (Blueprint $table) {
                $table->uuidMorphs('model');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->dropMorphColumns();

        if (! $this->columnExists('model_id') && ! $this->columnExists('model_type')) {
            Schema::table('seo', function (Blueprint $table) {
                $table->morphs('model');
            });
        }
    }

    /** Buang indeks + kolom morph lama bila ada. */
    private function dropMorphColumns(): void
    {
        if ($this->columnExists('model_type') || $this->columnExists('model_id')) {
            try {
                Schema::table('seo', function (Blueprint $table) {
                    $table->dropIndex(['model_type', 'model_id']);
                });
            } catch (\Throwable) {
                // Indeks sudah tidak ada — lanjut.
            }
        }

        $columns = array_values(array_filter(
            ['model_id', 'model_type'],
            fn (string $column): bool => $this->columnExists($column),
        ));

        if ($columns !== []) {
            Schema::table('seo', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }

    private function columnExists(string $column): bool
    {
        $connection = Schema::getConnection();

        // MariaDB lewat proxy sempat membuat Schema::hasColumn keliru; pakai
        // information_schema langsung untuk driver MySQL-family.
        if (in_array($connection->getDriverName(), ['mysql', 'mariadb'], true)) {
            return DB::table('information_schema.columns')
                ->where('table_schema', $connection->getDatabaseName())
                ->where('table_name', 'seo')
                ->where('column_name', $column)
                ->exists();
        }

        return Schema::hasColumn('seo', $column);
    }
};
