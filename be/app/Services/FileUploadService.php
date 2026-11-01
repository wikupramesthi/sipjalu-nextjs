<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Satu pintu untuk ganti/hapus berkas publik.
 *
 * Mengapa: pola storage_path()+unlink() tersebar di 7 controller rawan
 * path-traversal bila nilai DB kelak bisa dikontrol user. Selalu lewat
 * Storage::disk('public') + whitelist direktori.
 */
final class FileUploadService
{
    /** Direktori yang boleh ditulis/hapus via service ini. */
    private const ALLOWED_DIRS = [
        'avatars', 'agendas', 'documents', 'pages', 'identities', 'aduan',
    ];

    public static function isManaged(?string $path): bool
    {
        if (! is_string($path) || $path === '' || str_starts_with($path, 'http')) {
            return false;
        }
        // Tolak traversal & absolute path.
        if (str_contains($path, '..') || str_starts_with($path, '/') || str_contains($path, '\\')) {
            return false;
        }
        $dir = explode('/', $path)[0];
        if (! in_array($dir, self::ALLOWED_DIRS, true)) {
            return false;
        }

        return Storage::disk('public')->exists($path);
    }

    public static function delete(?string $path): bool
    {
        if (! self::isManaged($path)) {
            return false;
        }

        return (bool) Storage::disk('public')->delete($path);
    }

    /** Ganti berkas lama dengan upload baru; kembalikan path baru. */
    public static function replace(?string $oldPath, UploadedFile $file, string $dir): string
    {
        if (! in_array($dir, self::ALLOWED_DIRS, true)) {
            throw new \InvalidArgumentException("Direktori upload tidak diizinkan: {$dir}");
        }
        self::delete($oldPath);

        return $file->store($dir, 'public');
    }
}
