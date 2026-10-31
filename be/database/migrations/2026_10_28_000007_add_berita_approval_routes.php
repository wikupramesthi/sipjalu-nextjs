<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function routes(): array
    {
        return [
            'beritas.approve' => 'agendas.approve',
            'beritas.reject' => 'agendas.approve',
            'beritas.mintaRevisi' => 'agendas.approve',
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
                    'description' => 'Akses persetujuan berita.',
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
            ->where('description', 'Akses persetujuan berita.')
            ->delete();
    }
};
