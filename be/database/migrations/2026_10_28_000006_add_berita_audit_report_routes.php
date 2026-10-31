<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function routes(): array
    {
        return [
            'beritas.logAudit' => 'agendas.index',
            'beritas.laporan' => 'agendas.index',
            'beritas.laporanExcel' => 'agendas.index',
            'beritas.laporanPptx' => 'agendas.index',
        ];
    }

    public function up(): void
    {
        $now = now();
        foreach ($this->routes() as $route => $permission) {
            if (! DB::table('routes')->where('route', $route)->exists()) {
                DB::table('routes')->insert([
                    'route' => $route,
                    'permission_name' => $permission,
                    'status' => true,
                    'description' => 'Akses audit dan laporan berita.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('routes')
            ->whereIn('route', array_keys($this->routes()))
            ->where('description', 'Akses audit dan laporan berita.')
            ->delete();
    }
};
