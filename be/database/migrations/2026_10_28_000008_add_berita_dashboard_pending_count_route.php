<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::table('routes')->where('route', 'dashboard.beritas.pendingCount')->exists()) {
            DB::table('routes')->insert([
                'route' => 'dashboard.beritas.pendingCount',
                'permission_name' => 'agendas.index',
                'status' => true,
                'description' => 'Akses jumlah berita yang menunggu persetujuan di dashboard.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('routes')
            ->where('route', 'dashboard.beritas.pendingCount')
            ->where('description', 'Akses jumlah berita yang menunggu persetujuan di dashboard.')
            ->delete();
    }
};
