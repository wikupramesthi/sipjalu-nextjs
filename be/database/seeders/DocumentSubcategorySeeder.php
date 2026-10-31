<?php

namespace Database\Seeders;

use App\Models\ManagementAccess\MenuItem;
use App\Models\ManagementAccess\Route as ManagedRoute;
use Illuminate\Database\Seeder;

class DocumentSubcategorySeeder extends Seeder
{
    public function run(): void
    {
        $menuItem = MenuItem::where('route', 'document-categories.index')->first();

        if ($menuItem) {
            MenuItem::firstOrCreate(
                [
                    'route' => 'document-subcategories.index',
                    'menu_group_id' => $menuItem->menu_group_id,
                ],
                [
                    'name' => 'Subkategori Dokumen',
                    'status' => true,
                    'permission_name' => 'document-categories.index',
                    'position' => MenuItem::where('menu_group_id', $menuItem->menu_group_id)->max('position') + 1,
                ]
            );
        }

        foreach ($this->routePermissions() as $route => $permission) {
            ManagedRoute::firstOrCreate(
                ['route' => $route],
                ['permission_name' => $permission, 'status' => true, 'description' => 'Akses menu subkategori dokumen.']
            );
        }
    }

    private function routePermissions(): array
    {
        return [
            'document-subcategories.index' => 'document-categories.index',
            'document-subcategories.store' => 'document-categories.store',
            'document-subcategories.update' => 'document-categories.update',
            'document-subcategories.destroy' => 'document-categories.destroy',
        ];
    }
}
