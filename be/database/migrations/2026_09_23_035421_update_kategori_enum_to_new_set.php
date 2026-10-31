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
        $mysql = \Illuminate\Support\Facades\DB::getDriverName() === 'mysql';

        // 1. Perluas enum agar bisa tampung nilai lama + baru selama migrasi data
        if ($mysql) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE faqs MODIFY COLUMN kategori ENUM('umum','kepemudaan','keolahragaan','layanan','beasiswa','pengaduan','informasi-umum','infrastruktur-pemeliharaan','pengaduan-permohonan','program-kegiatan') NULL DEFAULT NULL");
        }

        // 2. Mapping lama → baru
        // Informasi Umum <- umum
        // Program & Kegiatan <- kepemudaan, beasiswa (sebagian)
        // Infrastruktur & Pemeliharaan <- keolahragaan
        // Layanan tetap
        // Pengaduan & Permohonan <- pengaduan
        \Illuminate\Support\Facades\DB::table('faqs')->where('kategori','umum')->update(['kategori'=>'informasi-umum']);
        \Illuminate\Support\Facades\DB::table('faqs')->where('kategori','kepemudaan')->update(['kategori'=>'program-kegiatan']);
        \Illuminate\Support\Facades\DB::table('faqs')->where('kategori','keolahragaan')->update(['kategori'=>'infrastruktur-pemeliharaan']);
        \Illuminate\Support\Facades\DB::table('faqs')->where('kategori','beasiswa')->update(['kategori'=>'program-kegiatan']);
        \Illuminate\Support\Facades\DB::table('faqs')->where('kategori','pengaduan')->update(['kategori'=>'pengaduan-permohonan']);
        // layanan sudah sesuai

        // 3. Kecilkan enum hanya ke 5 nilai baru yang diminta
        if ($mysql) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE faqs MODIFY COLUMN kategori ENUM('informasi-umum','layanan','infrastruktur-pemeliharaan','pengaduan-permohonan','program-kegiatan') NULL DEFAULT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $mysql = \Illuminate\Support\Facades\DB::getDriverName() === 'mysql';
        if ($mysql) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE faqs MODIFY COLUMN kategori ENUM('umum','kepemudaan','keolahragaan','layanan','beasiswa','pengaduan') NULL DEFAULT NULL");
        }
        \Illuminate\Support\Facades\DB::table('faqs')->where('kategori','informasi-umum')->update(['kategori'=>'umum']);
        \Illuminate\Support\Facades\DB::table('faqs')->where('kategori','program-kegiatan')->update(['kategori'=>'kepemudaan']);
        \Illuminate\Support\Facades\DB::table('faqs')->where('kategori','infrastruktur-pemeliharaan')->update(['kategori'=>'keolahragaan']);
        \Illuminate\Support\Facades\DB::table('faqs')->where('kategori','pengaduan-permohonan')->update(['kategori'=>'pengaduan']);
    }
};
