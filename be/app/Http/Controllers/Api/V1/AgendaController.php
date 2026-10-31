<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AgendaResource;
use App\Models\Agenda;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * API agenda untuk web bekasikota.go.id (ber-token Sanctum).
 *
 *   GET /api/v1/agenda        daftar + pagination + filter
 *   GET /api/v1/jadwal        kalender bulanan + upcoming
 *   GET /api/v1/agenda/{slug} detail (slug atau uuid) + terkait
 *
 * Auth: header `Authorization: Bearer <token>` (lihat
 * `php artisan integrasi:token-bekasikota`). Response ikut format
 * standar: { status, message, data } + meta untuk list.
 */
class AgendaController extends Controller
{
    /**
     * Daftar agenda.
     *
     * Query:
     * - search=...        cari judul/ringkasan/isi/lokasi
     * - kategori=slug     filter kategori
     * - from=YYYY-MM-DD   tanggal acara >= from
     * - to=YYYY-MM-DD     tanggal acara <= to
     * - upcoming=1        hanya yang belum lewat
     * - featured=1        hanya featured
     * - sort=terdekat|terbaru (default: terdekat)
     * - per_page=1..50 (default 12), page=N
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'search' => ['nullable', 'string', 'max:100'],
                'kategori' => ['nullable', 'string', 'max:100'],
                'from' => ['nullable', 'date_format:Y-m-d'],
                'to' => ['nullable', 'date_format:Y-m-d'],
                'upcoming' => ['nullable', 'boolean'],
                'featured' => ['nullable', 'boolean'],
                'sort' => ['nullable', 'in:terdekat,terbaru'],
                'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            ]);

            $query = $this->baseQuery();

            $this->applyFilters($query, $validated);

            $sort = $validated['sort'] ?? 'terdekat';
            $this->applySort($query, $sort);

            $perPage = (int) ($validated['per_page'] ?? 12);

            $paginator = $query->paginate($perPage)->appends($request->query());

            return $this->paginated(
                'Daftar agenda berhasil diambil',
                $paginator,
                fn ($items) => AgendaResource::collection($items)->toArray($request)
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationError('Parameter tidak valid.', $e->errors());
        } catch (\Exception $e) {
            Log::error('V1 agenda index error: ' . $e->getMessage());

            return $this->error('Gagal mengambil daftar agenda');
        }
    }

    /**
     * Jadwal agenda untuk widget kalender bekasikota.
     *
     * Mendukung dua pola (boleh digabung):
     * - Kalender: ?month=YYYY-MM → grup per tanggal sebulan penuh.
     * - Upcoming: ?upcoming=1&limit=N (default 10, maks 50) → list terdekat.
     * - Bebas: ?date=YYYY-MM-DD / ?from=&to= → rentang tanggal acara.
     *
     * Respons: { month, from, to, total, days: [{date,count,items}], items: [...] }
     */
    public function jadwal(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'month' => ['nullable', 'date_format:Y-m'],
                'date' => ['nullable', 'date_format:Y-m-d'],
                'from' => ['nullable', 'date_format:Y-m-d'],
                'to' => ['nullable', 'date_format:Y-m-d'],
                'upcoming' => ['nullable', 'boolean'],
                'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
                'kategori' => ['nullable', 'string', 'max:100'],
            ]);

            $now = Carbon::now();

            if (! empty($validated['date'])) {
                $from = Carbon::createFromFormat('Y-m-d', $validated['date'])->startOfDay();
                $to = $from->copy()->endOfDay();
                $month = $from->format('Y-m');
            } elseif (! empty($validated['month'])) {
                $monthStart = Carbon::createFromFormat('Y-m', $validated['month'])->startOfMonth();
                $from = $monthStart->copy()->startOfDay();
                $to = $monthStart->copy()->endOfMonth()->endOfDay();
                $month = $monthStart->format('Y-m');
            } else {
                $from = ! empty($validated['from'])
                    ? Carbon::createFromFormat('Y-m-d', $validated['from'])->startOfDay()
                    : $now->copy()->startOfDay();
                $to = ! empty($validated['to'])
                    ? Carbon::createFromFormat('Y-m-d', $validated['to'])->endOfDay()
                    : $from->copy()->addDays(30)->endOfDay();
                $month = null;

                if ($to->lessThan($from)) {
                    return $this->validationError('Parameter tidak valid.', [
                        'to' => ['Tanggal akhir harus >= tanggal awal.'],
                    ]);
                }

                if ($from->diffInDays($to) > 366) {
                    return $this->validationError('Parameter tidak valid.', [
                        'to' => ['Rentang maksimal 366 hari.'],
                    ]);
                }
            }

            $query = $this->baseQuery();

            if (! empty($validated['kategori'])) {
                $slug = $validated['kategori'];
                $query->whereHas('category', fn (Builder $q) => $q->where('slug', $slug));
            }

            // Tanggal acara = waktu_mulai, jatuh ke scheduled_at.
            $query->where(function (Builder $q) use ($from, $to) {
                $q->whereBetween('waktu_mulai', [$from, $to])
                    ->orWhere(function (Builder $qq) use ($from, $to) {
                        $qq->whereNull('waktu_mulai')->whereBetween('scheduled_at', [$from, $to]);
                    });
            });

            if (! empty($validated['upcoming'])) {
                $query->where(function (Builder $q) use ($now) {
                    $q->where('waktu_mulai', '>=', $now)
                        ->orWhere(function (Builder $qq) use ($now) {
                            $qq->whereNull('waktu_mulai')->where('scheduled_at', '>=', $now);
                        });
                });
            }

            $query->orderByRaw('COALESCE(waktu_mulai, scheduled_at) ASC');

            $limit = (int) ($validated['limit'] ?? 0);
            $agendas = $limit > 0 ? $query->limit($limit)->get() : $query->get();

            $items = AgendaResource::collection($agendas)->toArray($request);

            $days = [];
            foreach ($items as $item) {
                $date = $item['tanggal'] ?? null;
                if (! $date) {
                    continue;
                }
                if (! isset($days[$date])) {
                    $days[$date] = ['date' => $date, 'count' => 0, 'items' => []];
                }
                $days[$date]['items'][] = $item;
                $days[$date]['count']++;
            }
            ksort($days);

            return $this->success('Jadwal agenda berhasil diambil', [
                'month' => $month,
                'from' => $from->format('Y-m-d'),
                'to' => $to->format('Y-m-d'),
                'total' => count($items),
                'days' => array_values($days),
                'items' => $items,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationError('Parameter tidak valid.', $e->errors());
        } catch (\Exception $e) {
            Log::error('V1 agenda jadwal error: ' . $e->getMessage());

            return $this->error('Gagal mengambil jadwal agenda');
        }
    }

    /**
     * Detail satu agenda by slug atau uuid + 3 agenda terkait
     * (kategori sama, terdekat).
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        try {
            if (mb_strlen($slug) > 255) {
                return $this->error('Agenda tidak ditemukan', null, 404);
            }

            $agenda = $this->baseQuery()
                ->where(function (Builder $q) use ($slug) {
                    $q->where('slug', $slug)->orWhere('uuid', $slug);
                })
                ->first();

            if (! $agenda) {
                return $this->error('Agenda tidak ditemukan', null, 404);
            }

            // Hitung view 1x per IP per jam (sama seperti API publik).
            $viewKey = 'agenda:v1:view:' . $agenda->getKey() . ':' . hash('sha256', (string) $request->ip());
            if (! Cache::has($viewKey)) {
                $agenda->incrementViews();
                Cache::put($viewKey, true, now()->addHour());
            }

            $related = $this->baseQuery()
                ->where('uuid', '!=', $agenda->uuid)
                ->when($agenda->category_uuid, fn (Builder $q) => $q->where('category_uuid', $agenda->category_uuid))
                ->orderByRaw('COALESCE(waktu_mulai, scheduled_at) ASC')
                ->limit(3)
                ->get();

            return $this->success('Detail agenda berhasil diambil', [
                'agenda' => new AgendaResource($agenda),
                'terkait' => AgendaResource::collection($related),
            ]);
        } catch (\Exception $e) {
            Log::error('V1 agenda detail error: ' . $e->getMessage());

            return $this->error('Gagal mengambil detail agenda');
        }
    }

    private function baseQuery(): Builder
    {
        return Agenda::query()
            ->with(['user', 'category', 'images'])
            ->where('status', 'published')
            ->published();
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['kategori'])) {
            $slug = $filters['kategori'];
            $query->whereHas('category', fn (Builder $q) => $q->where('slug', $slug));
        }

        if (! empty($filters['from'])) {
            $from = Carbon::createFromFormat('Y-m-d', $filters['from'])->startOfDay();
            $query->where(function (Builder $q) use ($from) {
                $q->where('waktu_mulai', '>=', $from)
                    ->orWhere(function (Builder $qq) use ($from) {
                        $qq->whereNull('waktu_mulai')->where('scheduled_at', '>=', $from);
                    });
            });
        }

        if (! empty($filters['to'])) {
            $to = Carbon::createFromFormat('Y-m-d', $filters['to'])->endOfDay();
            $query->where(function (Builder $q) use ($to) {
                $q->where('waktu_mulai', '<=', $to)
                    ->orWhere(function (Builder $qq) use ($to) {
                        $qq->whereNull('waktu_mulai')->where('scheduled_at', '<=', $to);
                    });
            });
        }

        if (! empty($filters['upcoming'])) {
            $now = Carbon::now();
            $query->where(function (Builder $q) use ($now) {
                $q->where('waktu_mulai', '>=', $now)
                    ->orWhere(function (Builder $qq) use ($now) {
                        $qq->whereNull('waktu_mulai')->where('scheduled_at', '>=', $now);
                    });
            });
        }

        if (! empty($filters['featured'])) {
            $query->where('is_featured', true);
        }
    }

    private function applySort(Builder $query, string $sort): void
    {
        if ($sort === 'terbaru') {
            $query->latest('created_at');
            return;
        }

        // terdekat: tanggal acara menaik (NULL di belakang).
        $query->orderByRaw('COALESCE(waktu_mulai, scheduled_at) IS NULL, COALESCE(waktu_mulai, scheduled_at) ASC');
    }
}
