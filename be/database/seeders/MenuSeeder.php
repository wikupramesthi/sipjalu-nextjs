<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ManagementAccess\MenuItem;
use App\Models\ManagementAccess\MenuGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() : void
    {
        $menuMain = MenuGroup::create([
            'name' => 'Main Menu',
            'icon' => 'bxs-dashboard',
            'permission_name' => 'menu.main-menu',
            'position' => 1,
        ]);

        MenuItem::create([
            'name' => 'Dashboard',
            // 'icon' => 'ri-shield-star-line',
            'route' => 'dashboard.index',
            'permission_name' => 'dashboard.index',
            'menu_group_id' => $menuMain->id,
            'position' => 1,
        ]);

        $menuRolePermission = MenuGroup::create([
            'name' => 'Roles & Permissions',
            'icon' => 'bx-shield-quarter',
            'permission_name' => 'menu.role-permission',
            'position' => 2,
        ]);

        MenuItem::create([
            'name' => 'Permissions Management',
            // 'icon' => 'ri-shield-star-line',
            'route' => 'permission.index',
            'permission_name' => 'permission.index',
            'menu_group_id' => $menuRolePermission->id,
            'position' => 1,
        ]);

        MenuItem::create([
            'name' => 'Roles Management',
            // 'icon' => 'ri-shield-user-line',
            'route' => 'role.index',
            'permission_name' => 'role.index',
            'menu_group_id' => $menuRolePermission->id,
            'position' => 2,
        ]);

        $menuAccess = MenuGroup::create([
            'name' => 'Access Management',
            'icon' => 'bx-cog',
            'permission_name' => 'menu.access-management',
            'position' => 3,
        ]);

        MenuItem::create([
            'name' => 'User Management',
            // 'icon' => 'ri-file-user-line',
            'route' => 'user.index',
            'permission_name' => 'user.index',
            'menu_group_id' => $menuAccess->id,
            'position' => 1,
        ]);

        MenuItem::create([
            'name' => 'Route Management',
            // 'icon' => 'ri-settings-2-line',
            'route' => 'route.index',
            'permission_name' => 'route.index',
            'menu_group_id' => $menuAccess->id,
            'position' => 2,
        ]);


        MenuItem::create([
            'name' => 'Menu Management',
            // 'icon' => 'ri-menu-line',
            'route' => 'menu.index',
            'permission_name' => 'menu-group.index',
            'menu_group_id' => $menuAccess->id,
            'position' => 3,
        ]);

        MenuItem::create([
            'name' => 'Menu Website',
            // 'icon' => 'ri-menu-line',
            'route' => 'website-menu.index',
            'permission_name' => 'website-menu.index',
            'menu_group_id' => $menuAccess->id,
            'position' => 4,
        ]);

    }
}
