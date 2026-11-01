<?php

namespace App\Providers;

use App\Models\Agenda;
use App\Models\AgendaImage;
use App\Models\Berita;
use App\Models\BeritaImage;
use App\Models\Category;
use App\Models\Service;
use App\Models\WebsiteIdentity;
use App\Models\WebsiteMenu;
use App\Models\WebsiteMenuItem;
use App\Observers\AuditObserver;
use App\Support\PublicApiCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Root URL (host/scheme per request + prefix dari APP_URL) diatur
        // ForceRootUrl middleware. Paginator tetap perlu resolver khusus:
        // path request mentah sudah kehilangan prefix /be/ dari reverse proxy,
        // sehingga ?page=2 harus dibangun lewat URL generator yang sama.
        if (config('app.url')) {
            Paginator::currentPathResolver(function () {
                try {
                    $path = request()->path();
                    $path = $path === '/' ? '' : ltrim($path, '/');
                    return URL::to($path);
                } catch (\Throwable $e) {
                    return URL::to('/');
                }
            });
        }

        // Listener auth (RecordLoginActivity untuk event Login,
        // RecordFailedLogin untuk event Failed) didaftarkan OTOMATIS oleh
        // Laravel event discovery (lihat app/Listeners). Jangan daftarkan
        // manual via Event::listen di sini agar tidak tercatat 2x lipat.

        Event::listen('eloquent.*', function (string $eventName, array $data) {
            $model = $data[0] ?? null;

            if (! $model instanceof Model) {
                return;
            }

            $action = Str::between($eventName, 'eloquent.', ':');

            if (! in_array($action, ['created', 'updated', 'deleted', 'restored'], true)) {
                return;
            }

            app(AuditObserver::class)->handle($action, $model);

            // Increment `views` saat detail dibuka tidak mengubah isi daftar,
            // jadi jangan buang cache API publik hanya karena statistik views.
            $changes = array_keys($model->getChanges());
            if ($action === 'updated' && $changes !== [] && array_diff($changes, ['views']) === []) {
                return;
            }

            $groups = match (true) {
                $model instanceof Agenda, $model instanceof AgendaImage => ['agenda', 'categories'],
                $model instanceof Berita, $model instanceof BeritaImage => ['berita', 'categories'],
                $model instanceof Service => ['services'],
                $model instanceof WebsiteIdentity => ['identity'],
                $model instanceof WebsiteMenu, $model instanceof WebsiteMenuItem => ['menus'],
                $model instanceof Category => ['agenda', 'berita', 'menus', 'categories'],
                default => [],
            };

            if ($groups !== []) {
                PublicApiCache::invalidate(...$groups);
            }
        });

        // Share website identity globally for frontend & backend
        View::composer('*', function ($view) {
            try {
                $identity = \App\Models\WebsiteIdentity::first();
                $view->with('websiteIdentity', $identity);
            } catch (\Throwable $e) {
                $view->with('websiteIdentity', null);
            }
        });
        
    }
}
