<?php

namespace Database\Seeders;

use App\Models\ManagementAccess\MenuItem;
use App\Models\ManagementAccess\Route as ManagedRoute;
use Illuminate\Database\Seeder;

class BeritaModuleSeeder extends Seeder
{
    public function run(): void
    {
        $agendaMenu = MenuItem::where('route', 'agendas.index')->first();

        if ($agendaMenu) {
            MenuItem::firstOrCreate(
                [
                    'route' => 'beritas.index',
                    'menu_group_id' => $agendaMenu->menu_group_id,
                ],
                [
                    'name' => 'Berita',
                    'status' => true,
                    'permission_name' => 'agendas.index',
                    'position' => MenuItem::where('menu_group_id', $agendaMenu->menu_group_id)->max('position') + 1,
                ]
            );
        }

        foreach ($this->routePermissions() as $route => $permission) {
            ManagedRoute::firstOrCreate(
                ['route' => $route],
                [
                    'permission_name' => $permission,
                    'status' => true,
                    'description' => 'Akses modul berita.',
                ]
            );
        }
    }

    private function routePermissions(): array
    {
        return [
            'beritas.index' => 'agendas.index',
            'beritas.create' => 'agendas.create',
            'beritas.store' => 'agendas.store',
            'beritas.show' => 'agendas.index',
            'beritas.edit' => 'agendas.update',
            'beritas.update' => 'agendas.update',
            'beritas.destroy' => 'agendas.destroy',
            'beritas.bulkDestroy' => 'agendas.destroy',
            'beritas.bulkRestore' => 'agendas.destroy',
            'beritas.restore' => 'agendas.destroy',
            'beritas.forceDestroy' => 'agendas.destroy',
            'beritas.export' => 'agendas.index',
            'beritas.import' => 'agendas.create',
            'beritas.importPreview' => 'agendas.store',
            'beritas.importConfirm' => 'agendas.store',
            'beritas.importTemplate' => 'agendas.index',
            'beritas.logAudit' => 'agendas.index',
            'beritas.laporan' => 'agendas.index',
            'beritas.laporanExcel' => 'agendas.index',
            'beritas.laporanPptx' => 'agendas.index',
            'beritas.approve' => 'agendas.approve',
            'beritas.reject' => 'agendas.approve',
            'beritas.mintaRevisi' => 'agendas.approve',
            'dashboard.beritas.pendingCount' => 'agendas.index',
        ];
    }
}
