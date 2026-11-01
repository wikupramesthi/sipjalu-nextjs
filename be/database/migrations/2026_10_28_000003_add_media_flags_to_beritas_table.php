<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('beritas', 'video')) {
            Schema::table('beritas', function (Blueprint $table) {
                $table->string('video')->nullable()->after('featured_image');
            });
        }

        if (! Schema::hasColumn('beritas', 'is_urgent')) {
            Schema::table('beritas', function (Blueprint $table) {
                $table->boolean('is_urgent')->default(false)->after('is_popular');
            });
        }
    }

    public function down(): void
    {
        $columns = array_values(array_filter(
            ['video', 'is_urgent'],
            fn (string $column): bool => Schema::hasColumn('beritas', $column)
        ));

        if ($columns !== []) {
            Schema::table('beritas', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
