<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use Mews\Captcha\CaptchaController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\ManagementAccess\RoleController;
use App\Http\Controllers\ManagementAccess\UserController;
use App\Http\Controllers\ManagementAccess\RouteController;
use App\Http\Controllers\ManagementAccess\MenuItemController;
use App\Http\Controllers\ManagementAccess\MenuGroupController;
use App\Http\Controllers\ManagementAccess\PermissionController;

use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\DocumentCategoryController;
use App\Http\Controllers\Admin\DocumentSubcategoryController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\AduanController;
use App\Http\Controllers\Admin\AduanTindakLanjutController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\Security\LoginActivityController;
use App\Http\Controllers\Admin\Security\AuditLogController;
use App\Http\Controllers\Admin\Security\FailedLoginController;
use App\Http\Controllers\Admin\Security\LoginLockoutController;
use App\Http\Controllers\Admin\WebsiteMenuController;
use App\Http\Controllers\Admin\WebsiteMenuItemController;
use App\Http\Controllers\Admin\WebsiteIdentityController;

use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WallboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\MinifyHtml;

Route::redirect('/', '/auth/login')->name('home');

// Sitemap dinamis SEO rapih (discoverable via /sitemap.xml & robots.txt)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', function () {
    $sitemap = route('sitemap');
    $content = "User-agent: *\nAllow: /\nSitemap: {$sitemap}\n";
    return response($content, 200)->header('Content-Type', 'text/plain');
})->name('robots');

// Health check sistem (publik, ringkas + throttle + cache 30 dtk anti-abuse)
Route::get('/health', [HealthController::class, 'index'])->middleware('throttle:10,1')->name('health');
// Health page visual - khusus super-admin/admin (jangan publik, bocorkan heartbeat)
Route::get('/health/page', [HealthController::class, 'page'])->middleware(['auth', 'role:super-admin|admin|opd'])->name('health.page');
Route::get('/health/details', [HealthController::class, 'details'])
    ->middleware(['auth', 'role:super-admin|admin|opd', 'throttle:10,1'])
    ->name('health.details');

// Snapshot SEO server-side untuk crawler share (WhatsApp/FB/X/Telegram).
// nginx hanya mem-proxy User-Agent bot pada /agenda/{slug} ke sini;
// manusia tetap ke SPA. Tanpa auth & tanpa session berat.
Route::get('/seo/agenda/{slug}', [\App\Http\Controllers\SeoSnapshotController::class, 'show'])
    ->where('slug', '[^/]+')
    ->name('seo.agenda');

// Wallboard TV pusat pantau (login atau ?token=WALLBOARD_TOKEN, throttle anti-scrape)
Route::get('/wallboard', [WallboardController::class, 'index'])->middleware('throttle:30,1')->name('wallboard');

// Language switch (khusus login — simpan di session & redirect back)
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'], true)) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Socialite Routes GOOGLE (guest + throttle anti-spam/abuse)
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->middleware(['guest', 'throttle:20,1'])->name('googleAuth');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->middleware(['guest', 'throttle:20,1']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/notifications', function (Illuminate\Http\Request $request) {
        $query = auth()->user()->notifications()->latest();

        if ($request->get('filter') === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate(15)->withQueryString();
        $unreadCount = auth()->user()->unreadNotifications()->count();

        return view('pages.notifications.index', compact('notifications', 'unreadCount'));
    })->name('notifications.index');
    // Keep-alive untuk reset timer idle (dipanggil via JS saat user klik "Tetap Login")
    Route::post('/keep-alive', function (\Illuminate\Http\Request $request) {
        $request->session()->put('last_activity', time());
        return response()->json(['ok' => true]);
    })->name('keep-alive');
    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read');
    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.readAll');
});




Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'backend'], function () {
    $superAdmin = 'role:super-admin';
    // /backend bukan route yang terdaftar (hanya /backend/dashboard), jadi
    // membuka /backend menghasilkan 404. Arahkan ke dashboard; middleware
    // auth akan mengirim tamu ke halaman login lebih dulu.
    Route::get('/', fn () => redirect()->route('dashboard.index'))->name('backend.home');
    // $user = 'role:user';
    Route::post('/dashboard/sumber-informasi', [DashboardController::class, 'submitSumber'])->name('dashboard.submitSumber');
    Route::get('/dashboard/device-stats', [DashboardController::class, 'deviceStats'])->name('dashboard.device-stats');
    Route::get('/dashboard/active-visitors', [DashboardController::class, 'activeVisitors'])->name('dashboard.active-visitors');
    Route::get('/dashboard/beritas/pending-count', [DashboardController::class, 'pendingBeritaCount'])->name('dashboard.beritas.pendingCount');
    Route::get('/dashboard/analytics', [\App\Http\Controllers\Dashboard\AnalyticsReportController::class, 'preview'])->middleware('role:super-admin|admin|opd')->name('dashboard.analytics.preview');
    Route::get('/dashboard/analytics/pdf', [\App\Http\Controllers\Dashboard\AnalyticsReportController::class, 'download'])->middleware('role:super-admin|admin|opd')->name('dashboard.analytics.pdf');
    Route::get('/search', [SearchController::class, 'index'])->name('global.search');
    Route::resource('dashboard', DashboardController::class)->only('index');
    Route::resource('user', UserController::class)->middleware($superAdmin)->only('index', 'store', 'update', 'destroy');
    Route::resource('route', RouteController::class)->middleware($superAdmin)->only('index', 'store', 'update', 'destroy');
    Route::resource('permission', PermissionController::class)->middleware($superAdmin)->only('index', 'store', 'update', 'destroy');
    Route::resource('role', RoleController::class)->middleware([$superAdmin])->only('index', 'store', 'update', 'destroy');
    Route::resource('menu', MenuGroupController::class)->middleware($superAdmin)->only('index', 'store', 'update', 'destroy');
    Route::resource('menu.item', MenuItemController::class)->middleware($superAdmin)->only('index', 'store', 'update', 'destroy');
    Route::delete('faq/bulk', [FaqController::class, 'bulkDestroy'])->name('faq.bulkDestroy');
    Route::resource('faq', FaqController::class);
    Route::resource('categories', CategoryController::class);
    Route::delete('agendas/bulk', [AgendaController::class, 'bulkDestroy'])->name('agendas.bulkDestroy');
    Route::put('agendas/bulk/restore', [AgendaController::class, 'bulkRestore'])->name('agendas.bulkRestore');
    Route::post('agendas/bulk-approve', [AgendaController::class, 'bulkApprove'])->name('agendas.bulkApprove');
    Route::post('agendas/bulk-status', [AgendaController::class, 'bulkStatus'])->name('agendas.bulkStatus');
    Route::get('agendas/calendar-data', [AgendaController::class, 'calendarData'])->name('agendas.calendarData');
    Route::get('agendas/kalender.ics', [AgendaController::class, 'ical'])->name('agendas.ical');
    Route::get('agendas/pending-count', [AgendaController::class, 'pendingCount'])->name('agendas.pendingCount');
    Route::get('agendas/log-audit', [AgendaController::class, 'logAudit'])->name('agendas.logAudit');
    Route::get('agendas/export', [AgendaController::class, 'export'])->name('agendas.export')->middleware('throttle:20,1');
    Route::get('agendas/import', [AgendaController::class, 'importForm'])->name('agendas.import');
    Route::post('agendas/import', [AgendaController::class, 'importPreview'])->name('agendas.importPreview')->middleware('throttle:20,1');
    Route::post('agendas/import-edit', [AgendaController::class, 'importEdit'])->name('agendas.importEdit')->middleware('throttle:20,1');
    Route::post('agendas/import-confirm', [AgendaController::class, 'importConfirm'])->name('agendas.importConfirm')->middleware('throttle:20,1');
    Route::get('agendas/import-template', [AgendaController::class, 'importTemplate'])->name('agendas.importTemplate')->middleware('throttle:30,1');
    Route::get('agendas/laporan', [AgendaController::class, 'laporan'])->name('agendas.laporan')->middleware('throttle:30,1');
    Route::get('agendas/laporan/excel', [AgendaController::class, 'laporanExcel'])->name('agendas.laporanExcel')->middleware('throttle:20,1');
    Route::get('agendas/laporan/pptx', [AgendaController::class, 'laporanPptx'])->name('agendas.laporanPptx')->middleware('throttle:20,1');
    Route::post('agendas/{agenda}/approve', [AgendaController::class, 'approve'])->name('agendas.approve')->middleware('throttle:10,1');
    Route::post('agendas/{agenda}/reject', [AgendaController::class, 'reject'])->name('agendas.reject')->middleware('throttle:10,1');
    Route::post('agendas/{agenda}/minta-revisi', [AgendaController::class, 'mintaRevisi'])->name('agendas.mintaRevisi')->middleware('throttle:10,1');
    Route::post('agendas/{agenda}/duplicate', [AgendaController::class, 'duplicate'])->name('agendas.duplicate');
    Route::delete('agendas/{agenda}/attachments/{attachment}', [AgendaController::class, 'destroyAttachment'])->name('agendas.attachments.destroy');
    Route::get('agendas/{agenda}/attachments/{attachment}/download', [AgendaController::class, 'downloadAttachment'])->name('agendas.attachments.download');
    Route::resource('agendas', AgendaController::class);
    Route::delete('beritas/bulk', [BeritaController::class, 'bulkDestroy'])->name('beritas.bulkDestroy');
    Route::put('beritas/bulk/restore', [BeritaController::class, 'bulkRestore'])->name('beritas.bulkRestore');
    Route::get('beritas/export', [BeritaController::class, 'export'])->name('beritas.export')->middleware('throttle:20,1');
    Route::get('beritas/import', [BeritaController::class, 'importForm'])->name('beritas.import');
    Route::post('beritas/import', [BeritaController::class, 'importPreview'])->name('beritas.importPreview')->middleware('throttle:20,1');
    Route::post('beritas/import-confirm', [BeritaController::class, 'importConfirm'])->name('beritas.importConfirm')->middleware('throttle:20,1');
    Route::get('beritas/import-template', [BeritaController::class, 'importTemplate'])->name('beritas.importTemplate')->middleware('throttle:30,1');
    Route::get('beritas/log-audit', [BeritaController::class, 'logAudit'])->name('beritas.logAudit');
    Route::get('beritas/laporan/pdf', [BeritaController::class, 'laporanPdf'])->name('beritas.laporan')->middleware('throttle:30,1');
    Route::get('beritas/laporan/excel', [BeritaController::class, 'laporanExcel'])->name('beritas.laporanExcel')->middleware('throttle:20,1');
    Route::get('beritas/laporan/pptx', [BeritaController::class, 'laporanPptx'])->name('beritas.laporanPptx')->middleware('throttle:20,1');
    Route::post('beritas/{berita}/approve', [BeritaController::class, 'approve'])->name('beritas.approve')->middleware('throttle:10,1');
    Route::post('beritas/{berita}/reject', [BeritaController::class, 'reject'])->name('beritas.reject')->middleware('throttle:10,1');
    Route::post('beritas/{berita}/minta-revisi', [BeritaController::class, 'mintaRevisi'])->name('beritas.mintaRevisi')->middleware('throttle:10,1');
    Route::put('beritas/{uuid}/restore', [BeritaController::class, 'restore'])->name('beritas.restore');
    Route::delete('beritas/{uuid}/force', [BeritaController::class, 'forceDestroy'])->name('beritas.forceDestroy');
    Route::resource('beritas', BeritaController::class);

    // Restore agenda from archive
    Route::put('agendas/{agenda}/restore', [AgendaController::class, 'restore'])->name('agendas.restore');
    Route::delete('agendas/{uuid}/force', [AgendaController::class, 'forceDestroy'])->name('agendas.forceDestroy');
    Route::resource('account', AccountController::class);
    Route::get('/get-kelurahan/{kecamatan_id}', [AccountController::class, 'getKelurahan']);
    Route::resource('pages', PagesController::class);
    Route::delete('services/bulk', [ServiceController::class, 'bulkDestroy'])->name('services.bulkDestroy');
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::get('aduans/kelurahan/{kecamatan_id}', [AduanController::class, 'getKelurahan'])->name('aduans.kelurahan');
    Route::get('aduans/cek-duplikat', [AduanController::class, 'cekDuplikat'])->name('aduans.cekDuplikat');
    Route::get('aduans/export-pdf', [AduanController::class, 'exportPdf'])->name('aduans.exportPdf');
    Route::post('aduans/{aduan}/restore', [AduanController::class, 'restore'])->name('aduans.restore');
    Route::delete('aduans/{aduan}/force', [AduanController::class, 'forceDestroy'])->name('aduans.forceDestroy');
    Route::post('aduans/{aduan}/rating', [AduanController::class, 'rate'])->name('aduans.rate');
    Route::post('aduans/{aduan}/arsip', [AduanController::class, 'arsip'])->name('aduans.arsip');
    Route::post('aduans/{aduan}/batal-arsip', [AduanController::class, 'batalArsip'])->name('aduans.batalArsip');
    Route::post('aduans/{aduan}/tindak-lanjut', [AduanTindakLanjutController::class, 'store'])->name('aduans.tindak-lanjut.store');
    Route::delete('aduans/tindak-lanjut/{tindakLanjut}', [AduanTindakLanjutController::class, 'destroy'])->name('aduans.tindak-lanjut.destroy');
    Route::get('gis', [AduanController::class, 'gis'])->name('gis.index');
    Route::get('gis/export', [AduanController::class, 'gisExport'])->name('gis.export');
    Route::post('aduans/bulk-destroy', [AduanController::class, 'bulkDestroy'])->name('aduans.bulkDestroy');
    Route::post('aduans/bulk-restore', [AduanController::class, 'bulkRestore'])->name('aduans.bulkRestore');
    Route::resource('aduans', AduanController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::delete('documents/bulk', [DocumentController::class, 'bulkDestroy'])->name('documents.bulkDestroy');
    Route::get('documents/get-subcategories', [DocumentController::class, 'getSubcategories'])->name('documents.getSubcategories');
    Route::resource('documents', DocumentController::class);
    Route::delete('document-categories/bulk', [DocumentCategoryController::class, 'bulkDestroy'])->name('document-categories.bulkDestroy');
    Route::resource('document-categories', DocumentCategoryController::class);
    Route::get('document-subcategories', [DocumentSubcategoryController::class, 'index'])->name('document-subcategories.index');
    Route::post('document-subcategories', [DocumentSubcategoryController::class, 'store'])->name('document-subcategories.store');
    Route::put('document-subcategories/{uuid}', [DocumentSubcategoryController::class, 'update'])->name('document-subcategories.update');
    Route::delete('document-subcategories/{uuid}', [DocumentSubcategoryController::class, 'destroy'])->name('document-subcategories.destroy');
    Route::get('pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/export', [PenggunaController::class, 'export'])->name('pengguna.export');

    // end payment

    Route::get('kontak', [FaqController::class, 'kontak'])->name('layanan.kontak');
    Route::delete(
        '/kontak/{uuid}',
        [FaqController::class, 'forceDelete']
    )->name('kontak.destroy');
    Route::patch('/pages/{uuid}/sidebar', [PagesController::class, 'updateSidebar'])->name('pages.updateSidebar');

    Route::prefix('security')->name('security.')->group(function () {
        Route::get('login-activity', [LoginActivityController::class, 'index'])->name('login-activity.index');
        Route::delete('login-activity/bulk', [LoginActivityController::class, 'bulkDestroy'])->name('login-activity.bulkDestroy');
        Route::delete('login-activity/clear', [LoginActivityController::class, 'clear'])->name('login-activity.clear');
        Route::delete('login-activity/{loginActivity}', [LoginActivityController::class, 'destroy'])->name('login-activity.destroy');

        Route::get('login-lockout', [LoginLockoutController::class, 'index'])->name('login-lockout.index');
        Route::delete('login-lockout/bulk', [LoginLockoutController::class, 'bulkDestroy'])->name('login-lockout.bulkDestroy');
        Route::delete('login-lockout/{loginLockout}', [LoginLockoutController::class, 'destroy'])->name('login-lockout.destroy');

        Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
        Route::get('audit-log/export-pdf', [AuditLogController::class, 'exportPdf'])->name('audit-log.exportPdf');
        Route::delete('audit-log/bulk', [AuditLogController::class, 'bulkDestroy'])->name('audit-log.bulkDestroy');
        Route::delete('audit-log/clear', [AuditLogController::class, 'clear'])->name('audit-log.clear');
        Route::delete('audit-log/{auditLog}', [AuditLogController::class, 'destroy'])->name('audit-log.destroy');

        Route::get('failed-login', [FailedLoginController::class, 'index'])->name('failed-login.index');
        Route::delete('failed-login/bulk', [FailedLoginController::class, 'bulkDestroy'])->name('failed-login.bulkDestroy');
        Route::delete('failed-login/clear', [FailedLoginController::class, 'clear'])->name('failed-login.clear');
        Route::delete('failed-login/{failedLogin}', [FailedLoginController::class, 'destroy'])->name('failed-login.destroy');
    });

    Route::resource('website-menu', WebsiteMenuController::class)
        ->middleware('role:admin|super-admin')
        ->parameters(['website-menu' => 'websiteMenu'])
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('website-menu.items', WebsiteMenuItemController::class)
        ->middleware('role:admin|super-admin')
        ->parameters(['website-menu' => 'websiteMenu'])
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::get('website-identity', [WebsiteIdentityController::class, 'edit'])
        ->middleware('role:admin|super-admin')
        ->name('website-identity.edit');
    Route::put('website-identity', [WebsiteIdentityController::class, 'update'])
        ->middleware('role:admin|super-admin')
        ->name('website-identity.update');
});

Route::get('/captcha-file/{config?}', function ($config = 'flat') {
    $captcha = app('captcha');
    if (ob_get_contents()) {
        ob_clean();
    }
    $response = $captcha->create($config);
    // Jangan biarkan request gambar menimpa "previous URL" session.
    // StartSession menyimpan URL GET terakhir sebagai previous, dan browser
    // selalu me-load gambar ini setelah halaman login — tanpa guard ini,
    // redirect()->back() saat login gagal nyasar ke URL gambar (user tidak
    // melihat notifikasi error). Kembalikan ke halaman login bila tertimpa.
    try {
        $prev = session()->get('_previous.url');
        if (is_string($prev) && str_contains($prev, 'captcha')) {
            session()->setPreviousUrl(route('login'));
        }
    } catch (\Throwable $e) {
        // Abaikan: captcha tetap harus terkirim sebagai gambar.
    }
    // Anti-cache header agar captcha selalu fresh & validasi sesuai session
    return $response
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
})->name('captcha.file');
Route::get('/captcha/{config?}', [CaptchaController::class, 'getCaptcha'])->name('captcha.image');
Route::get('/captcha-api/{config?}', [CaptchaController::class, 'getCaptchaApi'])->name('captcha.api');

require __DIR__ . '/auth.php';
