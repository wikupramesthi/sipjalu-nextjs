<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\AduanController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\WebsiteMenuController;
use App\Http\Controllers\Api\WebsiteIdentityController;
use App\Http\Controllers\Api\BeritaLiveController;
use App\Http\Controllers\Api\CctvController;
use App\Http\Controllers\Api\CuacaController;
use App\Http\Controllers\Api\PorprovBeritaController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Profil pemilik token (pengganti closure: route:cache di entrypoint docker
// gagal bila ada route closure).
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

// Autentikasi token (Sanctum) untuk Frontend/Mobile.
// Login dibatasi 10x/menit + kunci brute-force (lihat AuthController).
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll'])->middleware('throttle:5,1');
    });
});

// Layanan: read-only untuk frontend (GET saja) — hanya is_active=active.
Route::middleware(['throttle:60,1', 'public.key'])->prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/{uuid}', [ServiceController::class, 'show']);
});

Route::middleware(['throttle:60,1', 'public.key'])->group(function () {
    Route::get('/faqs', [FaqController::class, 'index']);
    Route::get('/categories', [AgendaController::class, 'category']);
    Route::get('/porprov-berita', [PorprovBeritaController::class, 'index']);
    Route::get('/porprov-berita/{idOrSlug}', [PorprovBeritaController::class, 'show']);
});
Route::get('/contact/captcha', [ContactController::class, 'captcha'])->middleware(['throttle:30,1', 'public.key']);
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:10,1');

// Agenda — publik hanya published (lihat AgendaController), throttle anti-scrape
Route::middleware(['throttle:60,1', 'public.key'])->prefix('agenda')->group(function () {
    Route::get('/', [AgendaController::class, 'index']);
    Route::get('category/{slug}', [AgendaController::class, 'byCategory']);
    Route::get('{slug}', [AgendaController::class, 'show']);
});

// Dokumen (read-only untuk frontend; tulis via panel admin)
Route::middleware(['throttle:60,1', 'public.key'])->group(function () {
    Route::get('/document-categories', [DocumentController::class, 'categories']);
    Route::get('/document-subcategories', [DocumentController::class, 'getSubcategories']);
    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index']);
        Route::get('/category/{slug}', [DocumentController::class, 'byCategory']);
    });
});

// aduan: baca bebas throttle, lapor wajib login + throttle ketat
Route::middleware('throttle:30,1')->prefix('aduan')->group(function () {
    Route::get('/', [AduanController::class, 'index']);
    Route::get('/track/{nomor_aduan}', [AduanController::class, 'track'])
        ->where('nomor_aduan', 'ADU-[0-9]{8}-[0-9]{4}');
    Route::post('/', [AduanController::class, 'store'])->middleware(['auth:sanctum', 'throttle:10,1']);
});

// halaman statis — hanya is_published=true (lihat PageController)
Route::middleware(['throttle:60,1', 'public.key'])->group(function () {
    Route::get('/pages', [PageController::class, 'index']);
    Route::get('/pages/{slug}', [PageController::class, 'show']);
});

// Website Menu (navigasi dinamis)
Route::middleware(['throttle:60,1', 'public.key'])->group(function () {
    Route::get('/menus', [WebsiteMenuController::class, 'index']);
    Route::get('/menus/{slug}', [WebsiteMenuController::class, 'show']);
});

// Website Identity (SEO, logo, favicon, kontak footer)
Route::middleware(['throttle:60,1', 'public.key'])->group(function () {
    Route::get('/website-identity', [WebsiteIdentityController::class, 'show']);
    Route::get('/website-identities', [WebsiteIdentityController::class, 'index']); // alias plural
});

// Berita dari database lokal, dengan format kompatibel untuk frontend lama.
Route::middleware(['throttle:60,1', 'public.key'])->group(function () {
    Route::get('/berita-live', [BeritaLiveController::class, 'index']);
    Route::get('/post/all', [BeritaLiveController::class, 'all']);
    Route::get('/post/search', [BeritaLiveController::class, 'search']);
    Route::get('/post/category/{slug}', [BeritaLiveController::class, 'byCategory'])
        ->where('slug', '[A-Za-z0-9._-]+');
    Route::get('/post/detail/{slug}', [BeritaLiveController::class, 'show'])
        ->where('slug', '[A-Za-z0-9._-]+');
    Route::get('/post/{slug}', [BeritaLiveController::class, 'show'])
        ->where('slug', '[A-Za-z0-9._-]+');
});

// Cuaca Kota Bekasi (proxy Open-Meteo + cache 10 menit, untuk pita cuaca /cctv)
Route::middleware(['throttle:60,1', 'public.key'])->get('/cuaca', [CuacaController::class, 'index']);

// CCTV Kota Bekasi (proxy HLS eofficev2 + cache; pemutar di halaman /cctv)
Route::middleware(['throttle:60,1', 'public.key'])->get('/cctv', [CctvController::class, 'index']);
Route::middleware('throttle:120,1')->group(function () {
    Route::get('/cctv/stream/{file}', [CctvController::class, 'stream'])
        ->where('file', '[A-Za-z0-9_\-\.]+');
    Route::get('/cctv/seg/{file}', [CctvController::class, 'segment'])
        ->where('file', '[A-Za-z0-9_\-\.]+');
});

// Unified Search API (Meilisearch) — throttle ketat anti-abuse
Route::middleware(['throttle:30,1', 'public.key'])->group(function () {
    Route::get('/search', [SearchController::class, 'search']);
    Route::get('/search/suggest', [SearchController::class, 'suggest']);
});

// Agenda v1 untuk web bekasikota.go.id — ber-token Sanctum.
// Web bekasikota me-render HTML sendiri dari JSON ini (URL gambar absolut BE).
Route::middleware(['auth:sanctum', 'throttle:120,1'])->prefix('v1')->group(function () {
    Route::get('/agenda', [\App\Http\Controllers\Api\V1\AgendaController::class, 'index']);
    // Jadwal tetap di bawah /v1/agenda (didefinisikan SEBELUM {slug} agar
    // "jadwal" tidak ditangkap sebagai slug).
    Route::get('/agenda/jadwal', [\App\Http\Controllers\Api\V1\AgendaController::class, 'jadwal']);
    Route::get('/agenda/{slug}', [\App\Http\Controllers\Api\V1\AgendaController::class, 'show'])
        ->where('slug', '[A-Za-z0-9\-\._]+');
    // Alias lama → tetap hidup agar klien yang telanjur pakai tidak putus.
    Route::get('/jadwal', [\App\Http\Controllers\Api\V1\AgendaController::class, 'jadwal']);
});

// Agenda OPD Mobile — auth Sanctum + OPD token ability check
Route::middleware(['auth:sanctum', 'opd.token', 'throttle:60,1'])->prefix('opd')->group(function () {
    Route::get('/agendas/statistik', [\App\Http\Controllers\Api\Opd\AgendaController::class, 'statistik']);
    Route::get('/agendas/kategori', [\App\Http\Controllers\Api\Opd\AgendaController::class, 'kategori']);
    Route::get('/agendas', [\App\Http\Controllers\Api\Opd\AgendaController::class, 'index']);
    Route::post('/agendas', [\App\Http\Controllers\Api\Opd\AgendaController::class, 'store']);
    Route::get('/agendas/{uuid}', [\App\Http\Controllers\Api\Opd\AgendaController::class, 'show']);
    Route::put('/agendas/{uuid}', [\App\Http\Controllers\Api\Opd\AgendaController::class, 'update']);
    Route::delete('/agendas/{uuid}', [\App\Http\Controllers\Api\Opd\AgendaController::class, 'destroy']);
});