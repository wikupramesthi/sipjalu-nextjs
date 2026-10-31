<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ManagementAccess\Route;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class RouteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next) : Response
    // {
    //     $routes = Route::firstWhere('route', $request->route()?->getName());

    //     return blank($routes) || (bool) $routes->status && $request->user()->can($routes->permission_name)
    //         ? $next($request)
    //         : redirect(RouteServiceProvider::HOME)->withErrors('you do not have access to this route!');
    // }

    public function handle(Request $request, Closure $next): Response
    {
        // Tamu (belum login) diteruskan ke middleware auth agar
        // diarahkan ke halaman login, bukan 500 null->can().
        if (! $request->user()) {
            return $next($request);
        }

        $routes = Route::firstWhere(
            'route',
            $request->route()?->getName()
        );

        // Default-deny untuk area admin: route backend/* yang belum terdaftar
        // di tabel routes TIDAK boleh fail-open ke semua user login.
        // Pengecualian sempit: instalasi fresh (tabel routes kosong) atau
        // peran super-admin/admin agar operasional + test tidak terkunci.
        if (blank($routes)) {
            if (! $request->is('backend/*')) {
                return $next($request);
            }
            try {
                if (Route::count() === 0) {
                    return $next($request);
                }
            } catch (\Throwable) {
                return abort(404);
            }

            // Fallback untuk route agenda yang mungkin belum terdaftar di tabel
            // routes, namun user memiliki permission yang sesuai.
            $routeName = $request->route()?->getName();
            $agendaPermissionMap = [
                'agendas.index' => 'agendas.index',
                'agendas.create' => 'agendas.create',
                'agendas.store' => 'agendas.store',
                'agendas.show' => 'agendas.index',
                'agendas.edit' => 'agendas.update',
                'agendas.update' => 'agendas.update',
                'agendas.destroy' => 'agendas.destroy',
                'agendas.restore' => 'agendas.destroy',
                'agendas.forceDestroy' => 'agendas.destroy',
                'agendas.duplicate' => 'agendas.create',
                'agendas.approve' => 'agendas.approve',
                'agendas.reject' => 'agendas.approve',
                'agendas.mintaRevisi' => 'agendas.approve',
                'agendas.attachments.destroy' => 'agendas.update',
                'agendas.attachments.download' => 'agendas.index',
                'agendas.calendarData' => 'agendas.index',
                'agendas.pendingCount' => 'agendas.index',
                'agendas.ical' => 'agendas.index',
                'agendas.export' => 'agendas.index',
                'agendas.import' => 'agendas.create',
                'agendas.importPreview' => 'agendas.create',
                'agendas.importEdit' => 'agendas.create',
                'agendas.importConfirm' => 'agendas.create',
                'agendas.importTemplate' => 'agendas.create',
                'agendas.laporan' => 'agendas.index',
                'agendas.laporanExcel' => 'agendas.index',
                'agendas.laporanPptx' => 'agendas.index',
                'agendas.logAudit' => 'agendas.index',
                'agendas.bulkDestroy' => 'agendas.destroy',
                'agendas.bulkRestore' => 'agendas.destroy',
                'agendas.bulkApprove' => 'agendas.approve',
                'agendas.bulkStatus' => 'agendas.update',
            ];
            if (is_string($routeName) && isset($agendaPermissionMap[$routeName])
                && $request->user()->can($agendaPermissionMap[$routeName])) {
                return $next($request);
            }

            return $request->user()->hasAnyRole(['super-admin', 'admin'])
                ? $next($request)
                : abort(404);
        }

        return (bool) $routes->status
            && $request->user()->can($routes->permission_name)
            ? $next($request)
            : abort(404);
    }
}
