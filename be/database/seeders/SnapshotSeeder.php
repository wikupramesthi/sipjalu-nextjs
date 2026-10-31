<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Snapshot isi database per 2026-10-12.
 *
 * `php artisan migrate:fresh --seed` menghasilkan data menyerupai
 * kondisi database saat snapshot diambil (user, role, agenda,
 * identitas website/hero, menu, wilayah, konten, dsb.).
 *
 * PERHATIAN: sync() MENGHAPUS seluruh isi tabel yang dikelolanya
 * sebelum insert ulang. Hanya untuk instalasi fresh — jangan
 * dijalankan di database produksi yang sudah berisi data operasi.
 * Tabel runtime (cache, sessions, jobs, logs, visitor, dsb.)
 * sengaja tidak ikut snapshot.
 */
class SnapshotSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        try {
            $this->sync('roles', [
                ['id' => 1, 'name' => 'super-admin', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 2, 'name' => 'user', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 3, 'name' => 'admin', 'guard_name' => 'web', 'created_at' => '2024-09-27 01:40:58', 'updated_at' => '2024-09-27 01:40:58'],
                ['id' => 5, 'name' => 'opd', 'guard_name' => 'web', 'created_at' => '2025-07-21 13:29:16', 'updated_at' => '2026-10-07 13:12:14'],
            ]);
            $this->sync('permissions', [
                ['id' => 1, 'name' => 'menu.main-menu', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 2, 'name' => 'menu.role-permission', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 3, 'name' => 'menu.access-management', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 4, 'name' => 'dashboard.index', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 5, 'name' => 'user.index', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 6, 'name' => 'user.store', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 7, 'name' => 'user.update', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 8, 'name' => 'user.destroy', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 9, 'name' => 'menu-group.index', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 10, 'name' => 'menu-group.store', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 11, 'name' => 'menu-group.update', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 12, 'name' => 'menu-group.destroy', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 13, 'name' => 'menu-item.index', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 14, 'name' => 'menu-item.store', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 15, 'name' => 'menu-item.update', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 16, 'name' => 'menu-item.destroy', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 17, 'name' => 'route.index', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 18, 'name' => 'route.store', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 19, 'name' => 'route.update', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 20, 'name' => 'route.destroy', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 21, 'name' => 'role.index', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 22, 'name' => 'role.store', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 23, 'name' => 'role.update', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 24, 'name' => 'role.destroy', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 25, 'name' => 'permission.index', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 26, 'name' => 'permission.store', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 27, 'name' => 'permission.update', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 28, 'name' => 'permission.destroy', 'guard_name' => 'web', 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:27:05'],
                ['id' => 29, 'name' => 'faq.destroy', 'guard_name' => 'web', 'created_at' => '2024-09-27 00:07:55', 'updated_at' => '2025-09-16 01:45:11'],
                ['id' => 30, 'name' => 'faq.index', 'guard_name' => 'web', 'created_at' => '2024-09-27 00:08:52', 'updated_at' => '2025-09-16 01:45:00'],
                ['id' => 31, 'name' => 'faq.store', 'guard_name' => 'web', 'created_at' => '2024-09-27 00:09:11', 'updated_at' => '2025-09-16 01:45:36'],
                ['id' => 32, 'name' => 'faq.update', 'guard_name' => 'web', 'created_at' => '2024-09-27 00:09:36', 'updated_at' => '2025-09-16 01:45:20'],
                ['id' => 33, 'name' => 'menu.main-portofolio', 'guard_name' => 'web', 'created_at' => '2024-09-27 00:11:21', 'updated_at' => '2024-09-27 00:11:21'],
                ['id' => 38, 'name' => 'categories.destroy', 'guard_name' => 'web', 'created_at' => '2024-09-29 12:28:15', 'updated_at' => '2024-10-15 13:50:56'],
                ['id' => 39, 'name' => 'categories.index', 'guard_name' => 'web', 'created_at' => '2024-09-29 12:28:26', 'updated_at' => '2024-10-15 13:51:03'],
                ['id' => 40, 'name' => 'categories.store', 'guard_name' => 'web', 'created_at' => '2024-09-29 12:28:42', 'updated_at' => '2024-10-15 13:51:13'],
                ['id' => 41, 'name' => 'categories.update', 'guard_name' => 'web', 'created_at' => '2024-09-29 12:28:54', 'updated_at' => '2024-10-15 13:51:20'],
                ['id' => 46, 'name' => 'banner.destroy', 'guard_name' => 'web', 'created_at' => '2024-09-30 12:05:12', 'updated_at' => '2024-10-13 22:29:29'],
                ['id' => 47, 'name' => 'banner.index', 'guard_name' => 'web', 'created_at' => '2024-09-30 12:05:28', 'updated_at' => '2024-10-13 22:29:37'],
                ['id' => 48, 'name' => 'banner.store', 'guard_name' => 'web', 'created_at' => '2024-09-30 12:05:41', 'updated_at' => '2024-10-13 22:29:45'],
                ['id' => 49, 'name' => 'banner.update', 'guard_name' => 'web', 'created_at' => '2024-09-30 12:05:59', 'updated_at' => '2024-10-13 22:29:53'],
                ['id' => 50, 'name' => 'agendas.destroy', 'guard_name' => 'web', 'created_at' => '2024-10-15 14:19:19', 'updated_at' => '2024-10-15 14:19:19'],
                ['id' => 51, 'name' => 'agendas.create', 'guard_name' => 'web', 'created_at' => '2024-10-15 14:19:37', 'updated_at' => '2024-10-15 14:20:03'],
                ['id' => 52, 'name' => 'agendas.index', 'guard_name' => 'web', 'created_at' => '2024-10-15 14:20:16', 'updated_at' => '2024-10-15 14:20:16'],
                ['id' => 53, 'name' => 'agendas.store', 'guard_name' => 'web', 'created_at' => '2024-10-15 14:20:34', 'updated_at' => '2024-10-15 14:20:34'],
                ['id' => 54, 'name' => 'agendas.update', 'guard_name' => 'web', 'created_at' => '2024-10-15 14:20:52', 'updated_at' => '2024-10-15 14:20:52'],
                ['id' => 55, 'name' => 'dashboard.form', 'guard_name' => 'web', 'created_at' => '2025-07-21 15:03:35', 'updated_at' => '2025-07-21 16:36:22'],
                ['id' => 60, 'name' => 'poll.destroy', 'guard_name' => 'web', 'created_at' => '2025-07-21 17:50:21', 'updated_at' => '2025-09-17 22:51:57'],
                ['id' => 65, 'name' => 'poll.index', 'guard_name' => 'web', 'created_at' => '2025-07-21 17:50:33', 'updated_at' => '2025-09-17 22:52:13'],
                ['id' => 70, 'name' => 'poll.store', 'guard_name' => 'web', 'created_at' => '2025-07-21 17:50:49', 'updated_at' => '2025-09-17 22:52:25'],
                ['id' => 75, 'name' => 'poll.update', 'guard_name' => 'web', 'created_at' => '2025-07-21 17:51:14', 'updated_at' => '2025-09-17 22:52:37'],
                ['id' => 85, 'name' => 'account.index', 'guard_name' => 'web', 'created_at' => '2025-07-22 23:35:48', 'updated_at' => '2025-07-22 23:35:48'],
                ['id' => 90, 'name' => 'account.update', 'guard_name' => 'web', 'created_at' => '2025-07-22 23:36:13', 'updated_at' => '2025-07-22 23:36:13'],
                ['id' => 125, 'name' => 'document-categories.destroy', 'guard_name' => 'web', 'created_at' => '2025-07-25 16:48:18', 'updated_at' => '2026-09-15 22:15:08'],
                ['id' => 130, 'name' => 'document-categories.index', 'guard_name' => 'web', 'created_at' => '2025-07-25 16:48:48', 'updated_at' => '2026-09-15 22:15:20'],
                ['id' => 135, 'name' => 'document-categories.store', 'guard_name' => 'web', 'created_at' => '2025-07-25 16:49:09', 'updated_at' => '2026-09-15 22:15:30'],
                ['id' => 140, 'name' => 'document-categories.update', 'guard_name' => 'web', 'created_at' => '2025-07-25 16:49:23', 'updated_at' => '2026-09-15 22:15:42'],
                ['id' => 145, 'name' => 'layanan.kontak', 'guard_name' => 'web', 'created_at' => '2025-07-27 23:46:01', 'updated_at' => '2025-07-27 23:46:01'],
                ['id' => 147, 'name' => 'pages.create', 'guard_name' => 'web', 'created_at' => '2025-09-17 23:27:58', 'updated_at' => '2025-09-17 23:27:58'],
                ['id' => 148, 'name' => 'pages.destroy', 'guard_name' => 'web', 'created_at' => '2025-09-17 23:28:08', 'updated_at' => '2025-09-17 23:28:08'],
                ['id' => 149, 'name' => 'pages.index', 'guard_name' => 'web', 'created_at' => '2025-09-17 23:28:19', 'updated_at' => '2025-09-17 23:28:19'],
                ['id' => 150, 'name' => 'pages.store', 'guard_name' => 'web', 'created_at' => '2025-09-17 23:28:28', 'updated_at' => '2025-09-17 23:28:28'],
                ['id' => 151, 'name' => 'pages.update', 'guard_name' => 'web', 'created_at' => '2025-09-17 23:28:36', 'updated_at' => '2025-09-17 23:28:36'],
                ['id' => 163, 'name' => 'services.index', 'guard_name' => 'web', 'created_at' => '2025-11-11 03:38:59', 'updated_at' => '2026-10-07 13:21:25'],
                ['id' => 164, 'name' => 'services.destroy', 'guard_name' => 'web', 'created_at' => '2025-11-11 03:39:23', 'updated_at' => '2026-10-07 13:21:11'],
                ['id' => 165, 'name' => 'services.update', 'guard_name' => 'web', 'created_at' => '2025-11-11 03:39:36', 'updated_at' => '2026-10-07 13:21:56'],
                ['id' => 166, 'name' => 'services.store', 'guard_name' => 'web', 'created_at' => '2025-11-11 03:39:47', 'updated_at' => '2026-10-07 13:21:38'],
                ['id' => 176, 'name' => 'dashboard.submitSumber', 'guard_name' => 'web', 'created_at' => '2026-09-04 07:15:06', 'updated_at' => '2026-09-04 07:15:06'],
                ['id' => 185, 'name' => 'pengguna.index', 'guard_name' => 'web', 'created_at' => '2026-09-06 20:38:51', 'updated_at' => '2026-09-06 20:38:51'],
                ['id' => 186, 'name' => 'pengguna.export', 'guard_name' => 'web', 'created_at' => '2026-09-06 20:39:08', 'updated_at' => '2026-09-06 20:39:08'],
                ['id' => 187, 'name' => 'documents.index', 'guard_name' => 'web', 'created_at' => '2026-09-08 06:29:57', 'updated_at' => '2026-09-16 00:37:55'],
                ['id' => 188, 'name' => 'documents.destroy', 'guard_name' => 'web', 'created_at' => '2026-09-08 06:30:24', 'updated_at' => '2026-09-16 00:37:43'],
                ['id' => 189, 'name' => 'documents.create', 'guard_name' => 'web', 'created_at' => '2026-09-08 06:30:57', 'updated_at' => '2026-09-16 00:37:29'],
                ['id' => 190, 'name' => 'documents.store', 'guard_name' => 'web', 'created_at' => '2026-09-08 06:31:17', 'updated_at' => '2026-09-16 00:38:04'],
                ['id' => 191, 'name' => 'documents.update', 'guard_name' => 'web', 'created_at' => '2026-09-08 06:31:34', 'updated_at' => '2026-09-16 00:38:15'],
                ['id' => 197, 'name' => 'menu.keamanan', 'guard_name' => 'web', 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 198, 'name' => 'login-activity.index', 'guard_name' => 'web', 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 199, 'name' => 'login-activity.destroy', 'guard_name' => 'web', 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 200, 'name' => 'audit-log.index', 'guard_name' => 'web', 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 201, 'name' => 'audit-log.destroy', 'guard_name' => 'web', 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 202, 'name' => 'failed-login.index', 'guard_name' => 'web', 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 203, 'name' => 'failed-login.destroy', 'guard_name' => 'web', 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 204, 'name' => 'login-lockout.index', 'guard_name' => 'web', 'created_at' => '2026-09-17 03:25:22', 'updated_at' => '2026-09-17 03:25:22'],
                ['id' => 205, 'name' => 'login-lockout.destroy', 'guard_name' => 'web', 'created_at' => '2026-09-17 03:25:22', 'updated_at' => '2026-09-17 03:25:22'],
                ['id' => 206, 'name' => 'website-identity.edit', 'guard_name' => 'web', 'created_at' => '2026-09-18 02:42:55', 'updated_at' => '2026-09-18 02:42:55'],
                ['id' => 207, 'name' => 'website-identity.update', 'guard_name' => 'web', 'created_at' => '2026-09-18 02:42:55', 'updated_at' => '2026-09-18 02:42:55'],
                ['id' => 208, 'name' => 'menu.website-config', 'guard_name' => 'web', 'created_at' => '2026-09-18 02:42:55', 'updated_at' => '2026-09-18 02:42:55'],
                ['id' => 209, 'name' => 'aduans.index', 'guard_name' => 'web', 'created_at' => '2026-09-19 00:53:26', 'updated_at' => '2026-09-19 00:53:26'],
                ['id' => 210, 'name' => 'aduans.store', 'guard_name' => 'web', 'created_at' => '2026-09-19 00:53:26', 'updated_at' => '2026-09-19 00:53:26'],
                ['id' => 211, 'name' => 'aduans.show', 'guard_name' => 'web', 'created_at' => '2026-09-19 01:09:38', 'updated_at' => '2026-09-19 01:09:38'],
                ['id' => 212, 'name' => 'aduans.update', 'guard_name' => 'web', 'created_at' => '2026-09-19 01:09:38', 'updated_at' => '2026-09-19 01:09:38'],
                ['id' => 213, 'name' => 'aduans.destroy', 'guard_name' => 'web', 'created_at' => '2026-09-19 01:09:38', 'updated_at' => '2026-09-19 01:09:38'],
                ['id' => 214, 'name' => 'aduans.create', 'guard_name' => 'web', 'created_at' => '2026-09-19 02:10:07', 'updated_at' => '2026-09-19 02:10:07'],
                ['id' => 215, 'name' => 'aduans.edit', 'guard_name' => 'web', 'created_at' => '2026-09-19 02:10:28', 'updated_at' => '2026-09-19 02:10:28'],
                ['id' => 216, 'name' => 'aduans.restore', 'guard_name' => 'web', 'created_at' => '2026-09-19 02:10:30', 'updated_at' => '2026-09-19 02:10:30'],
                ['id' => 217, 'name' => 'aduans.forceDelete', 'guard_name' => 'web', 'created_at' => '2026-09-19 02:10:30', 'updated_at' => '2026-09-19 02:10:30'],
                ['id' => 218, 'name' => 'aduans.tindaklanjut.store', 'guard_name' => 'web', 'created_at' => '2026-09-19 04:15:47', 'updated_at' => '2026-09-19 04:15:47'],
                ['id' => 219, 'name' => 'aduans.tindaklanjut.destroy', 'guard_name' => 'web', 'created_at' => '2026-09-19 04:15:47', 'updated_at' => '2026-09-19 04:15:47'],
                ['id' => 220, 'name' => 'aduans.export', 'guard_name' => 'web', 'created_at' => '2026-09-19 04:22:17', 'updated_at' => '2026-09-19 04:22:17'],
                ['id' => 221, 'name' => 'aduans.arsip', 'guard_name' => 'web', 'created_at' => '2026-09-19 04:53:19', 'updated_at' => '2026-09-19 04:53:19'],
                ['id' => 222, 'name' => 'aduans.batalArsip', 'guard_name' => 'web', 'created_at' => '2026-09-19 04:53:19', 'updated_at' => '2026-09-19 04:53:19'],
                ['id' => 223, 'name' => 'health.page', 'guard_name' => 'web', 'created_at' => '2026-09-20 00:15:45', 'updated_at' => '2026-09-20 00:15:45'],
                ['id' => 224, 'name' => 'agendas.approve', 'guard_name' => 'web', 'created_at' => '2026-10-07 14:04:12', 'updated_at' => '2026-10-07 14:04:12'],
            ]);
            $this->sync('users', [
                ['uuid' => '0a091f5a-c510-4110-b2b2-2f992091fb1d', 'name' => 'Diskominfostandi', 'no_hp' => null, 'alamat' => null, 'kecamatan_id' => null, 'kelurahan_id' => null, 'avatar' => null, 'email' => 'diskominfostandi@bekasikota.go.id', 'email_verified_at' => '2026-10-07 14:15:33', 'password' => '$2y$12$jnDoi7L52haFQI1I5CtLzO0upmwFAg9uWsEt4wwJQVQEHHAl2BmiO', 'remember_token' => null, 'created_at' => '2026-10-07 14:15:33', 'updated_at' => '2026-10-07 14:15:33', 'google_id' => null, 'tempat_lahir' => null, 'tanggal_lahir' => null, 'jenis_kelamin' => null, 'agama' => null, 'is_active' => null, 'deleted_at' => null, 'sumber_informasi' => null],
                ['uuid' => '58cbfa68-69aa-4cd1-95cc-9520046d9ceb', 'name' => 'Wiku Pramesthi Bagaswara', 'no_hp' => '085691333321', 'alamat' => null, 'kecamatan_id' => null, 'kelurahan_id' => null, 'avatar' => 'https://lh3.googleusercontent.com/a/ACg8ocIy22SZ7rHRNSArCv4eG6XuX5UvbOoXvsVvIs9Xq7V7QurWiopP=s96-c', 'email' => 'wikupb@gmail.com', 'email_verified_at' => '2026-10-07 14:14:58', 'password' => '$2y$12$jAEzSFmcf9dR//UIHXEEfel1L44V3FZ31XSYmnSwaRBiz/OzNdX0q', 'remember_token' => null, 'created_at' => '2026-09-19 00:40:14', 'updated_at' => '2026-10-07 19:32:30', 'google_id' => null, 'tempat_lahir' => null, 'tanggal_lahir' => null, 'jenis_kelamin' => null, 'agama' => null, 'is_active' => null, 'deleted_at' => '2026-10-07 19:32:30', 'sumber_informasi' => 'event'],
                ['uuid' => '787b72ea-59d0-4d54-848b-c200bddafdd2', 'name' => 'super-admin', 'no_hp' => null, 'alamat' => 'Jl. Gandaria', 'kecamatan_id' => 4, 'kelurahan_id' => 17, 'avatar' => 'avatars/u5KMRU9jG95SYcdq0vhxnksQ0EFatee9WxrPxrtH.jpg', 'email' => 'super@admin.com', 'email_verified_at' => '2025-09-16 00:23:37', 'password' => '$2y$12$PRZJcd.nlREU6NRq3jvIVemYmlwxVPTb7En4URyNgyQSfKjWUzwDi', 'remember_token' => 'wBCX0Zq1yditGfp4In3D0eLKmQnrwvnk5Xufv5nR984GZB6EpjmD2EDxD0Vo', 'created_at' => '2025-09-16 00:23:37', 'updated_at' => '2026-09-06 23:43:32', 'google_id' => null, 'tempat_lahir' => 'dasda', 'tanggal_lahir' => '2025-09-24', 'jenis_kelamin' => 'P', 'agama' => 'Lainnya', 'is_active' => null, 'deleted_at' => null, 'sumber_informasi' => null],
                ['uuid' => '9c513953-32d1-4415-a921-8a6baf246d44', 'name' => 'Admin Diskominfo', 'no_hp' => '43243277777', 'alamat' => null, 'kecamatan_id' => null, 'kelurahan_id' => null, 'avatar' => 'https://lh3.googleusercontent.com/a/ACg8ocLitzhONjAN_zPOcC2rM16BHekNE3M2zU7C-e0d53-6M47R-PE=s96-c', 'email' => 'ikpdiskominfostandi@bekasikota.go.id', 'email_verified_at' => '2026-10-07 19:32:24', 'password' => '$2y$12$PRZJcd.nlREU6NRq3jvIVemYmlwxVPTb7En4URyNgyQSfKjWUzwDi', 'remember_token' => null, 'created_at' => '2026-09-19 02:27:18', 'updated_at' => '2026-10-07 19:32:24', 'google_id' => null, 'tempat_lahir' => null, 'tanggal_lahir' => null, 'jenis_kelamin' => null, 'agama' => null, 'is_active' => null, 'deleted_at' => null, 'sumber_informasi' => 'google'],
            ]);
            $this->sync('categories', [
                ['uuid' => '2162d145-9ef3-4e2f-8c55-81971a015bc5', 'name' => 'Pemerintahan', 'slug' => 'pemerintahan', 'description' => 'Agenda resmi yang berkaitan dengan penyelenggaraan pemerintahan dan kebijakan daerah.', 'icon' => 'fa-newspaper', 'created_at' => '2025-09-16 01:03:22', 'updated_at' => '2026-10-07 14:02:07'],
                ['uuid' => '34103609-8116-4baf-bd17-557fc6989e8e', 'name' => 'Pelayanan & Masyarakat', 'slug' => 'pelayanan-masyarakat', 'description' => 'Kegiatan yang berkaitan dengan pelayanan publik dan interaksi langsung dengan masyarakat.', 'icon' => 'fa-screwdriver-wrenc', 'created_at' => '2025-09-16 01:03:16', 'updated_at' => '2026-10-07 14:02:30'],
                ['uuid' => '72561291-cdcb-484b-a556-0400fbd53c3d', 'name' => 'Rapat & Koordinasi', 'slug' => 'rapat-koordinasi', 'description' => 'Rapat, koordinasi, pembahasan, dan kegiatan internal pemerintahan.', 'icon' => 'fa-road', 'created_at' => '2025-10-31 03:07:08', 'updated_at' => '2026-10-07 14:02:50'],
                ['uuid' => '773cd47c-dc89-4546-b6f0-bfb26f74a0a2', 'name' => 'Lainnya', 'slug' => 'lainnya', 'description' => 'Agenda yang tidak termasuk dalam kategori yang tersedia.', 'icon' => null, 'created_at' => '2026-10-07 14:03:26', 'updated_at' => '2026-10-07 14:03:26'],
                ['uuid' => 'a58f5ebc-c8ec-48da-b6f9-a80b05ca40a0', 'name' => 'Acara & Seremonial', 'slug' => 'acara-seremonial', 'description' => 'Peresmian, upacara, peringatan, pelantikan, dan kegiatan seremonial lainnya.', 'icon' => 'fa-calendar-days', 'created_at' => '2026-06-20 12:47:44', 'updated_at' => '2026-10-07 14:03:08'],
            ]);
            $this->sync('document_categories', [
                ['uuid' => '01a9274f-003b-4ebc-b62a-b22f1091afd5', 'name' => 'Materi Agenda', 'slug' => 'materi-agenda', 'description' => 'Materi, paparan, atau bahan yang digunakan dalam agenda.', 'status' => 'active', 'created_at' => '2026-09-15 16:14:43', 'updated_at' => '2026-10-07 14:51:35'],
                ['uuid' => '9c0c6f74-224c-4af9-b1cf-dd9d46a8c685', 'name' => 'Laporan', 'slug' => 'laporan', 'description' => 'Laporan pelaksanaan atau dokumentasi hasil kegiatan.', 'status' => 'active', 'created_at' => '2026-09-15 16:14:05', 'updated_at' => '2026-10-07 14:52:06'],
                ['uuid' => 'a6d14f40-5032-40dc-9ef2-349d4300636f', 'name' => 'Undangan', 'slug' => 'undangan', 'description' => 'Dokumen undangan untuk agenda atau kegiatan pemerintah.', 'status' => 'active', 'created_at' => '2026-09-20 13:02:23', 'updated_at' => '2026-10-07 14:51:04'],
                ['uuid' => 'b8d57474-3500-4caa-8d20-a3f1141c47e6', 'name' => 'Dokumen Pendukung', 'slug' => 'dokumen-pendukung', 'description' => 'Dokumen lain yang berkaitan dengan agenda.', 'status' => 'active', 'created_at' => '2026-09-15 16:13:55', 'updated_at' => '2026-10-07 14:52:24'],
                ['uuid' => 'd5394429-10b1-4222-86b4-68c6da8dfe0d', 'name' => 'Hasil Kegiatan', 'slug' => 'hasil-kegiatan', 'description' => 'Berita acara, notulen, atau hasil dari pelaksanaan agenda.', 'status' => 'active', 'created_at' => '2026-09-15 16:14:28', 'updated_at' => '2026-10-07 14:51:50'],
            ]);
            $this->sync('kecamatans', [
                ['id' => 1, 'nama' => 'Bantar Gebang', 'created_at' => null, 'updated_at' => null],
                ['id' => 2, 'nama' => 'Bekasi Barat', 'created_at' => null, 'updated_at' => null],
                ['id' => 3, 'nama' => 'Bekasi Selatan', 'created_at' => null, 'updated_at' => null],
                ['id' => 4, 'nama' => 'Bekasi Timur', 'created_at' => null, 'updated_at' => null],
                ['id' => 5, 'nama' => 'Bekasi Utara', 'created_at' => null, 'updated_at' => null],
                ['id' => 6, 'nama' => 'Jatiasih', 'created_at' => null, 'updated_at' => null],
                ['id' => 7, 'nama' => 'Jatisampurna', 'created_at' => null, 'updated_at' => null],
                ['id' => 8, 'nama' => 'Medan Satria', 'created_at' => null, 'updated_at' => null],
                ['id' => 9, 'nama' => 'Mustika Jaya', 'created_at' => null, 'updated_at' => null],
                ['id' => 10, 'nama' => 'Pondok Gede', 'created_at' => null, 'updated_at' => null],
                ['id' => 11, 'nama' => 'Pondok Melati', 'created_at' => null, 'updated_at' => null],
                ['id' => 12, 'nama' => 'Rawalumbu', 'created_at' => null, 'updated_at' => null],
            ]);
            $this->sync('kelurahans', [
                ['id' => 1, 'kecamatan_id' => 1, 'nama' => 'Bantargebang', 'kodepos' => 17151, 'created_at' => null, 'updated_at' => null],
                ['id' => 2, 'kecamatan_id' => 1, 'nama' => 'Ciketing Udik', 'kodepos' => 17153, 'created_at' => null, 'updated_at' => null],
                ['id' => 3, 'kecamatan_id' => 1, 'nama' => 'Cikiwul', 'kodepos' => 17152, 'created_at' => null, 'updated_at' => null],
                ['id' => 4, 'kecamatan_id' => 1, 'nama' => 'Sumur Batu', 'kodepos' => 17154, 'created_at' => null, 'updated_at' => null],
                ['id' => 5, 'kecamatan_id' => 2, 'nama' => 'Bintara', 'kodepos' => 17134, 'created_at' => null, 'updated_at' => null],
                ['id' => 6, 'kecamatan_id' => 2, 'nama' => 'Bintara Jaya', 'kodepos' => 17136, 'created_at' => null, 'updated_at' => null],
                ['id' => 7, 'kecamatan_id' => 2, 'nama' => 'Jakasampurna', 'kodepos' => 17145, 'created_at' => null, 'updated_at' => null],
                ['id' => 8, 'kecamatan_id' => 2, 'nama' => 'Kota Baru', 'kodepos' => 17133, 'created_at' => null, 'updated_at' => null],
                ['id' => 9, 'kecamatan_id' => 2, 'nama' => 'Kranji', 'kodepos' => 17135, 'created_at' => null, 'updated_at' => null],
                ['id' => 10, 'kecamatan_id' => 3, 'nama' => 'Jakamulya', 'kodepos' => 17146, 'created_at' => null, 'updated_at' => null],
                ['id' => 11, 'kecamatan_id' => 3, 'nama' => 'Jakasetia', 'kodepos' => 17147, 'created_at' => null, 'updated_at' => null],
                ['id' => 12, 'kecamatan_id' => 3, 'nama' => 'Kayuringin Jaya', 'kodepos' => 17144, 'created_at' => null, 'updated_at' => null],
                ['id' => 13, 'kecamatan_id' => 3, 'nama' => 'Marga Jaya', 'kodepos' => 17141, 'created_at' => null, 'updated_at' => null],
                ['id' => 14, 'kecamatan_id' => 3, 'nama' => 'Pekayon Jaya', 'kodepos' => 17148, 'created_at' => null, 'updated_at' => null],
                ['id' => 15, 'kecamatan_id' => 4, 'nama' => 'Aren Jaya', 'kodepos' => 17111, 'created_at' => null, 'updated_at' => null],
                ['id' => 16, 'kecamatan_id' => 4, 'nama' => 'Bekasi Jaya', 'kodepos' => 17112, 'created_at' => null, 'updated_at' => null],
                ['id' => 17, 'kecamatan_id' => 4, 'nama' => 'Duren Jaya', 'kodepos' => 17111, 'created_at' => null, 'updated_at' => null],
                ['id' => 18, 'kecamatan_id' => 4, 'nama' => 'Margahayu', 'kodepos' => 17113, 'created_at' => null, 'updated_at' => null],
                ['id' => 19, 'kecamatan_id' => 5, 'nama' => 'Harapan Baru', 'kodepos' => 17123, 'created_at' => null, 'updated_at' => null],
                ['id' => 20, 'kecamatan_id' => 5, 'nama' => 'Harapan Jaya', 'kodepos' => 17124, 'created_at' => null, 'updated_at' => null],
                ['id' => 21, 'kecamatan_id' => 5, 'nama' => 'Kaliabang Tengah', 'kodepos' => 17125, 'created_at' => null, 'updated_at' => null],
                ['id' => 22, 'kecamatan_id' => 5, 'nama' => 'Marga Mulya', 'kodepos' => 17142, 'created_at' => null, 'updated_at' => null],
                ['id' => 23, 'kecamatan_id' => 5, 'nama' => 'Perwira', 'kodepos' => 17122, 'created_at' => null, 'updated_at' => null],
                ['id' => 24, 'kecamatan_id' => 5, 'nama' => 'Teluk Pucung', 'kodepos' => 17121, 'created_at' => null, 'updated_at' => null],
                ['id' => 25, 'kecamatan_id' => 6, 'nama' => 'Jatiasih', 'kodepos' => 17423, 'created_at' => null, 'updated_at' => null],
                ['id' => 26, 'kecamatan_id' => 6, 'nama' => 'Jatikramat', 'kodepos' => 17421, 'created_at' => null, 'updated_at' => null],
                ['id' => 27, 'kecamatan_id' => 6, 'nama' => 'Jatiluhur', 'kodepos' => 17425, 'created_at' => null, 'updated_at' => null],
                ['id' => 28, 'kecamatan_id' => 6, 'nama' => 'Jatimekar', 'kodepos' => 17422, 'created_at' => null, 'updated_at' => null],
                ['id' => 29, 'kecamatan_id' => 6, 'nama' => 'Jatirasa', 'kodepos' => 17424, 'created_at' => null, 'updated_at' => null],
                ['id' => 30, 'kecamatan_id' => 6, 'nama' => 'Jatisari', 'kodepos' => 17426, 'created_at' => null, 'updated_at' => null],
                ['id' => 31, 'kecamatan_id' => 7, 'nama' => 'Jatikarya', 'kodepos' => 17435, 'created_at' => null, 'updated_at' => null],
                ['id' => 32, 'kecamatan_id' => 7, 'nama' => 'Jatiraden', 'kodepos' => 17433, 'created_at' => null, 'updated_at' => null],
                ['id' => 33, 'kecamatan_id' => 7, 'nama' => 'Jatirangga', 'kodepos' => 17434, 'created_at' => null, 'updated_at' => null],
                ['id' => 34, 'kecamatan_id' => 7, 'nama' => 'Jatiranggon', 'kodepos' => 17432, 'created_at' => null, 'updated_at' => null],
                ['id' => 35, 'kecamatan_id' => 7, 'nama' => 'Jatisampurna', 'kodepos' => 17433, 'created_at' => null, 'updated_at' => null],
                ['id' => 36, 'kecamatan_id' => 8, 'nama' => 'Harapan Mulya', 'kodepos' => 17143, 'created_at' => null, 'updated_at' => null],
                ['id' => 37, 'kecamatan_id' => 8, 'nama' => 'Kali Baru', 'kodepos' => 17133, 'created_at' => null, 'updated_at' => null],
                ['id' => 38, 'kecamatan_id' => 8, 'nama' => 'Medan Satria', 'kodepos' => 17132, 'created_at' => null, 'updated_at' => null],
                ['id' => 39, 'kecamatan_id' => 8, 'nama' => 'Pejuang', 'kodepos' => 17131, 'created_at' => null, 'updated_at' => null],
                ['id' => 40, 'kecamatan_id' => 9, 'nama' => 'Cimuning', 'kodepos' => 17155, 'created_at' => null, 'updated_at' => null],
                ['id' => 41, 'kecamatan_id' => 9, 'nama' => 'Mustikajaya', 'kodepos' => 17158, 'created_at' => null, 'updated_at' => null],
                ['id' => 42, 'kecamatan_id' => 9, 'nama' => 'Mustikasari', 'kodepos' => 17157, 'created_at' => null, 'updated_at' => null],
                ['id' => 43, 'kecamatan_id' => 9, 'nama' => 'Padurenan', 'kodepos' => 17156, 'created_at' => null, 'updated_at' => null],
                ['id' => 44, 'kecamatan_id' => 10, 'nama' => 'Jatibening', 'kodepos' => 17412, 'created_at' => null, 'updated_at' => null],
                ['id' => 45, 'kecamatan_id' => 10, 'nama' => 'Jatibening Baru', 'kodepos' => 17412, 'created_at' => null, 'updated_at' => null],
                ['id' => 46, 'kecamatan_id' => 10, 'nama' => 'Jaticempaka', 'kodepos' => 17411, 'created_at' => null, 'updated_at' => null],
                ['id' => 47, 'kecamatan_id' => 10, 'nama' => 'Jatimakmur', 'kodepos' => 17413, 'created_at' => null, 'updated_at' => null],
                ['id' => 48, 'kecamatan_id' => 10, 'nama' => 'Jatiwaringin', 'kodepos' => 17411, 'created_at' => null, 'updated_at' => null],
                ['id' => 49, 'kecamatan_id' => 11, 'nama' => 'Jatimelati', 'kodepos' => 17414, 'created_at' => null, 'updated_at' => null],
                ['id' => 50, 'kecamatan_id' => 11, 'nama' => 'Jatimurni', 'kodepos' => 17431, 'created_at' => null, 'updated_at' => null],
                ['id' => 51, 'kecamatan_id' => 11, 'nama' => 'Jatirahayu', 'kodepos' => 17414, 'created_at' => null, 'updated_at' => null],
                ['id' => 52, 'kecamatan_id' => 11, 'nama' => 'Jatiwarna', 'kodepos' => 17415, 'created_at' => null, 'updated_at' => null],
                ['id' => 53, 'kecamatan_id' => 12, 'nama' => 'Bojong Menteng', 'kodepos' => 17117, 'created_at' => null, 'updated_at' => null],
                ['id' => 54, 'kecamatan_id' => 12, 'nama' => 'Bojong Rawalumbu', 'kodepos' => 17116, 'created_at' => null, 'updated_at' => null],
                ['id' => 55, 'kecamatan_id' => 12, 'nama' => 'Pengasinan', 'kodepos' => 17115, 'created_at' => null, 'updated_at' => null],
                ['id' => 56, 'kecamatan_id' => 12, 'nama' => 'Sepanjang Jaya', 'kodepos' => 17114, 'created_at' => null, 'updated_at' => null],
            ]);
            $this->sync('routes', [
                ['id' => 1, 'route' => 'dashboard.index', 'permission_name' => 'dashboard.index', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 2, 'route' => 'user.index', 'permission_name' => 'user.index', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 3, 'route' => 'user.store', 'permission_name' => 'user.store', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 4, 'route' => 'user.update', 'permission_name' => 'user.update', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 5, 'route' => 'user.destroy', 'permission_name' => 'user.destroy', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 6, 'route' => 'menu.index', 'permission_name' => 'menu-group.index', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 7, 'route' => 'menu.store', 'permission_name' => 'menu-group.store', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 8, 'route' => 'menu.update', 'permission_name' => 'menu-group.update', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 9, 'route' => 'menu.destroy', 'permission_name' => 'menu-group.destroy', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 10, 'route' => 'menu.item.index', 'permission_name' => 'menu-item.index', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 11, 'route' => 'menu.item.store', 'permission_name' => 'menu-item.store', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 12, 'route' => 'menu.item.update', 'permission_name' => 'menu-item.update', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 13, 'route' => 'menu.item.destroy', 'permission_name' => 'menu-item.destroy', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 14, 'route' => 'route.index', 'permission_name' => 'route.index', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 15, 'route' => 'route.store', 'permission_name' => 'route.store', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 16, 'route' => 'route.update', 'permission_name' => 'route.update', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 17, 'route' => 'route.destroy', 'permission_name' => 'route.destroy', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 18, 'route' => 'role.index', 'permission_name' => 'role.index', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 19, 'route' => 'role.store', 'permission_name' => 'role.store', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 20, 'route' => 'role.update', 'permission_name' => 'role.update', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 21, 'route' => 'role.destroy', 'permission_name' => 'role.destroy', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 22, 'route' => 'permission.index', 'permission_name' => 'permission.index', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 23, 'route' => 'permission.store', 'permission_name' => 'permission.store', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 24, 'route' => 'permission.update', 'permission_name' => 'permission.update', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 25, 'route' => 'permission.destroy', 'permission_name' => 'permission.destroy', 'status' => 1, 'description' => null, 'created_at' => null, 'updated_at' => null],
                ['id' => 26, 'route' => 'faq.index', 'permission_name' => 'faq.index', 'status' => 1, 'description' => null, 'created_at' => '2024-09-27 00:20:45', 'updated_at' => '2024-09-27 00:20:45'],
                ['id' => 27, 'route' => 'faq.store', 'permission_name' => 'faq.store', 'status' => 1, 'description' => null, 'created_at' => '2024-09-27 01:45:13', 'updated_at' => '2024-09-27 01:45:13'],
                ['id' => 28, 'route' => 'faq.update', 'permission_name' => 'faq.update', 'status' => 1, 'description' => null, 'created_at' => '2024-09-27 01:45:36', 'updated_at' => '2024-09-27 01:45:52'],
                ['id' => 29, 'route' => 'faq.destroy', 'permission_name' => 'faq.destroy', 'status' => 1, 'description' => null, 'created_at' => '2024-09-27 01:46:45', 'updated_at' => '2024-09-27 01:46:45'],
                ['id' => 34, 'route' => 'categories.destroy', 'permission_name' => 'categories.destroy', 'status' => 1, 'description' => null, 'created_at' => '2024-09-29 12:31:56', 'updated_at' => '2024-10-15 13:51:53'],
                ['id' => 35, 'route' => 'categories.index', 'permission_name' => 'categories.index', 'status' => 1, 'description' => null, 'created_at' => '2024-09-29 12:32:17', 'updated_at' => '2024-10-15 13:52:11'],
                ['id' => 36, 'route' => 'categories.store', 'permission_name' => 'categories.store', 'status' => 1, 'description' => null, 'created_at' => '2024-09-29 12:32:37', 'updated_at' => '2024-10-15 13:52:26'],
                ['id' => 37, 'route' => 'categories.update', 'permission_name' => 'categories.update', 'status' => 1, 'description' => null, 'created_at' => '2024-09-29 12:32:56', 'updated_at' => '2024-10-15 13:52:45'],
                ['id' => 42, 'route' => 'banner.destroy', 'permission_name' => 'banner.destroy', 'status' => 1, 'description' => null, 'created_at' => '2024-09-30 12:06:31', 'updated_at' => '2024-10-13 22:36:22'],
                ['id' => 43, 'route' => 'banner.index', 'permission_name' => 'banner.index', 'status' => 1, 'description' => null, 'created_at' => '2024-09-30 12:06:55', 'updated_at' => '2024-10-13 22:36:39'],
                ['id' => 44, 'route' => 'banner.store', 'permission_name' => 'banner.store', 'status' => 1, 'description' => null, 'created_at' => '2024-09-30 12:07:34', 'updated_at' => '2024-10-13 22:37:03'],
                ['id' => 45, 'route' => 'banner.update', 'permission_name' => 'banner.update', 'status' => 1, 'description' => null, 'created_at' => '2024-09-30 12:07:57', 'updated_at' => '2024-10-13 22:37:24'],
                ['id' => 46, 'route' => 'agendas.create', 'permission_name' => 'agendas.create', 'status' => 1, 'description' => null, 'created_at' => '2024-10-15 14:22:17', 'updated_at' => '2024-10-15 14:22:17'],
                ['id' => 47, 'route' => 'agendas.destroy', 'permission_name' => 'agendas.destroy', 'status' => 1, 'description' => null, 'created_at' => '2024-10-15 14:22:51', 'updated_at' => '2024-10-15 14:22:51'],
                ['id' => 48, 'route' => 'agendas.index', 'permission_name' => 'agendas.index', 'status' => 1, 'description' => null, 'created_at' => '2024-10-15 14:23:15', 'updated_at' => '2024-10-15 14:23:15'],
                ['id' => 49, 'route' => 'agendas.store', 'permission_name' => 'agendas.store', 'status' => 1, 'description' => null, 'created_at' => '2024-10-15 14:23:49', 'updated_at' => '2024-10-15 14:23:49'],
                ['id' => 50, 'route' => 'agendas.update', 'permission_name' => 'agendas.update', 'status' => 1, 'description' => null, 'created_at' => '2024-10-15 14:24:09', 'updated_at' => '2024-10-15 14:24:09'],
                ['id' => 238, 'route' => 'agendas.show', 'permission_name' => 'agendas.index', 'status' => 1, 'description' => 'Lihat detail agenda', 'created_at' => '2026-10-21 00:00:00', 'updated_at' => '2026-10-21 00:00:00'],
                ['id' => 239, 'route' => 'agendas.edit', 'permission_name' => 'agendas.update', 'status' => 1, 'description' => 'Form edit agenda', 'created_at' => '2026-10-21 00:00:00', 'updated_at' => '2026-10-21 00:00:00'],
                ['id' => 55, 'route' => 'dashboard.submitSumber', 'permission_name' => 'dashboard.form', 'status' => 1, 'description' => null, 'created_at' => '2025-07-21 16:35:06', 'updated_at' => '2025-07-21 16:35:06'],
                ['id' => 60, 'route' => 'poll.destroy', 'permission_name' => 'poll.destroy', 'status' => 1, 'description' => null, 'created_at' => '2025-07-21 17:53:17', 'updated_at' => '2025-09-17 22:55:14'],
                ['id' => 65, 'route' => 'polls.index', 'permission_name' => 'poll.index', 'status' => 1, 'description' => null, 'created_at' => '2025-07-21 17:53:47', 'updated_at' => '2025-09-17 22:54:20'],
                ['id' => 70, 'route' => 'poll.store', 'permission_name' => 'poll.store', 'status' => 1, 'description' => null, 'created_at' => '2025-07-21 17:54:16', 'updated_at' => '2025-09-17 22:55:28'],
                ['id' => 75, 'route' => 'poll.update', 'permission_name' => 'poll.update', 'status' => 1, 'description' => null, 'created_at' => '2025-07-21 17:54:35', 'updated_at' => '2025-09-17 22:55:42'],
                ['id' => 85, 'route' => 'account.index', 'permission_name' => 'account.index', 'status' => 1, 'description' => null, 'created_at' => '2025-07-22 23:36:37', 'updated_at' => '2025-07-22 23:36:37'],
                ['id' => 90, 'route' => 'account.update', 'permission_name' => 'account.update', 'status' => 1, 'description' => null, 'created_at' => '2025-07-22 23:37:03', 'updated_at' => '2025-07-22 23:37:03'],
                ['id' => 120, 'route' => 'document-categories.index', 'permission_name' => 'document-categories.index', 'status' => 1, 'description' => null, 'created_at' => '2025-07-25 16:50:03', 'updated_at' => '2026-09-15 22:36:36'],
                ['id' => 125, 'route' => 'document-categories.destroy', 'permission_name' => 'document-categories.destroy', 'status' => 1, 'description' => null, 'created_at' => '2025-07-25 16:50:24', 'updated_at' => '2026-09-15 22:36:11'],
                ['id' => 130, 'route' => 'document-categories.store', 'permission_name' => 'document-categories.store', 'status' => 1, 'description' => null, 'created_at' => '2025-07-25 16:50:51', 'updated_at' => '2026-09-15 22:36:58'],
                ['id' => 135, 'route' => 'document-categories.update', 'permission_name' => 'document-categories.update', 'status' => 1, 'description' => null, 'created_at' => '2025-07-25 16:51:29', 'updated_at' => '2026-09-15 22:37:27'],
                ['id' => 140, 'route' => 'layanan.kontak', 'permission_name' => 'layanan.kontak', 'status' => 1, 'description' => null, 'created_at' => '2025-07-27 23:47:08', 'updated_at' => '2025-07-27 23:47:08'],
                ['id' => 145, 'route' => 'banner.update', 'permission_name' => 'banner.update', 'status' => 1, 'description' => null, 'created_at' => '2025-08-07 12:52:06', 'updated_at' => '2025-08-07 12:52:06'],
                ['id' => 147, 'route' => 'pages.create', 'permission_name' => 'pages.create', 'status' => 1, 'description' => null, 'created_at' => '2025-09-17 23:29:27', 'updated_at' => '2025-09-17 23:29:27'],
                ['id' => 148, 'route' => 'pages.index', 'permission_name' => 'pages.index', 'status' => 1, 'description' => null, 'created_at' => '2025-09-17 23:29:41', 'updated_at' => '2025-09-17 23:29:41'],
                ['id' => 149, 'route' => 'pages.store', 'permission_name' => 'pages.store', 'status' => 1, 'description' => null, 'created_at' => '2025-09-17 23:29:59', 'updated_at' => '2025-09-17 23:29:59'],
                ['id' => 150, 'route' => 'pages.destroy', 'permission_name' => 'pages.destroy', 'status' => 1, 'description' => null, 'created_at' => '2025-09-17 23:30:31', 'updated_at' => '2025-09-17 23:30:31'],
                ['id' => 151, 'route' => 'pages.update', 'permission_name' => 'pages.update', 'status' => 1, 'description' => null, 'created_at' => '2025-09-17 23:30:45', 'updated_at' => '2025-09-17 23:30:45'],
                ['id' => 163, 'route' => 'services.destroy', 'permission_name' => 'services.destroy', 'status' => 1, 'description' => null, 'created_at' => '2025-11-11 03:40:24', 'updated_at' => '2026-10-07 13:28:51'],
                ['id' => 164, 'route' => 'services.index', 'permission_name' => 'services.index', 'status' => 1, 'description' => null, 'created_at' => '2025-11-11 03:40:41', 'updated_at' => '2026-10-07 13:29:11'],
                ['id' => 165, 'route' => 'services.store', 'permission_name' => 'services.store', 'status' => 1, 'description' => null, 'created_at' => '2025-11-11 03:40:58', 'updated_at' => '2026-10-07 13:29:30'],
                ['id' => 166, 'route' => 'services.update', 'permission_name' => 'services.update', 'status' => 1, 'description' => null, 'created_at' => '2025-11-11 03:42:13', 'updated_at' => '2026-10-07 13:29:55'],
                ['id' => 176, 'route' => 'dashboard.submitSumber', 'permission_name' => 'dashboard.submitSumber', 'status' => 1, 'description' => null, 'created_at' => '2026-09-04 07:15:41', 'updated_at' => '2026-09-04 07:15:41'],
                ['id' => 185, 'route' => 'pengguna.index', 'permission_name' => 'pengguna.index', 'status' => 1, 'description' => null, 'created_at' => '2026-09-06 20:39:23', 'updated_at' => '2026-09-06 20:39:23'],
                ['id' => 186, 'route' => 'pengguna.export', 'permission_name' => 'pengguna.export', 'status' => 1, 'description' => null, 'created_at' => '2026-09-06 20:39:35', 'updated_at' => '2026-09-06 20:39:35'],
                ['id' => 187, 'route' => 'documents.create', 'permission_name' => 'documents.create', 'status' => 1, 'description' => null, 'created_at' => '2026-09-08 06:32:44', 'updated_at' => '2026-09-16 00:54:07'],
                ['id' => 188, 'route' => 'documents.index', 'permission_name' => 'documents.index', 'status' => 1, 'description' => null, 'created_at' => '2026-09-08 06:32:59', 'updated_at' => '2026-09-16 00:54:20'],
                ['id' => 189, 'route' => 'documents.destroy', 'permission_name' => 'documents.destroy', 'status' => 1, 'description' => null, 'created_at' => '2026-09-08 06:33:15', 'updated_at' => '2026-09-16 00:53:57'],
                ['id' => 190, 'route' => 'documents.update', 'permission_name' => 'documents.update', 'status' => 1, 'description' => null, 'created_at' => '2026-09-08 06:33:33', 'updated_at' => '2026-09-16 00:55:02'],
                ['id' => 191, 'route' => 'documents.store', 'permission_name' => 'documents.store', 'status' => 1, 'description' => null, 'created_at' => '2026-09-08 06:33:55', 'updated_at' => '2026-09-16 00:54:38'],
                ['id' => 194, 'route' => 'aduans.index', 'permission_name' => 'aduans.index', 'status' => 1, 'description' => null, 'created_at' => '2026-09-13 13:24:29', 'updated_at' => '2026-09-19 01:07:28'],
                ['id' => 196, 'route' => 'orders.show', 'permission_name' => 'orders.show', 'status' => 1, 'description' => null, 'created_at' => '2026-09-13 13:24:58', 'updated_at' => '2026-09-13 13:24:58'],
                ['id' => 197, 'route' => 'security.login-activity.index', 'permission_name' => 'login-activity.index', 'status' => 1, 'description' => null, 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 198, 'route' => 'security.login-activity.destroy', 'permission_name' => 'login-activity.destroy', 'status' => 1, 'description' => null, 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 199, 'route' => 'security.audit-log.index', 'permission_name' => 'audit-log.index', 'status' => 1, 'description' => null, 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 200, 'route' => 'security.audit-log.destroy', 'permission_name' => 'audit-log.destroy', 'status' => 1, 'description' => null, 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 201, 'route' => 'security.audit-log.clear', 'permission_name' => 'audit-log.destroy', 'status' => 1, 'description' => null, 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 202, 'route' => 'security.failed-login.index', 'permission_name' => 'failed-login.index', 'status' => 1, 'description' => null, 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 203, 'route' => 'security.failed-login.destroy', 'permission_name' => 'failed-login.destroy', 'status' => 1, 'description' => null, 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 204, 'route' => 'security.failed-login.clear', 'permission_name' => 'failed-login.destroy', 'status' => 1, 'description' => null, 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 205, 'route' => 'security.login-lockout.index', 'permission_name' => 'login-lockout.index', 'status' => 1, 'description' => null, 'created_at' => '2026-09-17 03:25:22', 'updated_at' => '2026-09-17 03:25:22'],
                ['id' => 206, 'route' => 'security.login-lockout.destroy', 'permission_name' => 'login-lockout.destroy', 'status' => 1, 'description' => null, 'created_at' => '2026-09-17 03:25:22', 'updated_at' => '2026-09-17 03:25:22'],
                ['id' => 207, 'route' => 'website-identity.edit', 'permission_name' => 'website-identity.edit', 'status' => 1, 'description' => null, 'created_at' => '2026-09-18 02:42:55', 'updated_at' => '2026-09-18 02:42:55'],
                ['id' => 208, 'route' => 'website-identity.update', 'permission_name' => 'website-identity.update', 'status' => 1, 'description' => null, 'created_at' => '2026-09-18 02:42:55', 'updated_at' => '2026-09-18 02:42:55'],
                ['id' => 209, 'route' => 'aduans.store', 'permission_name' => 'aduans.store', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 01:09:39', 'updated_at' => '2026-09-19 01:09:39'],
                ['id' => 210, 'route' => 'aduans.show', 'permission_name' => 'aduans.show', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 01:09:39', 'updated_at' => '2026-09-19 01:09:39'],
                ['id' => 211, 'route' => 'aduans.update', 'permission_name' => 'aduans.update', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 01:09:39', 'updated_at' => '2026-09-19 01:09:39'],
                ['id' => 212, 'route' => 'aduans.destroy', 'permission_name' => 'aduans.destroy', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 01:09:39', 'updated_at' => '2026-09-19 01:09:39'],
                ['id' => 213, 'route' => 'aduans.create', 'permission_name' => 'aduans.store', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 01:27:52', 'updated_at' => '2026-09-19 01:27:52'],
                ['id' => 214, 'route' => 'aduans.edit', 'permission_name' => 'aduans.update', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 01:27:52', 'updated_at' => '2026-09-19 01:27:52'],
                ['id' => 215, 'route' => 'aduans.restore', 'permission_name' => 'aduans.restore', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 02:10:31', 'updated_at' => '2026-09-19 02:10:31'],
                ['id' => 216, 'route' => 'aduans.forceDestroy', 'permission_name' => 'aduans.forceDelete', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 02:10:31', 'updated_at' => '2026-09-19 02:10:31'],
                ['id' => 217, 'route' => 'aduans.tindak-lanjut.store', 'permission_name' => 'aduans.tindaklanjut.store', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 04:15:47', 'updated_at' => '2026-09-19 04:15:47'],
                ['id' => 218, 'route' => 'aduans.tindak-lanjut.destroy', 'permission_name' => 'aduans.tindaklanjut.destroy', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 04:15:47', 'updated_at' => '2026-09-19 04:15:47'],
                ['id' => 219, 'route' => 'aduans.exportPdf', 'permission_name' => 'aduans.export', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 04:22:18', 'updated_at' => '2026-09-19 04:22:18'],
                ['id' => 220, 'route' => 'aduans.cekDuplikat', 'permission_name' => 'aduans.index', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 04:53:20', 'updated_at' => '2026-09-19 04:53:20'],
                ['id' => 221, 'route' => 'aduans.arsip', 'permission_name' => 'aduans.arsip', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 04:53:20', 'updated_at' => '2026-09-19 04:53:20'],
                ['id' => 222, 'route' => 'aduans.batalArsip', 'permission_name' => 'aduans.batalArsip', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 04:53:20', 'updated_at' => '2026-09-19 04:53:20'],
                ['id' => 223, 'route' => 'aduans.rate', 'permission_name' => 'aduans.update', 'status' => 1, 'description' => null, 'created_at' => '2026-09-19 05:26:07', 'updated_at' => '2026-09-19 05:26:07'],
                ['id' => 225, 'route' => 'agendas.bulkDestroy', 'permission_name' => 'agendas.destroy', 'status' => 1, 'description' => 'Proteksi hapus massal (dibuat otomatis).', 'created_at' => '2026-10-07 11:43:40', 'updated_at' => '2026-10-07 11:43:40'],
                ['id' => 226, 'route' => 'documents.bulkDestroy', 'permission_name' => 'documents.destroy', 'status' => 1, 'description' => 'Proteksi hapus massal (dibuat otomatis).', 'created_at' => '2026-10-07 11:43:40', 'updated_at' => '2026-10-07 11:43:40'],
                ['id' => 227, 'route' => 'faq.bulkDestroy', 'permission_name' => 'faq.destroy', 'status' => 1, 'description' => 'Proteksi hapus massal (dibuat otomatis).', 'created_at' => '2026-10-07 11:43:40', 'updated_at' => '2026-10-07 11:43:40'],
                ['id' => 228, 'route' => 'document-categories.bulkDestroy', 'permission_name' => 'document-categories.destroy', 'status' => 1, 'description' => 'Proteksi hapus massal (dibuat otomatis).', 'created_at' => '2026-10-07 11:43:40', 'updated_at' => '2026-10-07 11:43:40'],
                ['id' => 230, 'route' => 'aduans.bulkDestroy', 'permission_name' => 'aduans.destroy', 'status' => 1, 'description' => 'Proteksi hapus massal (dibuat otomatis).', 'created_at' => '2026-10-07 11:43:40', 'updated_at' => '2026-10-07 11:43:40'],
                ['id' => 231, 'route' => 'aduans.bulkRestore', 'permission_name' => 'aduans.restore', 'status' => 1, 'description' => 'Proteksi hapus massal (dibuat otomatis).', 'created_at' => '2026-10-07 11:43:40', 'updated_at' => '2026-10-07 11:43:40'],
                ['id' => 232, 'route' => 'security.audit-log.bulkDestroy', 'permission_name' => 'audit-log.destroy', 'status' => 1, 'description' => 'Proteksi hapus massal (dibuat otomatis).', 'created_at' => '2026-10-07 11:43:40', 'updated_at' => '2026-10-07 11:43:40'],
                ['id' => 233, 'route' => 'security.failed-login.bulkDestroy', 'permission_name' => 'failed-login.destroy', 'status' => 1, 'description' => 'Proteksi hapus massal (dibuat otomatis).', 'created_at' => '2026-10-07 11:43:40', 'updated_at' => '2026-10-07 11:43:40'],
                ['id' => 234, 'route' => 'security.login-activity.bulkDestroy', 'permission_name' => 'login-activity.destroy', 'status' => 1, 'description' => 'Proteksi hapus massal (dibuat otomatis).', 'created_at' => '2026-10-07 11:43:40', 'updated_at' => '2026-10-07 11:43:40'],
                ['id' => 235, 'route' => 'security.login-lockout.bulkDestroy', 'permission_name' => 'login-lockout.destroy', 'status' => 1, 'description' => 'Proteksi hapus massal (dibuat otomatis).', 'created_at' => '2026-10-07 11:43:40', 'updated_at' => '2026-10-07 11:43:40'],
                ['id' => 236, 'route' => 'agendas.approve', 'permission_name' => 'agendas.approve', 'status' => 1, 'description' => 'Approval agenda OPD', 'created_at' => '2026-10-07 09:04:12', 'updated_at' => '2026-10-07 09:04:12'],
                ['id' => 237, 'route' => 'agendas.reject', 'permission_name' => 'agendas.approve', 'status' => 1, 'description' => 'Approval agenda OPD', 'created_at' => '2026-10-07 09:04:12', 'updated_at' => '2026-10-07 09:04:12'],
            ]);
            $this->sync('menu_groups', [
                ['id' => 2, 'name' => 'Role dan Akses', 'status' => 1, 'permission_name' => 'menu.role-permission', 'icon' => 'bx-shield-quarter', 'position' => 9, 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:56:50'],
                ['id' => 3, 'name' => 'Pengaturan', 'status' => 1, 'permission_name' => 'menu-item.index', 'icon' => 'bx-cog', 'position' => 10, 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2026-09-17 03:28:38'],
                ['id' => 4, 'name' => 'Dokumen', 'status' => 1, 'permission_name' => 'documents.index', 'icon' => 'bx-receipt', 'position' => 5, 'created_at' => '2024-09-26 23:35:30', 'updated_at' => '2026-10-07 19:42:27'],
                ['id' => 7, 'name' => 'Publikasi', 'status' => 1, 'permission_name' => 'agendas.index', 'icon' => 'bxs-file', 'position' => 2, 'created_at' => '2024-09-29 12:37:06', 'updated_at' => '2026-10-07 13:55:13'],
                ['id' => 8, 'name' => 'Galeri Media', 'status' => 1, 'permission_name' => 'banner.index', 'icon' => 'bx-camera', 'position' => 3, 'created_at' => '2024-10-13 22:33:06', 'updated_at' => '2026-10-07 14:22:09'],
                ['id' => 9, 'name' => 'Master Data', 'status' => 1, 'permission_name' => 'faq.index', 'icon' => 'bx-folder-open', 'position' => 8, 'created_at' => '2025-07-21 17:56:19', 'updated_at' => '2026-08-30 19:07:27'],
                ['id' => 22, 'name' => 'Layanan Publik', 'status' => 1, 'permission_name' => 'services.index', 'icon' => 'bx-book', 'position' => 6, 'created_at' => '2026-09-02 15:16:09', 'updated_at' => '2026-10-07 13:30:20'],
                ['id' => 24, 'name' => 'Keamanan', 'status' => 1, 'permission_name' => 'menu.keamanan', 'icon' => 'bx-shield-alt-2', 'position' => 11, 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 26, 'name' => 'Pengaduan', 'status' => 1, 'permission_name' => 'aduans.index', 'icon' => 'bx-phone', 'position' => 7, 'created_at' => '2026-09-19 02:03:39', 'updated_at' => '2026-09-19 02:03:39'],
            ]);
            $this->sync('menu_items', [
                ['id' => 2, 'name' => 'Module Role', 'icon' => null, 'route' => 'permission.index', 'status' => 1, 'permission_name' => 'permission.index', 'menu_group_id' => 2, 'position' => 1, 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:58:37'],
                ['id' => 3, 'name' => 'Master Role', 'icon' => null, 'route' => 'role.index', 'status' => 1, 'permission_name' => 'role.index', 'menu_group_id' => 2, 'position' => 2, 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:58:15'],
                ['id' => 4, 'name' => 'Daftar Pengguna', 'icon' => null, 'route' => 'user.index', 'status' => 1, 'permission_name' => 'user.index', 'menu_group_id' => 3, 'position' => 1, 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:39:25'],
                ['id' => 5, 'name' => 'Module Aplikasi', 'icon' => null, 'route' => 'route.index', 'status' => 1, 'permission_name' => 'route.index', 'menu_group_id' => 3, 'position' => 2, 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:44:54'],
                ['id' => 6, 'name' => 'Menu Manager', 'icon' => null, 'route' => 'menu.index', 'status' => 1, 'permission_name' => 'menu-group.index', 'menu_group_id' => 3, 'position' => 3, 'created_at' => '2024-09-26 23:27:05', 'updated_at' => '2024-09-26 23:44:09'],
                ['id' => 8, 'name' => 'Kategori', 'icon' => null, 'route' => 'categories.index', 'status' => 1, 'permission_name' => 'categories.index', 'menu_group_id' => 7, 'position' => 3, 'created_at' => '2024-09-29 12:39:24', 'updated_at' => '2026-09-17 02:21:41'],
                ['id' => 9, 'name' => 'Agenda', 'icon' => null, 'route' => 'agendas.index', 'status' => 1, 'permission_name' => 'agendas.index', 'menu_group_id' => 7, 'position' => 1, 'created_at' => '2024-09-30 12:08:38', 'updated_at' => '2026-09-17 01:51:37'],
                ['id' => 10, 'name' => 'Semua Media', 'icon' => null, 'route' => 'banner.index', 'status' => 1, 'permission_name' => 'banner.index', 'menu_group_id' => 8, 'position' => 1, 'created_at' => '2024-10-13 22:38:24', 'updated_at' => '2026-09-17 05:23:32'],
                ['id' => 12, 'name' => 'Tambah Agenda', 'icon' => null, 'route' => 'agendas.create', 'status' => 1, 'permission_name' => 'agendas.create', 'menu_group_id' => 7, 'position' => 2, 'created_at' => '2025-07-22 12:59:27', 'updated_at' => '2026-09-17 02:21:32'],
                ['id' => 40, 'name' => 'Halaman Statis', 'icon' => null, 'route' => 'pages.index', 'status' => 1, 'permission_name' => 'pages.index', 'menu_group_id' => 9, 'position' => 3, 'created_at' => '2025-10-21 03:46:02', 'updated_at' => '2026-09-17 03:30:02'],
                ['id' => 46, 'name' => 'Daftar Layanan', 'icon' => null, 'route' => 'services.index', 'status' => 1, 'permission_name' => 'services.index', 'menu_group_id' => 22, 'position' => 1, 'created_at' => '2026-09-02 15:18:03', 'updated_at' => '2026-10-07 13:32:18'],
                ['id' => 48, 'name' => 'Pesan Masuk', 'icon' => null, 'route' => 'layanan.kontak', 'status' => 1, 'permission_name' => 'layanan.kontak', 'menu_group_id' => 3, 'position' => 4, 'created_at' => '2026-09-06 19:44:23', 'updated_at' => '2026-09-17 14:13:26'],
                ['id' => 49, 'name' => 'Menu Website', 'icon' => null, 'route' => 'website-menu.index', 'status' => 1, 'permission_name' => 'agendas.index', 'menu_group_id' => 3, 'position' => 5, 'created_at' => '2026-09-06 20:40:53', 'updated_at' => '2026-09-17 13:56:47'],
                ['id' => 50, 'name' => 'Polling', 'icon' => null, 'route' => 'poll.index', 'status' => 1, 'permission_name' => 'poll.index', 'menu_group_id' => 9, 'position' => 4, 'created_at' => '2026-09-07 23:00:45', 'updated_at' => '2026-09-17 03:30:23'],
                ['id' => 51, 'name' => 'Faq & Answer', 'icon' => null, 'route' => 'faq.index', 'status' => 1, 'permission_name' => 'faq.index', 'menu_group_id' => 9, 'position' => 5, 'created_at' => '2026-09-07 23:04:55', 'updated_at' => '2026-09-07 23:04:55'],
                ['id' => 52, 'name' => 'All Studios', 'icon' => null, 'route' => 'studios.index', 'status' => 1, 'permission_name' => 'studios.index', 'menu_group_id' => 23, 'position' => 1, 'created_at' => '2026-09-08 07:14:52', 'updated_at' => '2026-09-08 07:14:52'],
                ['id' => 53, 'name' => 'Add Studio', 'icon' => null, 'route' => 'studios.create', 'status' => 1, 'permission_name' => 'studios.create', 'menu_group_id' => 23, 'position' => 2, 'created_at' => '2026-09-08 07:15:14', 'updated_at' => '2026-09-08 07:15:14'],
                ['id' => 54, 'name' => 'Semua Dokumen', 'icon' => null, 'route' => 'documents.index', 'status' => 1, 'permission_name' => 'documents.index', 'menu_group_id' => 4, 'position' => 1, 'created_at' => '2026-09-13 14:23:49', 'updated_at' => '2026-09-17 14:18:42'],
                ['id' => 55, 'name' => 'Kategori  Dokumen', 'icon' => null, 'route' => 'document-categories.index', 'status' => 1, 'permission_name' => 'document-categories.index', 'menu_group_id' => 4, 'position' => 2, 'created_at' => '2026-09-13 14:27:33', 'updated_at' => '2026-09-17 14:18:54'],
                ['id' => 56, 'name' => 'Tambah Dokumen', 'icon' => null, 'route' => 'documents.create', 'status' => 1, 'permission_name' => 'documents.create', 'menu_group_id' => 4, 'position' => 3, 'created_at' => '2026-09-16 00:39:08', 'updated_at' => '2026-09-16 00:39:08'],
                ['id' => 57, 'name' => 'Login Activity', 'icon' => null, 'route' => 'security.login-activity.index', 'status' => 1, 'permission_name' => 'login-activity.index', 'menu_group_id' => 24, 'position' => 1, 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 58, 'name' => 'Audit Log', 'icon' => null, 'route' => 'security.audit-log.index', 'status' => 1, 'permission_name' => 'audit-log.index', 'menu_group_id' => 24, 'position' => 2, 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 59, 'name' => 'Failed Login', 'icon' => null, 'route' => 'security.failed-login.index', 'status' => 1, 'permission_name' => 'failed-login.index', 'menu_group_id' => 24, 'position' => 3, 'created_at' => '2026-09-17 02:49:22', 'updated_at' => '2026-09-17 02:49:22'],
                ['id' => 60, 'name' => 'Blokir Login', 'icon' => null, 'route' => 'security.login-lockout.index', 'status' => 1, 'permission_name' => 'login-lockout.index', 'menu_group_id' => 24, 'position' => 4, 'created_at' => '2026-09-17 03:25:22', 'updated_at' => '2026-09-17 03:25:22'],
                ['id' => 61, 'name' => 'Identitas Website', 'icon' => null, 'route' => 'website-identity.edit', 'status' => 1, 'permission_name' => 'website-identity.edit', 'menu_group_id' => 25, 'position' => 1, 'created_at' => '2026-09-18 02:42:55', 'updated_at' => '2026-09-18 02:42:55'],
                ['id' => 62, 'name' => 'Konfigurasi Website', 'icon' => null, 'route' => 'website-identity.edit', 'status' => 1, 'permission_name' => 'website-identity.edit', 'menu_group_id' => 3, 'position' => 6, 'created_at' => '2026-09-18 03:00:37', 'updated_at' => '2026-09-18 03:00:37'],
                ['id' => 63, 'name' => 'Riwayat Aduan', 'icon' => null, 'route' => 'aduans.index', 'status' => 1, 'permission_name' => 'aduans.index', 'menu_group_id' => 26, 'position' => 1, 'created_at' => '2026-09-19 02:04:40', 'updated_at' => '2026-09-19 02:04:40'],
            ]);
            $this->sync('role_has_permissions', [
                ['permission_id' => 1, 'role_id' => 1],
                ['permission_id' => 1, 'role_id' => 3],
                ['permission_id' => 2, 'role_id' => 1],
                ['permission_id' => 3, 'role_id' => 1],
                ['permission_id' => 3, 'role_id' => 3],
                ['permission_id' => 4, 'role_id' => 1],
                ['permission_id' => 4, 'role_id' => 2],
                ['permission_id' => 4, 'role_id' => 3],
                ['permission_id' => 4, 'role_id' => 5],
                ['permission_id' => 5, 'role_id' => 1],
                ['permission_id' => 6, 'role_id' => 1],
                ['permission_id' => 7, 'role_id' => 1],
                ['permission_id' => 8, 'role_id' => 1],
                ['permission_id' => 9, 'role_id' => 1],
                ['permission_id' => 10, 'role_id' => 1],
                ['permission_id' => 11, 'role_id' => 1],
                ['permission_id' => 12, 'role_id' => 1],
                ['permission_id' => 13, 'role_id' => 1],
                ['permission_id' => 13, 'role_id' => 3],
                ['permission_id' => 14, 'role_id' => 1],
                ['permission_id' => 14, 'role_id' => 3],
                ['permission_id' => 15, 'role_id' => 1],
                ['permission_id' => 15, 'role_id' => 3],
                ['permission_id' => 16, 'role_id' => 1],
                ['permission_id' => 16, 'role_id' => 3],
                ['permission_id' => 17, 'role_id' => 1],
                ['permission_id' => 18, 'role_id' => 1],
                ['permission_id' => 19, 'role_id' => 1],
                ['permission_id' => 20, 'role_id' => 1],
                ['permission_id' => 21, 'role_id' => 1],
                ['permission_id' => 22, 'role_id' => 1],
                ['permission_id' => 23, 'role_id' => 1],
                ['permission_id' => 24, 'role_id' => 1],
                ['permission_id' => 25, 'role_id' => 1],
                ['permission_id' => 26, 'role_id' => 1],
                ['permission_id' => 27, 'role_id' => 1],
                ['permission_id' => 28, 'role_id' => 1],
                ['permission_id' => 29, 'role_id' => 1],
                ['permission_id' => 29, 'role_id' => 3],
                ['permission_id' => 30, 'role_id' => 1],
                ['permission_id' => 30, 'role_id' => 3],
                ['permission_id' => 31, 'role_id' => 1],
                ['permission_id' => 31, 'role_id' => 3],
                ['permission_id' => 32, 'role_id' => 1],
                ['permission_id' => 32, 'role_id' => 3],
                ['permission_id' => 33, 'role_id' => 1],
                ['permission_id' => 33, 'role_id' => 3],
                ['permission_id' => 38, 'role_id' => 1],
                ['permission_id' => 38, 'role_id' => 3],
                ['permission_id' => 39, 'role_id' => 1],
                ['permission_id' => 39, 'role_id' => 3],
                ['permission_id' => 40, 'role_id' => 1],
                ['permission_id' => 40, 'role_id' => 3],
                ['permission_id' => 41, 'role_id' => 1],
                ['permission_id' => 41, 'role_id' => 3],
                ['permission_id' => 46, 'role_id' => 1],
                ['permission_id' => 46, 'role_id' => 3],
                ['permission_id' => 47, 'role_id' => 1],
                ['permission_id' => 47, 'role_id' => 3],
                ['permission_id' => 48, 'role_id' => 1],
                ['permission_id' => 48, 'role_id' => 3],
                ['permission_id' => 49, 'role_id' => 1],
                ['permission_id' => 49, 'role_id' => 3],
                ['permission_id' => 50, 'role_id' => 1],
                ['permission_id' => 50, 'role_id' => 3],
                ['permission_id' => 50, 'role_id' => 5],
                ['permission_id' => 51, 'role_id' => 1],
                ['permission_id' => 51, 'role_id' => 3],
                ['permission_id' => 51, 'role_id' => 5],
                ['permission_id' => 52, 'role_id' => 1],
                ['permission_id' => 52, 'role_id' => 3],
                ['permission_id' => 52, 'role_id' => 5],
                ['permission_id' => 53, 'role_id' => 1],
                ['permission_id' => 53, 'role_id' => 3],
                ['permission_id' => 53, 'role_id' => 5],
                ['permission_id' => 54, 'role_id' => 1],
                ['permission_id' => 54, 'role_id' => 3],
                ['permission_id' => 54, 'role_id' => 5],
                ['permission_id' => 55, 'role_id' => 1],
                ['permission_id' => 55, 'role_id' => 2],
                ['permission_id' => 55, 'role_id' => 3],
                ['permission_id' => 55, 'role_id' => 5],
                ['permission_id' => 60, 'role_id' => 1],
                ['permission_id' => 60, 'role_id' => 3],
                ['permission_id' => 65, 'role_id' => 1],
                ['permission_id' => 65, 'role_id' => 3],
                ['permission_id' => 70, 'role_id' => 1],
                ['permission_id' => 70, 'role_id' => 3],
                ['permission_id' => 75, 'role_id' => 1],
                ['permission_id' => 75, 'role_id' => 3],
                ['permission_id' => 85, 'role_id' => 1],
                ['permission_id' => 85, 'role_id' => 2],
                ['permission_id' => 85, 'role_id' => 3],
                ['permission_id' => 85, 'role_id' => 5],
                ['permission_id' => 90, 'role_id' => 1],
                ['permission_id' => 90, 'role_id' => 2],
                ['permission_id' => 90, 'role_id' => 3],
                ['permission_id' => 90, 'role_id' => 5],
                ['permission_id' => 125, 'role_id' => 1],
                ['permission_id' => 125, 'role_id' => 3],
                ['permission_id' => 130, 'role_id' => 1],
                ['permission_id' => 130, 'role_id' => 3],
                ['permission_id' => 135, 'role_id' => 1],
                ['permission_id' => 135, 'role_id' => 3],
                ['permission_id' => 140, 'role_id' => 1],
                ['permission_id' => 140, 'role_id' => 3],
                ['permission_id' => 145, 'role_id' => 1],
                ['permission_id' => 147, 'role_id' => 1],
                ['permission_id' => 147, 'role_id' => 3],
                ['permission_id' => 148, 'role_id' => 1],
                ['permission_id' => 148, 'role_id' => 3],
                ['permission_id' => 149, 'role_id' => 1],
                ['permission_id' => 149, 'role_id' => 3],
                ['permission_id' => 150, 'role_id' => 1],
                ['permission_id' => 150, 'role_id' => 3],
                ['permission_id' => 151, 'role_id' => 1],
                ['permission_id' => 151, 'role_id' => 3],
                ['permission_id' => 163, 'role_id' => 1],
                ['permission_id' => 163, 'role_id' => 3],
                ['permission_id' => 164, 'role_id' => 1],
                ['permission_id' => 164, 'role_id' => 3],
                ['permission_id' => 165, 'role_id' => 1],
                ['permission_id' => 165, 'role_id' => 3],
                ['permission_id' => 166, 'role_id' => 1],
                ['permission_id' => 166, 'role_id' => 3],
                ['permission_id' => 176, 'role_id' => 2],
                ['permission_id' => 185, 'role_id' => 1],
                ['permission_id' => 185, 'role_id' => 3],
                ['permission_id' => 186, 'role_id' => 1],
                ['permission_id' => 186, 'role_id' => 3],
                ['permission_id' => 187, 'role_id' => 1],
                ['permission_id' => 187, 'role_id' => 3],
                ['permission_id' => 187, 'role_id' => 5],
                ['permission_id' => 188, 'role_id' => 1],
                ['permission_id' => 188, 'role_id' => 3],
                ['permission_id' => 188, 'role_id' => 5],
                ['permission_id' => 189, 'role_id' => 1],
                ['permission_id' => 189, 'role_id' => 3],
                ['permission_id' => 189, 'role_id' => 5],
                ['permission_id' => 190, 'role_id' => 1],
                ['permission_id' => 190, 'role_id' => 3],
                ['permission_id' => 190, 'role_id' => 5],
                ['permission_id' => 191, 'role_id' => 1],
                ['permission_id' => 191, 'role_id' => 3],
                ['permission_id' => 191, 'role_id' => 5],
                ['permission_id' => 197, 'role_id' => 1],
                ['permission_id' => 197, 'role_id' => 3],
                ['permission_id' => 198, 'role_id' => 1],
                ['permission_id' => 198, 'role_id' => 3],
                ['permission_id' => 199, 'role_id' => 1],
                ['permission_id' => 199, 'role_id' => 3],
                ['permission_id' => 200, 'role_id' => 1],
                ['permission_id' => 200, 'role_id' => 3],
                ['permission_id' => 201, 'role_id' => 1],
                ['permission_id' => 201, 'role_id' => 3],
                ['permission_id' => 202, 'role_id' => 1],
                ['permission_id' => 202, 'role_id' => 3],
                ['permission_id' => 203, 'role_id' => 1],
                ['permission_id' => 203, 'role_id' => 3],
                ['permission_id' => 204, 'role_id' => 1],
                ['permission_id' => 204, 'role_id' => 3],
                ['permission_id' => 205, 'role_id' => 1],
                ['permission_id' => 205, 'role_id' => 3],
                ['permission_id' => 206, 'role_id' => 1],
                ['permission_id' => 206, 'role_id' => 3],
                ['permission_id' => 207, 'role_id' => 1],
                ['permission_id' => 207, 'role_id' => 3],
                ['permission_id' => 208, 'role_id' => 1],
                ['permission_id' => 208, 'role_id' => 3],
                ['permission_id' => 209, 'role_id' => 1],
                ['permission_id' => 209, 'role_id' => 2],
                ['permission_id' => 210, 'role_id' => 1],
                ['permission_id' => 210, 'role_id' => 2],
                ['permission_id' => 211, 'role_id' => 1],
                ['permission_id' => 211, 'role_id' => 2],
                ['permission_id' => 212, 'role_id' => 1],
                ['permission_id' => 212, 'role_id' => 2],
                ['permission_id' => 213, 'role_id' => 1],
                ['permission_id' => 213, 'role_id' => 2],
                ['permission_id' => 214, 'role_id' => 1],
                ['permission_id' => 214, 'role_id' => 2],
                ['permission_id' => 215, 'role_id' => 1],
                ['permission_id' => 216, 'role_id' => 1],
                ['permission_id' => 216, 'role_id' => 2],
                ['permission_id' => 217, 'role_id' => 1],
                ['permission_id' => 218, 'role_id' => 1],
                ['permission_id' => 219, 'role_id' => 1],
                ['permission_id' => 220, 'role_id' => 1],
                ['permission_id' => 221, 'role_id' => 1],
                ['permission_id' => 222, 'role_id' => 1],
                ['permission_id' => 223, 'role_id' => 1],
                ['permission_id' => 223, 'role_id' => 3],
                ['permission_id' => 223, 'role_id' => 5],
                ['permission_id' => 224, 'role_id' => 1],
                ['permission_id' => 224, 'role_id' => 3],
            ]);
            $this->sync('model_has_roles', [
                ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => '3099cb1c-6c37-4e5a-bbb5-d11615a1b0ff'],
                ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => '5e21baa9-0ca0-4c94-9939-85068e363f57'],
                ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => '70e15b9f-535a-42a2-8ea0-9a26ad7952e5'],
                ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => '787b72ea-59d0-4d54-848b-c200bddafdd2'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => '0dd616be-4e66-463c-9b42-6529b1c7cb4f'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => '130214ce-3612-455f-ae39-f6f2a5c2b90d'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => '370a8be5-8531-453a-a504-2a610ed8538f'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => '3e354d68-8b35-410c-9fd1-827ec786b007'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => '57621d3c-c299-4cd2-b96a-9b887752cb73'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => '6bdf7417-66c8-407c-828b-77577006dda2'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => '70e15b9f-535a-42a2-8ea0-9a26ad7952e5'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => '95271004-d8a8-41a4-93eb-62a766c2758d'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 'a82db5a8-5d69-499b-a9a1-3d98ad0c47e4'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 'b5a9eb64-a547-4464-a576-e261ede0d4db'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 'c4536499-9979-4be5-a0ad-775fe9bec19d'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 'd56cc48a-b1fa-4c03-9b72-c8f92766efb3'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 'd91ccba3-e95b-4692-8f8c-63d543549331'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 'dfd391fa-c167-4547-ad17-27d52d9ffc28'],
                ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 'e5a05967-00cb-49f6-97bf-95c1bccee6e0'],
                ['role_id' => 3, 'model_type' => 'App\\Models\\User', 'model_id' => '44af87c7-cd3f-4f9d-af63-177da19727fb'],
                ['role_id' => 3, 'model_type' => 'App\\Models\\User', 'model_id' => '57f63489-134b-498c-ad05-78a3652cb916'],
                ['role_id' => 3, 'model_type' => 'App\\Models\\User', 'model_id' => '9c513953-32d1-4415-a921-8a6baf246d44'],
                ['role_id' => 3, 'model_type' => 'App\\Models\\User', 'model_id' => 'e2c23ff6-eda1-4c5c-86a4-d2d1782ed930'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '0a091f5a-c510-4110-b2b2-2f992091fb1d'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '1c3ea59a-f83a-4bdf-aa70-f38ff66f49a2'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '2f040883-ec48-424e-872d-3691d7c1a714'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '33b681e2-efc3-4017-9415-e5d5d197f2fe'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '4a85c36a-caa6-4cc9-9ab6-47f77c0a9a67'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '4c0ff420-34e5-4ecd-bb07-358862f9906a'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '514d04e5-f79b-403a-95f4-2254784db0f0'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '537f352e-a1e3-4daa-86b2-fc6b3366881c'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '56f0bbe7-c52e-4d97-833f-17fba920421f'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '58cbfa68-69aa-4cd1-95cc-9520046d9ceb'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '755e0eaa-50e4-46db-9003-7c1fc6674876'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '7d84490a-7661-415a-921c-141c05f38ded'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '896ff4a7-ec81-4be5-ad12-ba4e3bb277de'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '897f6253-86e1-4866-9072-e3e10bb51e5e'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '950e18b7-9af4-468a-8468-edc338b7deec'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => '95294c43-b3d8-4b46-b938-e4eea1f3a359'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 'a52ef0ee-6805-4b04-9f23-d6d6410829c5'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 'b497082d-dd9f-4d1c-a611-9e1331eb5393'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 'b55eb675-019c-4fa5-936e-579e2835527d'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 'bace7196-8ce8-4253-baa7-04901e31592e'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 'beaaf326-874c-48c2-b6b8-6bbe655c4df2'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 'd405298f-5481-4038-8474-2ec0283b7608'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 'dc25dea7-9969-46eb-b1ac-fd7f1b8dab77'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 'dffd1060-8999-4d7b-8623-74caf47ab546'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 'f7010485-3236-4341-a22a-48b70260a607'],
                ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 'fab7c79d-a7dc-421f-b343-aaf81bdd20da'],
            ]);
            $this->sync('agendas', [
                ['uuid' => '00c060d8-c387-435d-91d9-88d7e6d4a48c', 'user_uuid' => '0a091f5a-c510-4110-b2b2-2f992091fb1d', 'category_uuid' => 'a58f5ebc-c8ec-48da-b6f9-a80b05ca40a0', 'title' => 'Musrembang Kota Bekasi', 'slug' => 'musrembang-kota-bekasi', 'excerpt' => 'Musrembang Kota Bekasi', 'content' => '<p>musrembang kota bekasi tahun 2026</p>', 'featured_image' => 'images/EzgKQVJf6PLJzak8pTn6uQpjOpWRVGTCtIRr6vBv.jpg', 'scheduled_at' => '2026-10-11 17:00:00', 'jenis_agenda' => 'offline', 'lokasi' => 'Plaza Pemkot Bekasi', 'waktu_mulai' => null, 'waktu_akhir' => null, 'url' => null, 'parent_uuid' => null, 'recurrence_rule' => null, 'recurrence_end' => null, 'views' => 1, 'is_featured' => 1, 'is_popular' => 0, 'is_popular_manual' => 0, 'is_urgent' => 0, 'tagging' => null, 'status' => 'published', 'search_engine' => 'index', 'link' => null, 'video' => null, 'created_at' => '2026-10-12 02:36:01', 'updated_at' => '2026-10-12 12:04:11', 'deleted_at' => null, 'alasan_arsip' => 'dibatalkan'],
                ['uuid' => '89355860-0de5-4a90-9567-f8c68d5fe4de', 'user_uuid' => '0a091f5a-c510-4110-b2b2-2f992091fb1d', 'category_uuid' => 'a58f5ebc-c8ec-48da-b6f9-a80b05ca40a0', 'title' => 'dasd', 'slug' => 'dasd', 'excerpt' => 'dasda', 'content' => '<p>dsada dsada</p>', 'featured_image' => 'images/bG1cr04Br6NCTiuxlyPaoCdH5LTcCkOzsqev3RYP.png', 'scheduled_at' => null, 'jenis_agenda' => 'offline', 'lokasi' => null, 'waktu_mulai' => null, 'waktu_akhir' => null, 'url' => null, 'parent_uuid' => null, 'recurrence_rule' => null, 'recurrence_end' => null, 'views' => 2, 'is_featured' => 0, 'is_popular' => 0, 'is_popular_manual' => 0, 'is_urgent' => 0, 'tagging' => null, 'status' => 'published', 'search_engine' => 'index', 'link' => null, 'video' => null, 'created_at' => '2026-10-07 14:17:16', 'updated_at' => '2026-10-12 01:48:42', 'deleted_at' => null, 'alasan_arsip' => null],
                ['uuid' => 'fb3a98ff-7ded-4e38-a8f2-c1c21d13a3fb', 'user_uuid' => '9c513953-32d1-4415-a921-8a6baf246d44', 'category_uuid' => 'a58f5ebc-c8ec-48da-b6f9-a80b05ca40a0', 'title' => 'Pemerintah Kota Bekasi melalui Dinas Bina Marga dan Sumber Daya Air', 'slug' => 'pemerintah-kota-bekasi-melalui-dinas-bina-marga-dan-sumber-daya-air', 'excerpt' => 'Pemerintah Kota Bekasi melalui Dinas Bina Marga dan Sumber Daya Air (DBMSDA) Kota Bekasi terus melakukan upaya pengendalian Daerah Aliran Sungai (DAS) Rawa Tembaga mulai dari...', 'content' => '<p>Pemerintah Kota Bekasi melalui Dinas Bina Marga dan Sumber Daya Air (DBMSDA) Kota Bekasi terus melakukan upaya pengendalian Daerah Aliran Sungai (DAS) Rawa Tembaga mulai dari hulu hingga hilir guna meminimalisasi banjir di wilayah Kelurahan Pekayon Jaya dan Kayuringin Jaya, Kecamatan Bekasi Selatan.&nbsp;</p>



<p>DAS Rawa Tembaga sejauh 5.872 kilometer dimulai dari&nbsp; hulunya di Pulau Sirih Utama hingga hulu di Kali Bekasi. Adapun wilayah penanganan DAS Rawa Tembaga melewati Perumahan Vila Jakasetia, Perumahan Green View, Perumahan Pondok Timur Mas, Perumahan Galaxy, Perumahan Pulo Permatasari, Perumahan Taman Cikas, Grand Kamala Lagoon, Perumahan Arjuna, sebagian saluran Pondok Pekayon, Perumahan BSK, Perumnas 1, dan Kompleks Kejaksaan.&nbsp;</p>



<p>Pengendalian DAS Rawa Tembaga meliputi tindakan penanganan jangka pendek dan jangka panjang. Penanganan jangka pendek dilakukan dengan melakukan pengerukan dan pematusan saluran, penyediaan tiga buah pompa pengendali banjir, dan pemeliharaan pintu air.&nbsp;</p>



<p>Sedangkan program jangka panjang, Pemerintah Kota Bekasi telah mempersiapkan master plan Pengendalian DAS Rawa Tembaga kemudian mengajukan permohonan bantuan kepada Pemerintah Provinsi Jawa Barat dan Balai Besar Wilayah Sungai Ciliwung Cisadane (BBWSCC) Kementerian Pekerjaan Umum dan Perumahan Rakyat (PUPR).&nbsp;</p>



<p>Permohonan bantuan kepada Provinsi Jawa Barat disampaikan melalui Surat Nomor: 614/8025/DBMSDA, tanggal 29 Oktober 2021. Pemkot Bekasi mengajukan permohonan bantuan untuk pembangunan tanggul, pelebaran saluran, dan pembangunan long storage.</p>



<p>Sementara kepada BBWSCC melalui Surat Nomor: 614/8026/DBMSDA, tanggal 29 Oktober 2021, Pemkot Bekasi mengajukan permohonan bantuan pelaksanaan kegiatan di tahun anggaran 2022. Usulan Pemerintah Kota Bekasi antara lain pembangunan tanggul, pelebaran saluran, pembangunan long storage, pemindahan pintu air Rawa Tembaga ke bagian hilir mendekati Kali Bekasi, dan pembangunan polder di hulu, tengah dan hilir DAS Rawa Tembaga. Selain itu, Pemkot Bekasi juga mengusulkan pembangunan saluran dari hilir crossing Tol Jakarta - Cikampek KM 12+100 sejajar dengan sisi selatan Jalan Kalimalang menuju Kali Bekasi, permohonan bantuan alat berat untuk pengerukan sedimentasi, dan permohonan bantuan pompa mobile yang akan ditempatkan di pintu Bendung Prisdo.</p>



<p>Program penanganan DAS Rawa Tembaga telah dimulai sejak tahun 2003-2021 dan berlanjut pada rencana kegiatan tahun 2022.&nbsp;</p>



<p>Realisasi kegiatan DAS Rawa Tembaga pada 2003 adalah pembangunan Polder Cikas, 2004 pembangunan kolam retensi sisi jalan tol, 2005 pembangunan Polde Jakasetia, 2010 pembangunan rumah pompa Rawa Tembaga, 2015-2016 pembangunan Polder Green View, 2017 pembangunan Polder PPS, 2018 pembangunan Polder PTM, 2018 pembangunan Polder BSK, dan 2021 pembangunan kolam retensi sisi jalan tol.&nbsp;</p>



<p>Pemerintah Kota Bekasi juga mempersiapkan master plan DAS Rawa Tembaga dengan menerapkan lima kriteria penanganan yakni pembangunan polder/kolam retensi, duplikasi crossing saluran, normalisasi saluran berupa pelebaran dan peninggian tanggul, relokasi pintu bendung Rawa Tembaga, dan pembangunan saluran long storage.&nbsp;</p>



<p>Identifikasi masalah telah dipetakan pada Penanganan DAS Rawa Tembaga di Segmen 6 mulai dari titik DAS Rawa Tembaga STA 2+750 di Outlet Kalimalang hingga titik STA 3+124 di pintu air Rawa Tembaga.</p>



<p>Segmen 6 dimulai dari Outlet Kalimalang dengan elevasi 19,84 mdpl, kompleks BSK elevasi 16,10 mdpl dengan upaya peninggian tanggul 3-4 meter, Kantor kelurahan Kayuringin Jaya elevasi 17,60 mdpl dengan peninggian tanggul 2-2,5 meter, kompleks Kejaksaan elevasi 16,75 mdpl dengan peninggian tanggul 3-3,5 meter dan pintu air Rawa Tembaga elevasi 18,81 mdpl dengan peninggian tanggul 1 meter. (goeng/DNN)</p>', 'featured_image' => 'images/9K1D1onCiMxH8kdWBZ462VpFEApR9KkDIsj9PEUC.jpg', 'scheduled_at' => '2026-10-11 17:00:00', 'jenis_agenda' => 'offline', 'lokasi' => 'RW 002 Teluk Pucung', 'waktu_mulai' => null, 'waktu_akhir' => null, 'url' => null, 'parent_uuid' => null, 'recurrence_rule' => null, 'recurrence_end' => null, 'views' => 1573, 'is_featured' => 1, 'is_popular' => 1, 'is_popular_manual' => 0, 'is_urgent' => 0, 'tagging' => 'bekasi selatan, kota bekasi, jakasetia', 'status' => 'published', 'search_engine' => 'index', 'link' => null, 'video' => null, 'created_at' => '2021-11-11 15:53:00', 'updated_at' => '2026-10-12 12:24:15', 'deleted_at' => null, 'alasan_arsip' => null],
            ]);
            $this->sync('documents', [
                ['uuid' => '01a1196f-a6be-727e-bd0b-0693711985c6', 'user_uuid' => null, 'category_uuid' => 'd5394429-10b1-4222-86b4-68c6da8dfe0d', 'title' => 'fsdfsd', 'slug' => 'fsdfsd', 'excerpt' => 'fsdf', 'description' => 'fsdfs', 'published_at' => null, 'file' => 'documents/files/zdZmzlJ7SpL8zemyou29D54j08ZnQQioSG0aYYCA.pdf', 'thumbnail' => null, 'status' => 'active', 'created_at' => '2026-10-07 19:55:10', 'updated_at' => '2026-10-07 19:55:10', 'deleted_at' => null],
            ]);
            $this->sync('faqs', [
                ['uuid' => 'e3f9e4ac-fd07-42a6-83dd-0b7b415f7b50', 'pertanyaan' => 'Apa fungsi menu Agenda?', 'jawaban' => '<p data-v-2370c81a="" class="text-body-1 text-grey-darken-2 mb-3" style="box-sizing: inherit; background-repeat: no-repeat; margin-right: 0px; margin-left: 0px; padding: 0px; line-height: 1.5; font-size: 16px; font-family: Roboto, sans-serif; margin-bottom: 12px !important; color: rgb(97, 97, 97) !important; letter-spacing: 0.03125em !important;">Menu Agenda menampilkan kegiatan, acara, maupun jadwal yang diselenggarakan oleh unit kerja.</p>', 'kategori' => 'tentang-agenda', 'urutan' => 1, 'status' => 'active', 'created_at' => '2026-10-07 19:40:36', 'updated_at' => '2026-10-07 19:40:36'],
            ]);
            $this->sync('pages', [
                ['uuid' => '5380a3e5-adfa-437c-a0fa-bc2193d55cb2', 'title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'excerpt' => 'By accessing and using the YogaRoots website and services, you acknowledge that you have read, understood, and agreed to this Privacy Policy.', 'content' => '<h2><strong style="color: rgb(71, 85, 105); font-size: 16px; letter-spacing: 0.1px;">Last Updated:</strong><span style="color: rgb(71, 85, 105); font-size: 16px; font-weight: 400; letter-spacing: 0.1px;"> August 30, 2026</span></h2>



<p>

  Welcome to <strong>YogaRoots</strong>.

</p>



<p>

  We value your trust and are committed to protecting the privacy and security of your personal information. This Privacy Policy explains how YogaRoots collects, uses, stores, and protects your information when you visit our website, register for an account, contact us, or use our services.

</p>



<p>

  By accessing and using the YogaRoots website and services, you acknowledge that you have read, understood, and agreed to this Privacy Policy.

</p>



<h3>1. Information We Collect</h3>



<p>

  We may collect information that you voluntarily provide when using the YogaRoots website or services, including:

</p>



<ul>

  <li>Full name</li>

  <li>Email address</li>

  <li>Phone number</li>

  <li>Information submitted through contact forms</li>

  <li>Class registration or booking information</li>

  <li>Account information if you use our login features</li>

  <li>Other information you voluntarily provide to us</li>

</ul>



<p>

  We may also automatically collect certain technical information when you access our website, such as your device type, browser type, IP address, and information about your interactions with and use of the website.

</p>



<h3>2. How We Use Your Information</h3>



<p>

  The information we collect may be used to:

</p>



<ul>

  <li>Process class registrations and bookings</li>

  <li>Manage your account and membership</li>

  <li>Respond to your questions, requests, or inquiries</li>

  <li>Provide information about YogaRoots classes, schedules, programs, and services</li>

  <li>Send important information related to the services you use</li>

  <li>Improve our website, services, and overall user experience</li>

  <li>Maintain website security and prevent misuse or unauthorized activity</li>

  <li>Comply with applicable legal and regulatory requirements</li>

</ul>



<p>

  We strive to use your personal information only for purposes that are relevant and necessary to provide and improve YogaRoots services.

</p>



<h3>3. Protection of Your Information</h3>



<p>

  YogaRoots takes reasonable administrative, technical, and organizational measures to protect your personal information from unauthorized access, use, alteration, disclosure, or loss.

</p>



<p>

  However, no storage system or transmission of information over the internet can be guaranteed to be completely secure. Therefore, while we make reasonable efforts to protect your information, we cannot guarantee absolute security.

</p>



<h3>4. Third-Party Services</h3>



<p>

  To operate and improve our services, YogaRoots may work with trusted third-party service providers, including technology providers, payment processors, authentication services, analytics providers, communication platforms, and other service partners.

</p>



<p>

  Information may be shared with these third parties only to the extent reasonably necessary to provide, maintain, or improve the relevant services.

</p>



<p>

  Third-party providers may have their own privacy policies and terms of use. We encourage you to review the privacy policies of any third-party services you choose to use.

</p>



<h3>5. Cookies</h3>



<p>

  The YogaRoots website may use cookies and similar technologies to help the website function properly, remember your preferences, understand how visitors use our website, and improve your overall experience.

</p>



<p>

  You can manage or disable cookies through your browser settings. Please note that disabling certain cookies may affect the functionality or availability of some features on our website.

</p>



<h3>6. Information You Provide to Us</h3>



<p>

  When you contact YogaRoots through our contact forms, email, WhatsApp, or other communication channels, the information you provide may be used to respond to your inquiry and provide the assistance or information you need.

</p>



<p>

  We do not sell or rent your personal information to third parties.

</p>



<h3>7. Data Retention</h3>



<p>

  We may retain your personal information for as long as necessary to provide our services, fulfill the purposes for which the information was collected, complete transactions, comply with legal obligations, or address legitimate administrative and operational needs.

</p>



<p>

  When your information is no longer required, we may delete or anonymize it in accordance with our internal policies and applicable laws and regulations.

</p>



<h3>8. Your Rights</h3>



<p>

  You may contact YogaRoots if you wish to:

</p>



<ul>

  <li>Update your personal information</li>

  <li>Correct inaccurate or incomplete information</li>

  <li>Ask about the personal information we hold about you</li>

  <li>Request the deletion of certain information, where permitted and where such deletion does not conflict with applicable legal or service requirements</li>

  <li>Ask how your personal information is collected, used, or processed</li>

</ul>



<p>

  We will review and respond to requests in accordance with applicable laws, our policies, and the circumstances of each request.

</p>



<h3>9. Children\'s Privacy</h3>



<p>

  YogaRoots respects the privacy of children and does not knowingly intend to collect personal information from children without appropriate involvement or consent from a parent or legal guardian.

</p>



<p>

  If you believe that a child has provided personal information to us without appropriate consent, please contact us so that we can review the matter and take appropriate action.

</p>



<h3>10. Changes to This Privacy Policy</h3>



<p>

  YogaRoots may update this Privacy Policy from time to time to reflect changes in our services, technology, business practices, or applicable laws and regulations.

</p>



<p>

  Any changes will be published on this page, and the "Last Updated" date will be revised accordingly.

</p>



<p>

  We encourage you to review this page periodically to stay informed about how we collect, use, and protect your personal information.

</p>



<h3>11. Contact Us</h3>



<p>

  If you have any questions about this Privacy Policy or how YogaRoots handles your personal information, please contact us through the following channels:

</p>



<p>

  <strong>YogaRoots</strong>

</p>



<p>

  <strong>Email:</strong>&nbsp;

</p>



<p>

  <strong>Phone / WhatsApp:</strong> +62 813 2122 1270

</p>



<p>

  <strong>Address:</strong> Roots Prasasta Building, Jl. Kawi Raya No.37, RT.6/RW.2, Guntur, Setiabudi, South Jakarta City, Jakarta 12980

</p>



<p>

  We will do our best to assist you and provide the information you need regarding your privacy and the handling of your personal information.

</p>', 'featured_image' => 'pages/KGpKHJFc1aFrX0OocaW7I9uDWxmWiRAc9XA3gCzQ.png', 'is_published' => 1, 'published_at' => '2026-08-30 17:00:00', 'user_uuid' => null, 'has_sidebar' => 0, 'created_at' => '2025-10-16 04:07:16', 'updated_at' => '2026-09-06 21:25:55'],
                ['uuid' => '9e55815c-a4dd-4df2-8cb5-27f7de7249fd', 'title' => 'Terms & Conditions', 'slug' => 'terms-conditions', 'excerpt' => 'By accessing or using the YogaRoots website and services, you acknowledge that you have read, understood, and agreed to these Terms & Conditions.', 'content' => '`<strong style="letter-spacing: 0.1px;">Last Updated:</strong><span style="letter-spacing: 0.1px;"> August 30, 2026</span><p>

  Welcome to <strong>YogaRoots</strong>.

</p>



<p>

  These Terms &amp; Conditions explain the rules and guidelines for using the YogaRoots website, purchasing memberships, booking classes, making payments, and using our services.

</p>



<p>

  By accessing or using the YogaRoots website and services, you acknowledge that you have read, understood, and agreed to these Terms &amp; Conditions.

</p>



<h3>1. Website Use</h3>



<p>

  You agree to use the YogaRoots website responsibly and only for lawful purposes.

</p>



<p>

  You must not use the website to:

</p>



<ul>

  <li>Provide false, misleading, or inaccurate information</li>

  <li>Attempt to gain unauthorized access to accounts or systems</li>

  <li>Interfere with or disrupt the operation of the website</li>

  <li>Use the website for fraudulent, abusive, or unlawful activities</li>

  <li>Copy, reproduce, or misuse YogaRoots content without permission</li>

</ul>



<p>

  YogaRoots reserves the right to restrict or suspend access to the website or services if we reasonably believe that these Terms &amp; Conditions have been violated.

</p>



<h3>2. Account Registration</h3>



<p>

  Certain YogaRoots services may require you to create an account.

</p>



<p>

  When creating an account, you agree to provide accurate and up-to-date information and to keep your account information secure.

</p>



<ul>

  <li>You are responsible for maintaining the confidentiality of your login credentials.</li>

  <li>You are responsible for activities performed through your account.</li>

  <li>You must notify YogaRoots if you believe your account has been accessed without authorization.</li>

  <li>You must not create an account using another person\'s identity or information without permission.</li>

</ul>



<h3>3. Membership</h3>



<p>

  YogaRoots offers different membership packages and options designed to suit different practice needs.

</p>



<p>

  Each membership option may have different pricing, class quota, duration, benefits, and conditions.

</p>



<ul>

  <li>Memberships are personal and may only be used by the registered member.</li>

  <li>Memberships cannot be transferred, resold, or shared with another person.</li>

  <li>A membership becomes active after the payment has been successfully completed and confirmed.</li>

  <li>Membership validity follows the duration stated in the selected membership option.</li>

  <li>Unused class quota may expire when the membership period ends.</li>

  <li>Unlimited memberships may provide unlimited class access during the applicable membership period, subject to class availability and booking requirements.</li>

</ul>



<p>

  YogaRoots reserves the right to update membership packages, pricing, benefits, availability, or terms from time to time.

</p>



<h3>4. Membership Options</h3>



<p>

  A membership package may contain one or more membership options. Each option may have its own price, duration, and class quota.

</p>



<p>

  When purchasing a membership, you are responsible for reviewing the selected option before completing your payment.

</p>



<p>

  The membership option selected at checkout determines the applicable price, duration, quota, and benefits associated with your membership.

</p>



<h3>5. Class Booking</h3>



<p>

  Class availability is limited and subject to the capacity of each scheduled class.

</p>



<p>

  Members must complete the booking process through the available YogaRoots booking system before attending a class.

</p>



<ul>

  <li>A booking is considered confirmed once the booking process has been successfully completed.</li>

  <li>Class availability may change at any time based on capacity.</li>

  <li>Members are encouraged to arrive at least 10 minutes before the scheduled class.</li>

  <li>Late arrival may result in the member being unable to join the class if entering the studio may disturb the ongoing practice.</li>

  <li>Members must follow the instructions provided by the instructor and YogaRoots staff.</li>

</ul>



<h3>6. Class Cancellation &amp; No-Show</h3>



<p>

  We understand that plans can change. However, we ask members to cancel their bookings as early as possible so that another member may have the opportunity to attend.

</p>



<ul>

  <li>Class cancellations must be made through the available YogaRoots booking system.</li>

  <li>Late cancellations may result in the applicable class quota being deducted.</li>

  <li>No-shows may result in the applicable class quota being deducted.</li>

  <li>Repeated cancellations or no-shows may affect future booking privileges.</li>

</ul>



<p>

  Specific cancellation periods or policies may apply to certain classes, workshops, events, or special programs.

</p>



<h3>7. Payments</h3>



<p>

  All prices displayed on the YogaRoots website are stated in Indonesian Rupiah (IDR), unless otherwise specified.

</p>



<ul>

  <li>A membership or service is considered purchased only after payment has been successfully completed.</li>

  <li>Prices, discounts, and promotional offers may change from time to time.</li>

  <li>Promotional prices may only be available for a specific period or under specific conditions.</li>

  <li>The final amount payable will be displayed during the checkout process before payment is completed.</li>

  <li>Payment transactions may be processed through third-party payment providers.</li>

</ul>



<p>

  By proceeding with a payment, you agree to the applicable payment terms and conditions provided by YogaRoots and the relevant payment provider.

</p>



<h3>8. Refunds &amp; Cancellations</h3>



<p>

  Refund eligibility may depend on the type of purchase, payment status, membership status, booking status, and applicable YogaRoots policies.

</p>



<p>

  Membership purchases and completed payments may not be refundable unless otherwise stated or required under applicable laws and regulations.

</p>



<p>

  If you believe that a payment was made incorrectly or you experience a payment-related issue, please contact YogaRoots as soon as possible so that we can review the transaction.

</p>



<h3>9. Studio Rules</h3>



<p>

  YogaRoots is a shared space where everyone should feel comfortable, safe, and respected.

</p>



<p>

  Members are expected to:

</p>



<ul>

  <li>Respect instructors, staff, and fellow members.</li>

  <li>Keep phones on silent during classes.</li>

  <li>Maintain cleanliness and cleanliness of shared studio areas.</li>

  <li>Return studio equipment after use.</li>

  <li>Follow instructor and staff instructions.</li>

  <li>Avoid behavior that may disturb or make other members uncomfortable.</li>

  <li>Take reasonable care of YogaRoots facilities and equipment.</li>

</ul>



<p>

  YogaRoots may take reasonable action if a member\'s behavior negatively affects the safety, comfort, or experience of other members.

</p>



<h3>10. Health &amp; Safety</h3>



<p>

  Yoga is a physical activity and may involve physical risks. Every member is responsible for practicing according to their own physical ability and condition.

</p>



<ul>

  <li>Members should inform the instructor about relevant physical limitations, injuries, or conditions before participating.</li>

  <li>Members should not force movements beyond their comfortable limits.</li>

  <li>If you experience pain, dizziness, discomfort, or other concerning symptoms, stop practicing and inform the instructor immediately.</li>

  <li>Members are responsible for determining whether they are physically able to participate in a class.</li>

</ul>



<p>

  YogaRoots and its instructors are not a substitute for professional medical advice, diagnosis, or treatment.

</p>



<p>

  If you have concerns about whether yoga or a particular class is suitable for you, we recommend consulting a qualified healthcare professional before participating.

</p>



<h3>11. Personal Belongings</h3>



<p>

  Members are responsible for their personal belongings while visiting YogaRoots.

</p>



<p>

  We recommend keeping valuable items secure and bringing only essential belongings into the studio.

</p>



<p>

  YogaRoots is not responsible for loss, theft, or damage to personal belongings except where responsibility cannot legally be excluded.

</p>



<h3>12. Class Schedules &amp; Instructors</h3>



<p>

  YogaRoots makes reasonable efforts to maintain the published class schedule. However, schedules and instructors may occasionally change due to operational requirements, instructor availability, maintenance, special events, or other circumstances.

</p>



<p>

  YogaRoots reserves the right to modify, reschedule, replace, or cancel a class when necessary.

</p>



<p>

  Where reasonably possible, YogaRoots will provide notice of significant schedule changes to affected members.

</p>



<h3>13. Promotions &amp; Special Offers</h3>



<p>

  YogaRoots may offer promotional packages, discounts, introductory offers, events, workshops, or other special programs from time to time.

</p>



<p>

  Each promotion may have its own eligibility requirements, validity period, pricing, limitations, and terms.

</p>



<ul>

  <li>Promotions may only be available for a limited period.</li>

  <li>Promotions may not be combined with other offers unless explicitly stated.</li>

  <li>Promotional benefits cannot be exchanged for cash unless otherwise stated.</li>

  <li>YogaRoots may modify or discontinue a promotion in accordance with its applicable terms.</li>

</ul>



<h3>14. Intellectual Property</h3>



<p>

  All content available on the YogaRoots website, including logos, photographs, graphics, illustrations, text, videos, designs, and other materials, is owned by or licensed to YogaRoots unless otherwise stated.

</p>



<p>

  You may access and use the content for personal, non-commercial purposes only.

</p>



<p>

  You may not reproduce, distribute, modify, publish, sell, or commercially use YogaRoots content without prior written permission.

</p>



<h3>15. Third-Party Services</h3>



<p>

  YogaRoots may use or provide access to third-party services, including payment providers, communication platforms, analytics services, authentication services, and other technology providers.

</p>



<p>

  Your use of third-party services may be subject to the terms and policies of the relevant third-party provider.

</p>



<p>

  YogaRoots is not responsible for the policies, availability, or practices of third-party services that are outside our control.

</p>



<h3>16. Website Availability</h3>



<p>

  YogaRoots aims to keep the website and services available and functioning properly. However, we cannot guarantee that the website will always be available, uninterrupted, or free from errors.

</p>



<p>

  Website availability may occasionally be affected by maintenance, technical issues, network problems, security incidents, or circumstances beyond our reasonable control.

</p>



<h3>17. Limitation of Responsibility</h3>



<p>

  YogaRoots strives to provide a safe, comfortable, and high-quality experience for all members.

</p>



<p>

  However, participation in yoga and physical activities involves inherent risks. Members are responsible for practicing within their own capabilities and following reasonable instructions provided by instructors and staff.

</p>



<p>

  Nothing in these Terms &amp; Conditions is intended to exclude or limit any rights, responsibilities, or liabilities that cannot legally be excluded or limited under applicable laws and regulations.

</p>



<h3>18. Changes to These Terms</h3>



<p>

  YogaRoots may update these Terms &amp; Conditions from time to time to reflect changes in our services, business practices, technology, or applicable laws and regulations.

</p>



<p>

  Any changes will be published on this page, and the "Last Updated" date will be revised accordingly.

</p>



<p>

  We encourage you to review this page periodically to stay informed about the terms that apply to your use of YogaRoots services.

</p>



<h3>19. Contact Us</h3>



<p>

  If you have any questions about these Terms &amp; Conditions, membership, class bookings, payments, or other YogaRoots services, please contact us through the following channels:

</p>



<p>

  <strong>YogaRoots</strong>

</p>



<p>

  <strong>Email:</strong>&nbsp;

</p>



<p>

  <strong>Phone / WhatsApp:</strong> +62 813 2122 1270

</p>



<p>

  <strong>Address:</strong> Roots Prasasta Building, Jl. Kawi Raya No.37, RT.6/RW.2, Guntur, Setiabudi, South Jakarta City, Jakarta 12980

</p>



<p>

  We will do our best to assist you and provide the information you need regarding our services, memberships, bookings, and these Terms &amp; Conditions.

</p>', 'featured_image' => 'pages/UYCMzdX1OfuyrVRPYwicwNu1JZnHYBZhRSUjL4ki.jpg', 'is_published' => 1, 'published_at' => '2026-09-12 17:00:00', 'user_uuid' => '787b72ea-59d0-4d54-848b-c200bddafdd2', 'has_sidebar' => 0, 'created_at' => '2026-09-13 03:01:40', 'updated_at' => '2026-09-13 03:01:40'],
                ['uuid' => 'fa0b4dfb-37d5-4e15-b234-f65eab549c77', 'title' => 'Sejarah', 'slug' => 'sejarah', 'excerpt' => 'Jejak berdirinya Portal Agenda Pemerintah Kota Bekasi dan semangat olahraga daerah menuju PORPROV XV Jawa Barat 2026.', 'content' => '<p>Portal Agenda Pemerintah Kota Bekasi dibangun sebagai pintu tunggal informasi kegiatan daerah. Seluruh jadwal acara, agenda pimpinan, dan kegiatan perangkat daerah dirangkum dalam satu laman agar mudah diakses masyarakat, media, dan tamu yang berkunjung ke Kota Bekasi.</p><p>Seiring waktu, portal ini berkembang mengikuti kebutuhan pelayanan publik. Informasi tidak lagi tersebar di banyak kanal, melainkan ditata berdasarkan kategori, dilengkapi pencarian, serta terhubung dengan kalender kegiatan sehingga setiap agenda tercatat rapi dan dapat dipertanggungjawabkan.</p><p>Memasuki tahun 2026, portal ini mendapat peran baru sebagai pusat informasi kontingen Kota Bekasi pada Pekan Olahraga Provinsi (PORPROV) XV Jawa Barat. Jadwal pertandingan, venue, live score, klasemen medali, dan kabar para atlet disajikan berdampingan dengan agenda pemerintahan, menjadi saksi perjalanan Kota Bekasi menuju podium juara.</p>', 'featured_image' => null, 'is_published' => 1, 'published_at' => '2026-10-12 08:07:45', 'user_uuid' => null, 'has_sidebar' => 0, 'created_at' => '2026-10-12 08:07:45', 'updated_at' => '2026-10-12 08:07:45'],
            ]);
            $this->sync('polls', [
                ['uuid' => '022cc9a2-dd4a-4dd2-8063-391ae8831214', 'question' => 'Bagaimana menurut Anda terkait informasi yang tersedia pada website kami?', 'options' => '["Baik","Cukup Baik","Kurang"]', 'status' => 'active', 'created_at' => '2025-11-11 06:16:57', 'updated_at' => '2025-11-11 06:16:57'],
            ]);
            $this->sync('services', [
                ['uuid' => '5a9becf1-4aa5-479a-94d6-da2f8ab042b0', 'name' => 'Silat V2', 'category' => 'internal', 'url' => 'https://silat.bekasikota.go.id/', 'image' => 'services/PoYb7VaxSOTf8uD7Fl4jf5YNYDov5iiPHfoHVAz7.png', 'description' => 'Layanan Perizinan Terpadu, melayani seluruh perizinan di Kota Bekasi', 'is_active' => 1, 'created_at' => '2026-10-12 01:32:29', 'updated_at' => '2026-10-12 01:32:29'],
                ['uuid' => 'c19f7c6e-ad28-44e9-aa85-9ac2c63e7bdf', 'name' => 'Mall Pelayanan Publik', 'category' => 'internal', 'url' => 'https://mpp.bekasikota.go.id/', 'image' => 'services/a52BAB8jm2TRQo4APdbtZRzStANnlaw202NbZ1nQ.png', 'description' => 'Akses semua layanan Publik secara langsung di Mall Pelayanan Publik', 'is_active' => 1, 'created_at' => '2026-10-12 01:31:17', 'updated_at' => '2026-10-12 01:31:17'],
                ['uuid' => 'fdab5831-1bcc-4f86-873f-dfdf43fc9751', 'name' => 'SP4N Lapor', 'category' => 'external', 'url' => 'https://www.lapor.go.id/', 'image' => 'services/e4L6979pldMv2JCioO1rlvT7AWIzLXISfumYDmgh.png', 'description' => 'layanan resmi pengaduan, aspirasi, dan keluhan masyarakat terkait pelayanan publik.', 'is_active' => 1, 'created_at' => '2026-10-12 12:16:50', 'updated_at' => '2026-10-12 12:17:26'],
            ]);
            $this->sync('seo', [
                ['id' => 1, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2025-09-16 00:51:45', 'updated_at' => '2025-09-16 00:51:45', 'model_type' => 'App\\Models\\Category', 'model_id' => '0f6f80de-b410-4b7a-88b2-08411c5a063c'],
                ['id' => 2, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2025-09-16 01:03:16', 'updated_at' => '2025-09-16 01:03:16', 'model_type' => 'App\\Models\\Category', 'model_id' => '34103609-8116-4baf-bd17-557fc6989e8e'],
                ['id' => 3, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2025-09-16 01:03:22', 'updated_at' => '2025-09-16 01:03:22', 'model_type' => 'App\\Models\\Category', 'model_id' => '2162d145-9ef3-4e2f-8c55-81971a015bc5'],
                ['id' => 4, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2025-09-16 01:03:27', 'updated_at' => '2025-09-16 01:03:27', 'model_type' => 'App\\Models\\Category', 'model_id' => 'bb006a6e-36bc-48cb-a84f-2a562489bb54'],
                ['id' => 5, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2025-09-16 01:03:50', 'updated_at' => '2025-09-16 01:03:50', 'model_type' => 'App\\Models\\Category', 'model_id' => 'f16ac019-a6b8-4ca6-984a-6c87624d06e9'],
                ['id' => 6, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2025-09-16 01:03:56', 'updated_at' => '2025-09-16 01:03:56', 'model_type' => 'App\\Models\\Category', 'model_id' => 'd9c59085-6120-487f-bd89-8924f043b70f'],
                ['id' => 7, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2025-09-16 01:04:08', 'updated_at' => '2025-09-16 01:04:08', 'model_type' => 'App\\Models\\Category', 'model_id' => 'cbe3bfd5-2852-4616-ad1e-e64510623354'],
                ['id' => 8, 'description' => 'dasdsa', 'title' => 'dsadas', 'image' => 'images/BkDwT5z1GU7ge3jsY7cbjqxitH9GVI5bGWrVJXVf.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/dsadas', 'created_at' => '2025-09-21 21:24:40', 'updated_at' => '2025-09-21 21:24:40', 'model_type' => 'App\\Models\\Article', 'model_id' => 'a7a1ddfd-e34d-4a9b-b1f1-8c3b3f980101'],
                ['id' => 9, 'description' => 'das', 'title' => 'dsad', 'image' => 'images/vOOiSvvssJ0m7N5ZuDV1kqiOAlmAFT1LFkrWIVZM.png', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/dsad', 'created_at' => '2025-09-21 23:45:55', 'updated_at' => '2025-09-21 23:45:55', 'model_type' => 'App\\Models\\Article', 'model_id' => '9fea3519-9e45-42b6-b0a7-4eda3d36336c'],
                ['id' => 10, 'description' => 'Kunjungan kerja Pj Wali Kota Bekasi Gani Muhamad didampingi Istri Yolla Kusuma', 'title' => 'Dedikasi Guru SLB, Pj Wali Kota Bekasi Panjatkan Syukur dan Bangga', 'image' => 'images/dqG6N4LdZt2BpFYbkRYEipCXhu49cY8Hn4feSGYT.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'http://103.76.148.154:8000/backend/articles/dedikasi-guru-slb-pj-wali-kota-bekasi-panjatkan-syukur-dan-bangga', 'created_at' => '2025-09-29 08:50:56', 'updated_at' => '2025-09-29 08:50:56', 'model_type' => 'App\\Models\\Article', 'model_id' => '225a5ce5-27a3-4ebe-a15a-1151ea17f0b2'],
                ['id' => 11, 'description' => 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'title' => 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'image' => 'images/4Y8xWeFxXgrHTaGGrRR49cTzRgBbnylh8RT8pUcw.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/dorong-umkm-naik-kelas-pemkot-bekasi-gelar-sosialisasi-program-kur-bank-bjb', 'created_at' => '2025-09-30 04:31:02', 'updated_at' => '2025-09-30 04:31:02', 'model_type' => 'App\\Models\\Article', 'model_id' => 'e26fc79e-6747-4852-8e1b-711cae82c811'],
                ['id' => 12, 'description' => 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'title' => 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'image' => 'images/4Y8xWeFxXgrHTaGGrRR49cTzRgBbnylh8RT8pUcw.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'http://103.76.148.154:8000/backend/articles/dorong-umkm-naik-kelas-pemkot-bekasi-gelar-sosialisasi-program-kur-bank-bjb', 'created_at' => '2025-09-30 04:36:50', 'updated_at' => '2025-09-30 04:36:50', 'model_type' => 'App\\Models\\Article', 'model_id' => 'e26fc79e-6747-4852-8e1b-711cae82c811'],
                ['id' => 13, 'description' => 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'title' => 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'image' => 'images/4Y8xWeFxXgrHTaGGrRR49cTzRgBbnylh8RT8pUcw.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'http://103.76.148.154:8000/backend/articles/dorong-umkm-naik-kelas-pemkot-bekasi-gelar-sosialisasi-program-kur-bank-bjb', 'created_at' => '2025-09-30 04:37:18', 'updated_at' => '2025-09-30 04:37:18', 'model_type' => 'App\\Models\\Article', 'model_id' => 'e26fc79e-6747-4852-8e1b-711cae82c811'],
                ['id' => 14, 'description' => 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'title' => 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'image' => 'images/4Y8xWeFxXgrHTaGGrRR49cTzRgBbnylh8RT8pUcw.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'http://103.76.148.154:8000/backend/articles/dorong-umkm-naik-kelas-pemkot-bekasi-gelar-sosialisasi-program-kur-bank-bjb', 'created_at' => '2025-09-30 04:37:35', 'updated_at' => '2025-09-30 04:37:35', 'model_type' => 'App\\Models\\Article', 'model_id' => 'e26fc79e-6747-4852-8e1b-711cae82c811'],
                ['id' => 15, 'description' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'title' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'image' => 'images/89rXVtDwjTG9EmdgFPogoeFPwAJIirofhidjdrEu.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'http://103.76.148.154:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', 'created_at' => '2025-09-30 04:39:50', 'updated_at' => '2025-09-30 04:39:50', 'model_type' => 'App\\Models\\Article', 'model_id' => 'b74e1fd1-0417-4980-b207-eb8a9901a878'],
                ['id' => 16, 'description' => 'fsdf', 'title' => 'fsdf', 'image' => 'images/58jRWycX0LL0Mmk20xVxBZuLhb13b7gCtnbVFpLf.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/fsdf', 'created_at' => '2025-09-30 04:51:15', 'updated_at' => '2025-09-30 04:51:15', 'model_type' => 'App\\Models\\Article', 'model_id' => 'ef525ad4-9e6b-40ea-81fd-71de34a4e3a9'],
                ['id' => 17, 'description' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'title' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'image' => 'images/89rXVtDwjTG9EmdgFPogoeFPwAJIirofhidjdrEu.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', 'created_at' => '2025-09-30 05:26:51', 'updated_at' => '2025-09-30 05:26:51', 'model_type' => 'App\\Models\\Article', 'model_id' => 'b74e1fd1-0417-4980-b207-eb8a9901a878'],
                ['id' => 18, 'description' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'title' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'image' => 'images/89rXVtDwjTG9EmdgFPogoeFPwAJIirofhidjdrEu.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', 'created_at' => '2025-09-30 05:29:34', 'updated_at' => '2025-09-30 05:29:34', 'model_type' => 'App\\Models\\Article', 'model_id' => 'b74e1fd1-0417-4980-b207-eb8a9901a878'],
                ['id' => 19, 'description' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'title' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'image' => 'images/89rXVtDwjTG9EmdgFPogoeFPwAJIirofhidjdrEu.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', 'created_at' => '2025-09-30 05:29:45', 'updated_at' => '2025-09-30 05:29:45', 'model_type' => 'App\\Models\\Article', 'model_id' => 'b74e1fd1-0417-4980-b207-eb8a9901a878'],
                ['id' => 20, 'description' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'title' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'image' => 'images/FzjAPJph4NTicYYWBBVghzb9RwjxwfLrr8Jl6wD4.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', 'created_at' => '2025-09-30 05:33:35', 'updated_at' => '2025-09-30 05:33:35', 'model_type' => 'App\\Models\\Article', 'model_id' => 'b74e1fd1-0417-4980-b207-eb8a9901a878'],
                ['id' => 21, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2025-10-01 06:23:46', 'updated_at' => '2025-10-01 06:23:46', 'model_type' => 'App\\Models\\Category', 'model_id' => 'e7472961-7151-4c89-bfa8-04eee74d9111'],
                ['id' => 22, 'description' => 'Juara 3 Lomba Menyanyi', 'title' => 'Dinda Nurohman', 'image' => 'images/ZGZUKCAV10bEiRXlkcvW0lKT9J2n50XglYts9jWI.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'http://103.76.148.154:8000/backend/articles/dinda-nurohman', 'created_at' => '2025-10-02 02:24:30', 'updated_at' => '2025-10-02 02:24:30', 'model_type' => 'App\\Models\\Article', 'model_id' => 'ef525ad4-9e6b-40ea-81fd-71de34a4e3a9'],
                ['id' => 23, 'description' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'title' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'image' => 'images/bLT73ZQ6e8aVFwZ7hycMkyi8GmfJeuYGvVFuvoK8.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'http://103.76.148.154:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', 'created_at' => '2025-10-02 02:25:05', 'updated_at' => '2025-10-02 02:25:05', 'model_type' => 'App\\Models\\Article', 'model_id' => 'b74e1fd1-0417-4980-b207-eb8a9901a878'],
                ['id' => 24, 'description' => 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'title' => 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'image' => 'images/qF6sCsDs9ffRTwq44Ofa41hrriVirZ5H47Uee1Bd.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'http://103.76.148.154:8000/backend/articles/dorong-umkm-naik-kelas-pemkot-bekasi-gelar-sosialisasi-program-kur-bank-bjb', 'created_at' => '2025-10-02 02:25:16', 'updated_at' => '2025-10-02 02:25:16', 'model_type' => 'App\\Models\\Article', 'model_id' => 'e26fc79e-6747-4852-8e1b-711cae82c811'],
                ['id' => 25, 'description' => 'Juara lomba menyanyi solo berhasil diraih Adinda eko Subagio', 'title' => 'Adinda Eko Subagio Juara 1 Lomba menyanyi solo', 'image' => 'images/FQ4F5RMM3u0ljXS9yWYHDnXsFsPDofYnO3sqgBgV.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'http://103.76.148.154:8000/backend/articles/adinda-eko-subagio-juara-1-lomba-menyanyi-solo', 'created_at' => '2025-10-02 02:30:38', 'updated_at' => '2025-10-02 02:30:38', 'model_type' => 'App\\Models\\Article', 'model_id' => '3f026350-7718-43bf-bfd0-088a021e8089'],
                ['id' => 26, 'description' => 'Juara lomba menyanyi solo berhasil diraih Adinda eko Subagio', 'title' => 'Adinda Eko Subagio Juara 1 Lomba menyanyi solo', 'image' => 'images/7vGyW3v4tjrT4MS8i8Alf3KVGE7mzSV3dXd7E4XC.png', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/adinda-eko-subagio-juara-1-lomba-menyanyi-solo', 'created_at' => '2025-10-02 05:50:10', 'updated_at' => '2025-10-02 05:50:10', 'model_type' => 'App\\Models\\Article', 'model_id' => '3f026350-7718-43bf-bfd0-088a021e8089'],
                ['id' => 27, 'description' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'title' => 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'image' => 'images/bLT73ZQ6e8aVFwZ7hycMkyi8GmfJeuYGvVFuvoK8.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', 'created_at' => '2025-10-02 06:17:51', 'updated_at' => '2025-10-02 06:17:51', 'model_type' => 'App\\Models\\Article', 'model_id' => 'b74e1fd1-0417-4980-b207-eb8a9901a878'],
                ['id' => 28, 'description' => 'Kunjungan kerja Pj Wali Kota Bekasi Gani Muhamad didampingi Istri Yolla Kusuma', 'title' => 'Dedikasi Guru SLB, Pj Wali Kota Bekasi Panjatkan Syukur dan Bangga', 'image' => 'images/dqG6N4LdZt2BpFYbkRYEipCXhu49cY8Hn4feSGYT.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/dedikasi-guru-slb-pj-wali-kota-bekasi-panjatkan-syukur-dan-bangga', 'created_at' => '2025-10-02 06:19:06', 'updated_at' => '2025-10-02 06:19:06', 'model_type' => 'App\\Models\\Article', 'model_id' => '225a5ce5-27a3-4ebe-a15a-1151ea17f0b2'],
                ['id' => 29, 'description' => 'Juara lomba menyanyi solo berhasil diraih Adinda eko Subagio', 'title' => 'Adinda Eko Subagio Juara 1 Lomba menyanyi solo', 'image' => 'images/pLAsYqB3RjhFGxjr9r3ym0aGIboEuOo9mQqBxmvg.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/adinda-eko-subagio-juara-1-lomba-menyanyi-solo', 'created_at' => '2025-10-16 03:16:27', 'updated_at' => '2025-10-16 03:16:27', 'model_type' => 'App\\Models\\Article', 'model_id' => '3f026350-7718-43bf-bfd0-088a021e8089'],
                ['id' => 30, 'description' => 'Kebersaman pemimpin daerah dengan Anak-anak Down Syndrome', 'title' => 'Wali dan Wakil Wali Kota Bekasi Nyanyi Bersama Anak Down Syndrome di CFD Kota Bekasi', 'image' => 'images/ESvkOUk4fUn3QIxfGzTdnBDtqcSiQDop8TtFDYvP.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/wali-dan-wakil-wali-kota-bekasi-nyanyi-bersama-anak-down-syndrome-di-cfd-kota-bekasi', 'created_at' => '2025-10-16 03:19:35', 'updated_at' => '2025-10-16 03:19:35', 'model_type' => 'App\\Models\\Article', 'model_id' => '4922f25e-24ab-45d8-a7bb-ff650c55a578'],
                ['id' => 31, 'description' => 'Wali Kota Bekasi Tri Adhianto Terima Kunjungan Edukasi Siswa SLB Patriot', 'title' => 'Wali Kota Bekasi Tri Adhianto Terima Kunjungan Edukasi Siswa SLB Patriot', 'image' => 'images/jjIvZ0LsGljnaJnPO0VwRRaKIIeoJlGC549B69OZ.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/wali-kota-bekasi-tri-adhianto-terima-kunjungan-edukasi-siswa-slb-patriot', 'created_at' => '2025-10-17 07:47:37', 'updated_at' => '2025-10-17 07:47:37', 'model_type' => 'App\\Models\\Article', 'model_id' => '66ed2a58-530d-4460-b1da-a6d41933540e'],
                ['id' => 32, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2025-10-31 03:07:08', 'updated_at' => '2025-10-31 03:07:08', 'model_type' => 'App\\Models\\Category', 'model_id' => '72561291-cdcb-484b-a556-0400fbd53c3d'],
                ['id' => 33, 'description' => 'fsdfsdfsd', 'title' => 'ffdsdsfsd', 'image' => 'images/QmD0ntU3jgYsDD1rEXPWdRvz9S1wFj1Ncl1j6lNb.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/ffdsdsfsd', 'created_at' => '2025-10-31 03:31:16', 'updated_at' => '2025-10-31 03:31:16', 'model_type' => 'App\\Models\\Article', 'model_id' => '1ca939a6-229f-46f2-9ef9-90f6ecf368ef'],
                ['id' => 34, 'description' => 'fsdfsdfsd', 'title' => 'ffdsdsfsd', 'image' => 'images/QmD0ntU3jgYsDD1rEXPWdRvz9S1wFj1Ncl1j6lNb.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/ffdsdsfsd', 'created_at' => '2025-10-31 03:41:18', 'updated_at' => '2025-10-31 03:41:18', 'model_type' => 'App\\Models\\Article', 'model_id' => '1ca939a6-229f-46f2-9ef9-90f6ecf368ef'],
                ['id' => 35, 'description' => 'Menyanyi Solo SMPLB FLS3N Disabilitas 2025', 'title' => 'SMPLB FLS3N Disabilitas 2025', 'image' => 'images/GXWIGs16PIQbg7gi3yhpcQb1gg58JVu4i6QuOLIt.png', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/smplb-fls3n-disabilitas-2025', 'created_at' => '2025-10-31 06:48:45', 'updated_at' => '2025-10-31 06:48:45', 'model_type' => 'App\\Models\\Article', 'model_id' => '1ca939a6-229f-46f2-9ef9-90f6ecf368ef'],
                ['id' => 36, 'description' => 'Menyanyi Solo SMPLB FLS3N Disabilitas 2025', 'title' => 'SMPLB FLS3N Disabilitas 2025', 'image' => 'images/GXWIGs16PIQbg7gi3yhpcQb1gg58JVu4i6QuOLIt.png', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/smplb-fls3n-disabilitas-2025', 'created_at' => '2025-11-11 08:00:48', 'updated_at' => '2025-11-11 08:00:48', 'model_type' => 'App\\Models\\Article', 'model_id' => '1ca939a6-229f-46f2-9ef9-90f6ecf368ef'],
                ['id' => 37, 'description' => null, 'title' => 'SLB Patriot Bekasi di Bawah Yayasan Dharma Wanita: Hadir untuk ABK dengan Biaya Sekolah Berkeadilan', 'image' => null, 'author' => 'Ainun Mutia Zalfina', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/slb-patriot-bekasi-di-bawah-yayasan-dharma-wanita-hadir-untuk-abk-dengan-biaya-sekolah-berkeadilan', 'created_at' => '2026-04-17 07:08:11', 'updated_at' => '2026-04-17 07:08:11', 'model_type' => 'App\\Models\\Article', 'model_id' => '60dd784c-461e-4ce9-93ae-60d545e5c545'],
                ['id' => 38, 'description' => null, 'title' => 'SLB Patriot Bekasi di Bawah Yayasan Dharma Wanita: Hadir untuk ABK dengan Biaya Sekolah Berkeadilan', 'image' => null, 'author' => 'Ainun Mutia Zalfina', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/slb-patriot-bekasi-di-bawah-yayasan-dharma-wanita-hadir-untuk-abk-dengan-biaya-sekolah-berkeadilan', 'created_at' => '2026-04-17 07:10:27', 'updated_at' => '2026-04-17 07:10:27', 'model_type' => 'App\\Models\\Article', 'model_id' => '60dd784c-461e-4ce9-93ae-60d545e5c545'],
                ['id' => 39, 'description' => null, 'title' => 'SLB Patriot Bekasi di Bawah Yayasan Dharma Wanita: Hadir untuk ABK dengan Biaya Sekolah Berkeadilan', 'image' => 'images/8osmllekNYHfwNj1DOfoqmnaW87Cei8CcwavT0pQ.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/slb-patriot-bekasi-di-bawah-yayasan-dharma-wanita-hadir-untuk-abk-dengan-biaya-sekolah-berkeadilan', 'created_at' => '2026-05-13 09:15:26', 'updated_at' => '2026-05-13 09:15:26', 'model_type' => 'App\\Models\\Article', 'model_id' => '60dd784c-461e-4ce9-93ae-60d545e5c545'],
                ['id' => 40, 'description' => null, 'title' => 'SLB Patriot Bekasi di Bawah Yayasan Dharma Wanita: Hadir untuk ABK dengan Biaya Sekolah Berkeadilan', 'image' => 'images/8osmllekNYHfwNj1DOfoqmnaW87Cei8CcwavT0pQ.jpg', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/slb-patriot-bekasi-di-bawah-yayasan-dharma-wanita-hadir-untuk-abk-dengan-biaya-sekolah-berkeadilan', 'created_at' => '2026-05-13 09:16:41', 'updated_at' => '2026-05-13 09:16:41', 'model_type' => 'App\\Models\\Article', 'model_id' => '60dd784c-461e-4ce9-93ae-60d545e5c545'],
                ['id' => 41, 'description' => 'PENGUMUMAN PENERIMAAN MURID BARU (SPMB) SLB PATRIOT KOTA BEKASI - TA 2026/2027 SLB Patriot Kota Bekasi membuka kesempatan bagi anak berkebutuhan khusus (ABK)', 'title' => 'SPMB (Sistem Penerimaan Murid Baru) SLB PATRIOT KOTA BEKASI', 'image' => null, 'author' => 'Ainun Mutia Zalfina', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/spmb-sistem-penerimaan-murid-baru-slb-patriot-kota-bekasi', 'created_at' => '2026-05-13 20:51:19', 'updated_at' => '2026-05-13 20:51:19', 'model_type' => 'App\\Models\\Article', 'model_id' => '04df33b0-155e-41fc-a904-731c126c75d4'],
                ['id' => 42, 'description' => 'PENGUMUMAN PENERIMAAN MURID BARU (SPMB) SLB PATRIOT KOTA BEKASI - TA 2026/2027 SLB Patriot Kota Bekasi membuka kesempatan bagi anak berkebutuhan khusus (ABK)', 'title' => 'SPMB (Sistem Penerimaan Murid Baru) SLB PATRIOT KOTA BEKASI', 'image' => null, 'author' => 'Ainun Mutia Zalfina', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/spmb-sistem-penerimaan-murid-baru-slb-patriot-kota-bekasi', 'created_at' => '2026-05-13 20:57:18', 'updated_at' => '2026-05-13 20:57:18', 'model_type' => 'App\\Models\\Article', 'model_id' => '04df33b0-155e-41fc-a904-731c126c75d4'],
                ['id' => 43, 'description' => 'PENGUMUMAN PENERIMAAN MURID BARU (SPMB) SLB PATRIOT KOTA BEKASI - TA 2026/2027 SLB Patriot Kota Bekasi membuka kesempatan bagi anak berkebutuhan khusus (ABK)', 'title' => 'SPMB (Sistem Penerimaan Murid Baru) SLB PATRIOT KOTA BEKASI', 'image' => 'images/DhUdFwOukcm2aJMYNLkSQlOflprS55zlIS2KFK93.jpg', 'author' => 'Ainun Mutia Zalfina', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/spmb-sistem-penerimaan-murid-baru-slb-patriot-kota-bekasi', 'created_at' => '2026-05-14 01:33:57', 'updated_at' => '2026-05-14 01:33:57', 'model_type' => 'App\\Models\\Article', 'model_id' => '04df33b0-155e-41fc-a904-731c126c75d4'],
                ['id' => 44, 'description' => null, 'title' => 'Hadrah', 'image' => 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'author' => 'Gesik', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', 'created_at' => '2026-06-07 23:22:19', 'updated_at' => '2026-06-07 23:22:19', 'model_type' => 'App\\Models\\Article', 'model_id' => 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'],
                ['id' => 45, 'description' => null, 'title' => 'Kegiatan ASAS (Assessment Sumatif Akhir) Genap tahun ajaran 2025/2026', 'image' => 'images/VwcmPvohyFLOgGOlLLNSZwohnu5edSVzNDQIufJp.jpg', 'author' => 'Gesik', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/kegiatan-asas-assessment-sumatif-akhir-genap-tahun-ajaran-20252026', 'created_at' => '2026-06-17 20:02:59', 'updated_at' => '2026-06-17 20:02:59', 'model_type' => 'App\\Models\\Article', 'model_id' => '44cefb8d-2f87-4670-9bbc-631746daba9c'],
                ['id' => 46, 'description' => 'Ekstrakurikuler Hadroh di SLB Patriot Kota Bekasi merupakan salah satu wadah pembinaan seni musik Islami dan spiritual bagi para peserta didik. Kegiatan ini dirancang khusus untuk memfasilitasi minat dan bakat siswa-siswi berkebutuhan khusus dalam seni tabuh rebana dan seni tarik suara (selawat).  Melalui pendekatan yang sabar, adaptif, dan penuh kasih sayang, ekstra kurikuler ini membuktikan bahwa keterbatasan fisik maupun kognitif bukanlah penghalang untuk menghasilkan harmoni nada yang indah dan menyentuh hati.', 'title' => 'Hadrah', 'image' => 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'author' => 'Gesik', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', 'created_at' => '2026-06-19 03:05:59', 'updated_at' => '2026-06-19 03:05:59', 'model_type' => 'App\\Models\\Article', 'model_id' => 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'],
                ['id' => 47, 'description' => 'Ekstrakurikuler Hadroh di SLB Patriot Kota Bekasi merupakan salah satu wadah pembinaan seni musik Islami dan spiritual bagi para peserta didik. Kegiatan ini dirancang khusus untuk memfasilitasi minat dan bakat siswa-siswi berkebutuhan khusus dalam seni tabuh rebana dan seni tarik suara (selawat).  Melalui pendekatan yang sabar, adaptif, dan penuh kasih sayang, ekstra kurikuler ini membuktikan bahwa keterbatasan fisik maupun kognitif bukanlah penghalang untuk menghasilkan harmoni nada yang indah dan menyentuh hati.', 'title' => 'Hadrah', 'image' => 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'author' => 'Gesik', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', 'created_at' => '2026-06-19 03:19:58', 'updated_at' => '2026-06-19 03:19:58', 'model_type' => 'App\\Models\\Article', 'model_id' => 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'],
                ['id' => 48, 'description' => 'Ekstrakurikuler Hadroh di SLB Patriot Kota Bekasi merupakan salah satu wadah pembinaan seni musik Islami dan spiritual bagi para peserta didik. Kegiatan ini dirancang khusus untuk memfasilitasi minat dan bakat siswa-siswi berkebutuhan khusus dalam seni tabuh rebana dan seni tarik suara (selawat).  Melalui pendekatan yang sabar, adaptif, dan penuh kasih sayang, ekstra kurikuler ini membuktikan bahwa keterbatasan fisik maupun kognitif bukanlah penghalang untuk menghasilkan harmoni nada yang indah dan menyentuh hati.', 'title' => 'Hadrah', 'image' => 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'author' => 'Gesik', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', 'created_at' => '2026-06-19 03:21:12', 'updated_at' => '2026-06-19 03:21:12', 'model_type' => 'App\\Models\\Article', 'model_id' => 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'],
                ['id' => 49, 'description' => 'Ekstrakurikuler Hadroh di SLB Patriot Kota Bekasi merupakan salah satu wadah pembinaan seni musik Islami dan spiritual bagi para peserta didik. Kegiatan ini dirancang khusus untuk memfasilitasi minat dan bakat siswa-siswi berkebutuhan khusus dalam seni tabuh rebana dan seni tarik suara (selawat).  Melalui pendekatan yang sabar, adaptif, dan penuh kasih sayang, ekstra kurikuler ini membuktikan bahwa keterbatasan fisik maupun kognitif bukanlah penghalang untuk menghasilkan harmoni nada yang indah dan menyentuh hati.', 'title' => 'Hadrah', 'image' => 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'author' => 'Gesik', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', 'created_at' => '2026-06-19 03:22:51', 'updated_at' => '2026-06-19 03:22:51', 'model_type' => 'App\\Models\\Article', 'model_id' => 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'],
                ['id' => 50, 'description' => null, 'title' => 'Hadrah', 'image' => 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'author' => 'Gesik', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', 'created_at' => '2026-06-19 03:23:52', 'updated_at' => '2026-06-19 03:23:52', 'model_type' => 'App\\Models\\Article', 'model_id' => 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'],
                ['id' => 51, 'description' => null, 'title' => 'Hadrah', 'image' => 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'author' => 'Gesik', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', 'created_at' => '2026-06-19 03:34:22', 'updated_at' => '2026-06-19 03:34:22', 'model_type' => 'App\\Models\\Article', 'model_id' => 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'],
                ['id' => 52, 'description' => null, 'title' => 'Hadrah', 'image' => 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'author' => 'Gesik', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', 'created_at' => '2026-06-19 03:36:25', 'updated_at' => '2026-06-19 03:36:25', 'model_type' => 'App\\Models\\Article', 'model_id' => 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'],
                ['id' => 53, 'description' => null, 'title' => 'Hadrah', 'image' => 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'author' => 'Gesik', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', 'created_at' => '2026-06-19 03:39:38', 'updated_at' => '2026-06-19 03:39:38', 'model_type' => 'App\\Models\\Article', 'model_id' => 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'],
                ['id' => 54, 'description' => null, 'title' => 'Hadrah', 'image' => 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'author' => 'Gesik', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', 'created_at' => '2026-06-19 03:41:59', 'updated_at' => '2026-06-19 03:41:59', 'model_type' => 'App\\Models\\Article', 'model_id' => 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'],
                ['id' => 55, 'description' => 'tes', 'title' => 'tes kurikuler', 'image' => 'images/6g8ACLsBH6dVRSH5HrBWY61iwAaRwYQlnCq9b6Jy.png', 'author' => 'Admin Sekolah', 'robots' => 'index', 'canonical_url' => 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/tes-kurikuler', 'created_at' => '2026-06-20 09:12:25', 'updated_at' => '2026-06-20 09:12:25', 'model_type' => 'App\\Models\\Article', 'model_id' => '0ce7a99d-145f-4dce-9c69-fe8be79de363'],
                ['id' => 56, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-06-20 12:47:44', 'updated_at' => '2026-06-20 12:47:44', 'model_type' => 'App\\Models\\Category', 'model_id' => 'a58f5ebc-c8ec-48da-b6f9-a80b05ca40a0'],
                ['id' => 57, 'description' => 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'title' => 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'image' => 'images/QfojvfEegY7UFF07HkRp7xw9nsI4LZNVx92HxNfJ.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/pilates-vs-yoga-apa-bedanya-dan-mana-yang-cocok-untuk-anda', 'created_at' => '2026-08-27 04:59:46', 'updated_at' => '2026-08-27 04:59:46', 'model_type' => 'App\\Models\\Article', 'model_id' => '63829ea9-8a32-4f82-94e1-7299120f822c'],
                ['id' => 58, 'description' => 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'title' => 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'image' => 'images/QfojvfEegY7UFF07HkRp7xw9nsI4LZNVx92HxNfJ.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/pilates-vs-yoga-apa-bedanya-dan-mana-yang-cocok-untuk-anda', 'created_at' => '2026-08-27 05:02:14', 'updated_at' => '2026-08-27 05:02:14', 'model_type' => 'App\\Models\\Article', 'model_id' => '63829ea9-8a32-4f82-94e1-7299120f822c'],
                ['id' => 59, 'description' => 'Pilates dan yoga sama-sama populer sebagai olahraga yang membantu meningkatkan kebugaran tubuh sekaligus memberikan manfaat bagi pikiran. Keduanya juga dapat dilakukan oleh pemula dan tidak selalu membutuhkan peralatan yang rumit.', 'title' => 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'image' => 'images/QfojvfEegY7UFF07HkRp7xw9nsI4LZNVx92HxNfJ.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/pilates-vs-yoga-apa-bedanya-dan-mana-yang-cocok-untuk-anda', 'created_at' => '2026-08-27 05:50:43', 'updated_at' => '2026-08-27 05:50:43', 'model_type' => 'App\\Models\\Article', 'model_id' => '63829ea9-8a32-4f82-94e1-7299120f822c'],
                ['id' => 60, 'description' => 'Pilates dan yoga sama-sama populer sebagai olahraga yang membantu meningkatkan kebugaran tubuh sekaligus memberikan manfaat bagi pikiran. Keduanya juga dapat dilakukan oleh pemula dan tidak selalu membutuhkan peralatan yang rumit.', 'title' => 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'image' => 'images/3MeMVIEBB3e5NU9Tm1Ijda7s7iDDcrmdWakjl1C7.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/pilates-vs-yoga-apa-bedanya-dan-mana-yang-cocok-untuk-anda', 'created_at' => '2026-08-30 18:12:39', 'updated_at' => '2026-08-30 18:12:39', 'model_type' => 'App\\Models\\Article', 'model_id' => '63829ea9-8a32-4f82-94e1-7299120f822c'],
                ['id' => 61, 'description' => 'Pilates dan yoga sama-sama populer sebagai olahraga yang membantu meningkatkan kebugaran tubuh sekaligus memberikan manfaat bagi pikiran. Keduanya juga dapat dilakukan oleh pemula dan tidak selalu membutuhkan peralatan yang rumit.', 'title' => 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'image' => 'images/Q21BSTPfei8FVhkubCnpiCxQYWcL7P6XigMY6gBq.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/articles/pilates-vs-yoga-apa-bedanya-dan-mana-yang-cocok-untuk-anda', 'created_at' => '2026-09-01 03:03:46', 'updated_at' => '2026-09-01 03:03:46', 'model_type' => 'App\\Models\\Article', 'model_id' => '63829ea9-8a32-4f82-94e1-7299120f822c'],
                ['id' => 62, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-15 23:09:17', 'updated_at' => '2026-09-15 23:09:17', 'model_type' => 'App\\Models\\DocumentCategory', 'model_id' => '26dcc275-4817-4a5a-bdba-9f14cac83c5e'],
                ['id' => 63, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-15 23:12:44', 'updated_at' => '2026-09-15 23:12:44', 'model_type' => 'App\\Models\\DocumentCategory', 'model_id' => '8a16898c-5241-43e3-9ae1-e9f266c3218d'],
                ['id' => 64, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-15 23:12:56', 'updated_at' => '2026-09-15 23:12:56', 'model_type' => 'App\\Models\\DocumentCategory', 'model_id' => 'b72c0399-3cb1-468c-97f7-0080aff97343'],
                ['id' => 65, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-15 23:13:09', 'updated_at' => '2026-09-15 23:13:09', 'model_type' => 'App\\Models\\DocumentCategory', 'model_id' => 'b698befd-73c3-490c-be44-bf7a0f4b9a35'],
                ['id' => 66, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-15 23:13:22', 'updated_at' => '2026-09-15 23:13:22', 'model_type' => 'App\\Models\\DocumentCategory', 'model_id' => 'b271e039-1a0e-4bc7-b0b0-810d3e61b402'],
                ['id' => 67, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-15 23:13:32', 'updated_at' => '2026-09-15 23:13:32', 'model_type' => 'App\\Models\\DocumentCategory', 'model_id' => '8ca91919-83d6-44d1-bf95-a10fb30ba0ff'],
                ['id' => 68, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-15 23:13:44', 'updated_at' => '2026-09-15 23:13:44', 'model_type' => 'App\\Models\\DocumentCategory', 'model_id' => 'a84fba50-51ca-4130-83d9-46cee3f242d6'],
                ['id' => 69, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-15 23:13:55', 'updated_at' => '2026-09-15 23:13:55', 'model_type' => 'App\\Models\\DocumentCategory', 'model_id' => 'b8d57474-3500-4caa-8d20-a3f1141c47e6'],
                ['id' => 70, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-15 23:14:05', 'updated_at' => '2026-09-15 23:14:05', 'model_type' => 'App\\Models\\DocumentCategory', 'model_id' => '9c0c6f74-224c-4af9-b1cf-dd9d46a8c685'],
                ['id' => 71, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-15 23:14:28', 'updated_at' => '2026-09-15 23:14:28', 'model_type' => 'App\\Models\\DocumentCategory', 'model_id' => 'd5394429-10b1-4222-86b4-68c6da8dfe0d'],
                ['id' => 72, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-15 23:14:43', 'updated_at' => '2026-09-15 23:14:43', 'model_type' => 'App\\Models\\DocumentCategory', 'model_id' => '01a9274f-003b-4ebc-b62a-b22f1091afd5'],
                ['id' => 73, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-16 01:33:21', 'updated_at' => '2026-09-16 01:33:21', 'model_type' => 'App\\Models\\Document', 'model_id' => '01a0a959-5cdd-7352-a0df-2684dbae76bc'],
                ['id' => 74, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-16 01:43:21', 'updated_at' => '2026-09-16 01:43:21', 'model_type' => 'App\\Models\\Document', 'model_id' => '01a0a962-86c4-712f-8408-6653ea52051f'],
                ['id' => 76, 'description' => 'ringkasan', 'title' => 'judul berita', 'image' => 'images/4vWMjOtHfGlFugvZ84preK7RAnVcqHFGWUIjJaeD.png', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://localhost:8000/backend/articles/judul-berita', 'created_at' => '2026-09-20 11:19:34', 'updated_at' => '2026-09-20 11:19:35', 'model_type' => 'App\\Models\\Article', 'model_id' => 'a4ce109d-88de-423a-bb26-d11087f68a29'],
                ['id' => 77, 'description' => 'ringkasan', 'title' => 'judul berita 2', 'image' => 'images/j6y3qSWN6dYvXjXiE41yWMTz0Z53cyxzaJD6YQWm.png', 'author' => 'Admin DBMSDA', 'robots' => 'index', 'canonical_url' => 'http://localhost:8000/backend/articles/judul-berita-2', 'created_at' => '2026-09-20 12:09:25', 'updated_at' => '2026-09-20 12:10:17', 'model_type' => 'App\\Models\\Article', 'model_id' => '2fa27e87-b4b5-4224-900d-26e44764d1e4'],
                ['id' => 78, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-20 13:02:23', 'updated_at' => '2026-09-20 13:02:23', 'model_type' => 'App\\Models\\DocumentCategory', 'model_id' => 'a6d14f40-5032-40dc-9ef2-349d4300636f'],
                ['id' => 79, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-09-20 13:03:40', 'updated_at' => '2026-09-20 13:03:40', 'model_type' => 'App\\Models\\Document', 'model_id' => '01a0beea-4a72-723e-a71b-5f20668c45b1'],
                ['id' => 80, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-10-07 14:03:26', 'updated_at' => '2026-10-07 14:03:26', 'model_type' => 'App\\Models\\Category', 'model_id' => '773cd47c-dc89-4546-b6f0-bfb26f74a0a2'],
                ['id' => 81, 'description' => 'dasda', 'title' => 'dasd', 'image' => 'images/bG1cr04Br6NCTiuxlyPaoCdH5LTcCkOzsqev3RYP.png', 'author' => 'Diskominfostandi', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/agendas/dasd', 'created_at' => '2026-10-07 14:17:16', 'updated_at' => '2026-10-07 14:19:58', 'model_type' => 'App\\Models\\Agenda', 'model_id' => '89355860-0de5-4a90-9567-f8c68d5fe4de'],
                ['id' => 82, 'description' => null, 'title' => null, 'image' => null, 'author' => null, 'robots' => null, 'canonical_url' => null, 'created_at' => '2026-10-07 19:55:10', 'updated_at' => '2026-10-07 19:55:10', 'model_type' => 'App\\Models\\Document', 'model_id' => '01a1196f-a6be-727e-bd0b-0693711985c6'],
                ['id' => 83, 'description' => 'Musrembang Kota Bekasi', 'title' => 'Musrembang Kota Bekasi', 'image' => 'images/EzgKQVJf6PLJzak8pTn6uQpjOpWRVGTCtIRr6vBv.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/agendas/musrembang-kota-bekasi', 'created_at' => '2026-10-12 02:36:01', 'updated_at' => '2026-10-12 12:01:15', 'model_type' => 'App\\Models\\Agenda', 'model_id' => '00c060d8-c387-435d-91d9-88d7e6d4a48c'],
                ['id' => 84, 'description' => 'Pemerintah Kota Bekasi melalui Dinas Bina Marga dan Sumber Daya Air (DBMSDA) Kota Bekasi terus melakukan upaya pengendalian Daerah Aliran Sungai (DAS) Rawa Tembaga mulai dari...', 'title' => 'Pemerintah Kota Bekasi melalui Dinas Bina Marga dan Sumber Daya Air', 'image' => 'images/9K1D1onCiMxH8kdWBZ462VpFEApR9KkDIsj9PEUC.jpg', 'author' => 'super-admin', 'robots' => 'index', 'canonical_url' => 'http://127.0.0.1:8000/backend/agendas/pemerintah-kota-bekasi-melalui-dinas-bina-marga-dan-sumber-daya-air', 'created_at' => '2026-10-12 12:22:48', 'updated_at' => '2026-10-12 12:24:01', 'model_type' => 'App\\Models\\Agenda', 'model_id' => 'fb3a98ff-7ded-4e38-a8f2-c1c21d13a3fb'],
            ]);
            $this->sync('website_identities', [
                ['uuid' => '4b30c85e-4c00-4b53-897f-841bce4e9bde', 'site_name' => 'Agenda', 'site_title' => 'Pemerintah Kota Bekasi', 'tagline' => 'Temukan Agenda, Ikuti Kegiatan Kota Bekasi', 'description' => 'Portal resmi Agenda Pemerintah Kota Bekasi', 'logo' => 'website-identity/8n6R6qclBk0pgAa2QSR0ayYui7fd7x4sPGvE6mD8.png', 'favicon' => 'website-identity/j7n3ocgiv6jPJq0V52HDKem5Np6wyY2RQgFHAjFl.png', 'email' => 'info@bekasikota.go.id', 'phone' => '6285691231100', 'address' => 'Jl. Jendral Ahmad Yani No. 1 Gd.Perkantoran', 'facebook_url' => 'https://www.facebook.com/pemkotbekasi/', 'instagram_url' => 'https://www.instagram.com/pemkot_bekasi', 'youtube_url' => 'https://www.youtube.com/@pemerintahkotabekasi', 'tiktok_url' => null, 'meta_title' => 'Agenda Pemerintah Kota Bekasi', 'meta_description' => 'Portal resmi Agenda Pemerintah Kota Bekasi yang menyediakan informasi agenda, jadwal, kegiatan, dan aktivitas Pemerintah Kota Bekasi secara mudah dan terintegrasi.', 'meta_keywords' => 'Agenda Pemerintah Kota Bekasi, agenda Pemkot Bekasi, jadwal kegiatan Bekasi, kegiatan Pemerintah Kota Bekasi, agenda pemerintahan Bekasi, kalender kegiatan Bekasi, agenda Wali Kota Bekasi', 'og_image' => 'website-identity/tlTDSRWyjwaUNn2Rnf6ld83VjOaZRcnDBmjK89bD.webp', 'google_analytics_id' => null, 'google_site_verification' => null, 'created_at' => '2026-09-18 02:41:39', 'updated_at' => '2026-10-12 11:58:43', 'hero_badge' => '7 - 20 November 2026', 'hero_title' => 'Bekasi Dalam', 'hero_title_highlight' => 'Gelaran Juara', 'hero_lead' => 'Temukan jadwal kegiatan, agenda pertandingan, lokasi penyelenggaraan, dan informasi terkini seputar PORPROV XV Jawa Barat 2026 di Kota Bekasi.', 'hero_cta_label' => 'Lihat Jadwal & Venue', 'hero_cta_url' => 'http://porprovbekasikota2026.id/jadwal', 'hero_countdown_label' => 'Hitung Mundur • Opening Ceremony 7 November 2026', 'hero_countdown_target' => '2026-11-07 19:00:00', 'hero_background' => 'website-identity/WU446ZXg93Fx3gYryWb6y40sXpj4PsUswrBBKWiF.jpg'],
            ]);
            $this->sync('website_menus', [
                ['id' => 1, 'name' => 'Header Utama', 'slug' => 'header-main', 'location' => 'header', 'status' => 1, 'position' => 1, 'description' => 'Menu navigasi utama di header', 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 2, 'name' => 'Footer Utama', 'slug' => 'footer-main', 'location' => 'footer', 'status' => 1, 'position' => 1, 'description' => 'Menu navigasi di footer', 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 3, 'name' => 'Mobile Menu', 'slug' => 'mobile-main', 'location' => 'mobile', 'status' => 1, 'position' => 1, 'description' => 'Menu untuk tampilan mobile', 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
            ]);
            $this->sync('website_menu_items', [
                ['id' => 1, 'website_menu_id' => 1, 'parent_id' => null, 'name' => 'Beranda', 'url' => null, 'route' => 'home', 'route_params' => null, 'icon' => 'bx bx-home', 'target_blank' => 0, 'status' => 1, 'position' => 1, 'description' => null, 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 2, 'website_menu_id' => 1, 'parent_id' => null, 'name' => 'Tentang Kami', 'url' => null, 'route' => 'about', 'route_params' => null, 'icon' => 'bx bx-info-circle', 'target_blank' => 0, 'status' => 1, 'position' => 2, 'description' => null, 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 3, 'website_menu_id' => 1, 'parent_id' => null, 'name' => 'Layanan', 'url' => '#', 'route' => null, 'route_params' => null, 'icon' => 'bx bx-cog', 'target_blank' => 0, 'status' => 1, 'position' => 3, 'description' => null, 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 4, 'website_menu_id' => 1, 'parent_id' => 3, 'name' => 'Layanan 1', 'url' => null, 'route' => 'services.detail', 'route_params' => '{"slug":"layanan-1"}', 'icon' => null, 'target_blank' => 0, 'status' => 1, 'position' => 1, 'description' => null, 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 5, 'website_menu_id' => 1, 'parent_id' => 3, 'name' => 'Layanan 2', 'url' => null, 'route' => 'services.detail', 'route_params' => '{"slug":"layanan-2"}', 'icon' => null, 'target_blank' => 0, 'status' => 1, 'position' => 2, 'description' => null, 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 6, 'website_menu_id' => 1, 'parent_id' => null, 'name' => 'Kontak', 'url' => null, 'route' => 'contact', 'route_params' => null, 'icon' => 'bx bx-envelope', 'target_blank' => 0, 'status' => 1, 'position' => 4, 'description' => null, 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 7, 'website_menu_id' => 2, 'parent_id' => null, 'name' => 'Kebijakan Privasi', 'url' => null, 'route' => 'privacy', 'route_params' => null, 'icon' => null, 'target_blank' => 0, 'status' => 1, 'position' => 1, 'description' => null, 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 8, 'website_menu_id' => 2, 'parent_id' => null, 'name' => 'Syarat & Ketentuan', 'url' => null, 'route' => 'terms', 'route_params' => null, 'icon' => null, 'target_blank' => 0, 'status' => 1, 'position' => 2, 'description' => null, 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 9, 'website_menu_id' => 2, 'parent_id' => null, 'name' => 'FAQ', 'url' => null, 'route' => 'faq', 'route_params' => null, 'icon' => null, 'target_blank' => 0, 'status' => 1, 'position' => 3, 'description' => null, 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 10, 'website_menu_id' => 3, 'parent_id' => null, 'name' => 'Beranda', 'url' => null, 'route' => 'home', 'route_params' => null, 'icon' => 'bx bx-home', 'target_blank' => 0, 'status' => 1, 'position' => 1, 'description' => null, 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 11, 'website_menu_id' => 3, 'parent_id' => null, 'name' => 'Profil', 'url' => null, 'route' => 'profile.edit', 'route_params' => null, 'icon' => 'bx bx-user', 'target_blank' => 0, 'status' => 1, 'position' => 2, 'description' => null, 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
                ['id' => 12, 'website_menu_id' => 3, 'parent_id' => null, 'name' => 'Logout', 'url' => null, 'route' => 'logout', 'route_params' => null, 'icon' => 'bx bx-log-out', 'target_blank' => 0, 'status' => 1, 'position' => 3, 'description' => null, 'created_at' => '2026-09-17 13:48:02', 'updated_at' => '2026-09-17 13:48:02'],
            ]);
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    /** Hapus isi tabel lalu insert ulang; kolom yang tidak ada di
     *  skema (dump lebih tua/baru) dilewati agar tidak error. */
    private function sync(string $table, array $rows): void
    {
        if ($rows === []) {
            return;
        }
        $columns = Schema::getColumnListing($table);
        DB::table($table)->delete();
        foreach (array_chunk($rows, 250) as $chunk) {
            DB::table($table)->insert(array_map(
                fn (array $row) => array_intersect_key($row, array_flip($columns)),
                $chunk,
            ));
        }
    }
}
