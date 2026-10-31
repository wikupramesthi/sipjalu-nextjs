<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function routes(): array
    {
        return [
            'beritas.bulkDestroy' => 'agendas.destroy',
            'beritas.bulkRestore' => 'agendas.destroy',
            'beritas.restore' => 'agendas.destroy',
            'beritas.forceDestroy' => 'agendas.destroy',
            'beritas.export' => 'agendas.index',
            'beritas.import' => 'agendas.create',
            'beritas.importPreview' => 'agendas.store',
            'beritas.importConfirm' => 'agendas.store',
            'beritas.importTemplate' => 'agendas.index',
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
                    'description' => 'Akses arsip, impor, dan ekspor berita.',
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
            ->where('description', 'Akses arsip, impor, dan ekspor berita.')
            ->delete();
    }
};
