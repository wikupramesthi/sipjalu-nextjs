<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'route.permission' => \App\Http\Middleware\RouteMiddleware::class,
            'visitor' => \App\Http\Middleware\TrackVisitor::class,
            'force-file-session' => \App\Http\Middleware\ForceFileSession::class,
            'inactive' => \App\Http\Middleware\AutoLogoutInactive::class,
            'opd.token' => \App\Http\Middleware\CheckOpdTokenAbility::class,
            'public.key' => \App\Http\Middleware\PublicApiKey::class,
        ]);
        $middleware->append(\App\Http\Middleware\TrustProxies::class);
        $middleware->append(\App\Http\Middleware\ForceRootUrl::class);
        $middleware->appendToGroup('web', ['route.permission', 'visitor', \App\Http\Middleware\SetLocale::class, \App\Http\Middleware\AutoLogoutInactive::class, \App\Http\Middleware\SecurityHeaders::class]);
        $middleware->appendToGroup('api', [
            \App\Http\Middleware\RestrictApiToProxy::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
        
        // Custom middleware group for captcha.
        // JANGAN paksa file session di sini: captcha dan form login HARUS
        // berbagi session driver yang sama (database, sesuai SESSION_DRIVER),
        // kalau tidak validasi captcha selalu gagal.
        $middleware->group('captcha', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ]);
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(
            fn (\Illuminate\Http\Request $request, \Throwable $exception): bool => $request->is('api/*') || $request->expectsJson()
        );

        // Jaring pengaman: jangan pernah tampilkan halaman 429 untuk login.
        // Kalau ada throttle middleware (route/global) yang tembus, kembalikan
        // ke halaman login dengan alert "silakan login kembali dalam waktu ...".
        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $seconds = method_exists($e, 'getHeaders') ? (int) ($e->getHeaders()['Retry-After'] ?? 60) : 60;

                return response()->json([
                    'status' => 'error',
                    'message' => 'Terlalu banyak permintaan. Coba lagi dalam ' . max(1, $seconds) . ' detik.',
                    'data' => null,
                ], 429);
            }

            if ($request->is('auth/login', 'login', 'auth/*') || $request->routeIs('login')) {
                $seconds = method_exists($e, 'getHeaders') ? (int) ($e->getHeaders()['Retry-After'] ?? 60) : 60;
                $seconds = max(1, $seconds);
                $waitText = $seconds >= 60
                    ? ((int) ceil($seconds / 60)) . ' menit'
                    : $seconds . ' detik';

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => "Terlalu banyak percobaan login. Silakan login kembali dalam waktu {$waitText}.",
                    ], 429);
                }

                return redirect()
                    ->route('login')
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => "Terlalu banyak percobaan login. Silakan login kembali dalam waktu {$waitText}."]);
            }

            return null;
        });

        // Format error JSON konsisten untuk seluruh API (api/*).
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data yang dikirim tidak valid.',
                    'errors' => $e->errors(),
                ], 422);
            }

            return null;
        });

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak terautentikasi. Sertakan token Bearer yang valid.',
                    'data' => null,
                ], 401);
            }

            return null;
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Endpoint tidak ditemukan.',
                    'data' => null,
                ], 404);
            }

            return null;
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*') && $e->getStatusCode() === 403) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses.',
                    'data' => null,
                ], 403);
            }

            return null;
        });
    })->create();
