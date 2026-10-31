<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Daftarkan proteksi server-side (RouteMiddleware) untuk semua route
     * bulk-destroy/restore. Tanpa entri ini, route tersebut bebas diakses
     * user login mana pun (tombol UI disembunyikan @can, tapi endpoint
     * tetap terbuka bila URL-nya ditebak).
     */
    private function entries(): array
    {
        return [
            ['route' => 'agendas.bulkDestroy', 'permission_name' => 'agendas.destroy'],
            ['route' => 'documents.bulkDestroy', 'permission_name' => 'documents.destroy'],
            ['route' => 'faq.bulkDestroy', 'permission_name' => 'faq.destroy'],
            ['route' => 'document-categories.bulkDestroy', 'permission_name' => 'document-categories.destroy'],
            ['route' => 'aduans.bulkDestroy', 'permission_name' => 'aduans.destroy'],
            ['route' => 'aduans.bulkRestore', 'permission_name' => 'aduans.restore'],
            ['route' => 'security.audit-log.bulkDestroy', 'permission_name' => 'audit-log.destroy'],
            ['route' => 'security.failed-login.bulkDestroy', 'permission_name' => 'failed-login.destroy'],
            ['route' => 'security.login-activity.bulkDestroy', 'permission_name' => 'login-activity.destroy'],
            ['route' => 'security.login-lockout.bulkDestroy', 'permission_name' => 'login-lockout.destroy'],
        ];
    }

    public function up(): void
    {
        $now = now();

        foreach ($this->entries() as $entry) {
            $exists = DB::table('routes')->where('route', $entry['route'])->exists();

            if (! $exists) {
                DB::table('routes')->insert([
                    'route' => $entry['route'],
                    'permission_name' => $entry['permission_name'],
                    'status' => true,
                    'description' => 'Proteksi hapus massal (dibuat otomatis).',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('routes')
            ->whereIn('route', array_column($this->entries(), 'route'))
            ->where('description', 'Proteksi hapus massal (dibuat otomatis).')
            ->delete();
    }
};
