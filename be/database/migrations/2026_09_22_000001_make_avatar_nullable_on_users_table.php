<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Kolom avatar dibuat nullable agar registrasi & factory user tidak
     * gagal menyimpan (view sudah punya fallback avatar default).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE `users` SET `avatar` = '' WHERE `avatar` IS NULL");
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar', 255)->nullable(false)->change();
        });
    }
};
