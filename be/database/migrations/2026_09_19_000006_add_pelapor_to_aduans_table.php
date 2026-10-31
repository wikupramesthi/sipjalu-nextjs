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
        Schema::table('aduans', function (Blueprint $table) {
            $table->string('nama_pelapor', 150)->nullable()->after('is_anonim');
            $table->string('kontak_pelapor', 50)->nullable()->after('nama_pelapor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aduans', function (Blueprint $table) {
            $table->dropColumn(['nama_pelapor', 'kontak_pelapor']);
        });
    }
};
