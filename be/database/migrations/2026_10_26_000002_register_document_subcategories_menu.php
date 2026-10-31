<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function routePermissions(): array
    {
        return [
            'document-subcategories.index' => 'document-categories.index',
            'document-subcategories.store' => 'document-categories.store',
            'document-subcategories.update' => 'document-categories.update',
            'document-subcategories.destroy' => 'document-categories.destroy',
        ];
    }

    public function up(): void
    {
        $now = now();

        foreach ($this->routePermissions() as $route => $permission) {
            if (! DB::table('routes')->where('route', $route)->exists()) {
                DB::table('routes')->insert([
                    'route' => $route,
                    'permission_name' => $permission,
                    'status' => true,
                    'description' => 'Akses menu subkategori dokumen.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $categoryMenu = DB::table('menu_items')->where('route', 'document-categories.index')->first();
        if ($categoryMenu && ! DB::table('menu_items')->where('route', 'document-subcategories.index')->exists()) {
            DB::table('menu_items')->insert([
                'name' => 'Subkategori Dokumen',
                'icon' => null,
                'route' => 'document-subcategories.index',
                'status' => true,
                'permission_name' => 'document-categories.index',
                'menu_group_id' => $categoryMenu->menu_group_id,
                'position' => DB::table('menu_items')->where('menu_group_id', $categoryMenu->menu_group_id)->max('position') + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('menu_items')->where('route', 'document-subcategories.index')->delete();
        DB::table('routes')->whereIn('route', array_keys($this->routePermissions()))->delete();
    }
};
