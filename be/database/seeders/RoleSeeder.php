<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() : void
    {
        Model::unguard();
        //permission for user
        // $permissionUser = [
        //     'menu.main-menu',
        //     'dashboard.index',
        // ];

        $superadmin = Role::create(['name' => 'super-admin',]);
        $user = Role::create(['name' => 'user']);

        $makeSuperAdmin = User::factory()->create([
            'uuid' => (string) Str::uuid(),
            'name' => 'super-admin',
            'avatar' => 'sneat/assets/img/avatars/1.png',
            'email' => 'super@admin.com',
            'password' => User::DEFAULT_PASSWORD,
        ]);
        $makeUser = User::factory()->create([
            'uuid' => (string) Str::uuid(),
            'name' => 'user',
            'avatar' => 'sneat/assets/img/avatars/5.png',
            'email' => 'user@user.com',
            'password' => User::DEFAULT_PASSWORD,
        ]);

        $makeSuperAdmin->assignRole($superadmin);
        $makeUser->assignRole($user);

        $superadmin->givePermissionTo(Permission::all());
        // $admin->givePermissionTo([$permissionAdmin]);
        // $user->givePermissionTo([
        //     $permissionUser,
        // ]);
    }
}






