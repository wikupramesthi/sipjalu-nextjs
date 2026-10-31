<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run() : void
    {
        // User::factory(10)->create();
        $this->call(PermissionSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RouteSeeder::class);
        $this->call(SecuritySeeder::class);

        // Snapshot data terkini: menimpa isi tabel konten/RBAC agar sama
        // dengan kondisi database saat snapshot diambil. Hanya untuk
        // instalasi fresh (migrate:fresh --seed).
        $this->call(SnapshotSeeder::class);
        $this->call(DocumentSubcategorySeeder::class);
        $this->call(BeritaModuleSeeder::class);

        // Selalu akhiri seeding dengan cache permission yang segar.
        // Cache basi = semua cek $user->can() gagal = halaman admin 404/500 massal.
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
