<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Cache respons API publik per grup.
 *
 * Setiap grup punya "versi" yang bertambah saat data terkait berubah, sehingga
 * cache tidak perlu menghapus satu per satu key saat admin menyimpan data.
 */
final class PublicApiCache
{
    public const TTL_SHORT = 30;
    public const TTL_DEFAULT = 60;
    public const TTL_LONG = 300;

    public static function remember(
        string $group,
        string $key,
        Closure $callback,
        int $ttl = self::TTL_DEFAULT
    ): mixed {
        $version = (int) Cache::get(self::versionKey($group), 1);
        $host = app()->runningInConsole() ? 'cli' : request()->getSchemeAndHttpHost();
        $cacheKey = sprintf(
            'public_api:%s:v%d:%s:%s',
            $group,
            $version,
            sha1($host),
            $key
        );

        return Cache::remember($cacheKey, $ttl, $callback);
    }

    public static function invalidate(string ...$groups): void
    {
        foreach (array_unique($groups) as $group) {
            $key = self::versionKey($group);
            $version = (int) Cache::get($key, 1);

            Cache::forever($key, $version + 1);
        }
    }

    private static function versionKey(string $group): string
    {
        return 'public_api:version:' . $group;
    }
}
