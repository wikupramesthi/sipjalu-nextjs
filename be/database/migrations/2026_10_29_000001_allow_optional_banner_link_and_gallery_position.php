<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            "ALTER TABLE banner MODIFY link VARCHAR(255) NULL, " .
            "MODIFY posisi ENUM('slider','pengumuman','infografis','prestasi','galeri','popup','mitra','lainnya') NOT NULL"
        );
    }

    public function down(): void
    {
        DB::table('banner')->whereNull('link')->update(['link' => '']);
        DB::table('banner')->where('posisi', 'galeri')->update(['posisi' => 'prestasi']);

        DB::statement(
            "ALTER TABLE banner MODIFY link VARCHAR(255) NOT NULL, " .
            "MODIFY posisi ENUM('slider','pengumuman','infografis','prestasi','popup','mitra','lainnya') NOT NULL"
        );
    }
};
