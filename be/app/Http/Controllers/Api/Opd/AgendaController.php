<?php

namespace App\Http\Controllers\Api\Opd;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgendaResource;
use App\Models\Agenda;
use App\Models\Category;
use App\Services\HtmlSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AgendaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'status' => ['nullable', 'string', 'in:draft,published,scheduled,pending'],
            ]);
            $status = (string) $request->input('status', '');
            $query = Agenda::with(['category', 'user'])
                ->where('user_uuid', $request->user()->uuid);

            if ($status !== '') {
                $query->where('status', $status);
            }

            $agendas = $query->latest('created_at')->paginate(15);

            return response()->json([
                'status' => 'success',
                'message' => 'Daftar agenda berhasil diambil',
                'data' => AgendaResource::collection($agendas),
                'meta' => [
                    'current_page' => $agendas->currentPage(),
                    'last_page' => $agendas->lastPage(),
                    'total' => $agendas->total(),
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter tidak valid',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('OPD agenda list error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal mengambil agenda'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'excerpt' => 'nullable|string|max:255',
                'content' => 'required|string',
                'category_uuid' => 'required|exists:categories,uuid',
                'jenis_agenda' => 'required|in:offline,online',
                'lokasi' => 'nullable|string|max:255|required_if:jenis_agenda,offline',
                'waktu_mulai' => 'nullable|date',
                'waktu_akhir' => 'nullable|date|after_or_equal:waktu_mulai',
                'url' => 'nullable|url|max:500|required_if:jenis_agenda,online',
                'tagging' => 'nullable|string|max:255',
            ]);

            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $counter = 2;
            while (Agenda::withTrashed()->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $agenda = Agenda::create([
                'user_uuid' => $request->user()->uuid,
                'category_uuid' => $validated['category_uuid'],
                'title' => $validated['title'],
                'slug' => $slug,
                'excerpt' => $validated['excerpt'] ?? null,
                'content' => HtmlSanitizer::clean($validated['content']),
                'jenis_agenda' => $validated['jenis_agenda'],
                'lokasi' => $validated['jenis_agenda'] === 'offline' ? ($validated['lokasi'] ?? null) : null,
                'waktu_mulai' => $validated['waktu_mulai'] ?? null,
                'waktu_akhir' => $validated['waktu_akhir'] ?? null,
                'url' => $validated['url'] ?? null,
                'tagging' => $validated['tagging'] ?? null,
                'status' => 'pending',
                'search_engine' => 'index',
            ]);

            $agenda->logs()->create([
                'user_uuid' => $request->user()->uuid,
                'aksi' => 'dibuat',
                'catatan' => 'Dibuat via API mobile',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Agenda dikirim dan menunggu persetujuan admin',
                'data' => new AgendaResource($agenda),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('OPD agenda store error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan agenda'], 500);
        }
    }

    public function show(Request $request, string $uuid): JsonResponse
    {
        try {
            $agenda = Agenda::with(['category', 'user', 'attachments', 'images'])
                ->where('uuid', $uuid)
                ->where('user_uuid', $request->user()->uuid)
                ->firstOrFail();

            return response()->json([
                'status' => 'success',
                'message' => 'Detail agenda berhasil diambil',
                'data' => new AgendaResource($agenda),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Agenda tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error('OPD agenda show error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal mengambil detail agenda'], 500);
        }
    }

    public function update(Request $request, string $uuid): JsonResponse
    {
        try {
            $agenda = Agenda::where('uuid', $uuid)
                ->where('user_uuid', $request->user()->uuid)
                ->firstOrFail();

            if ($agenda->status !== 'draft') {
                return response()->json(['status' => 'error', 'message' => 'Hanya agenda draft yang bisa diubah'], 422);
            }

            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'excerpt' => 'nullable|string|max:255',
                'content' => 'sometimes|required|string',
                'category_uuid' => 'sometimes|required|exists:categories,uuid',
                'jenis_agenda' => 'sometimes|required|in:offline,online',
                'lokasi' => 'nullable|string|max:255|required_if:jenis_agenda,offline',
                'waktu_mulai' => 'nullable|date',
                'waktu_akhir' => 'nullable|date|after_or_equal:waktu_mulai',
                'url' => 'nullable|url|max:500|required_if:jenis_agenda,online',
                'tagging' => 'nullable|string|max:255',
            ]);

            if (isset($validated['title'])) {
                $slug = Str::slug($validated['title']);
                $originalSlug = $slug;
                $counter = 2;
                while (Agenda::withTrashed()->where('slug', $slug)->where('uuid', '!=', $uuid)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }
                $agenda->slug = $slug;
            }

            $agenda->fill($validated);
            $agenda->content = HtmlSanitizer::clean($validated['content'] ?? $agenda->content);
            $agenda->status = 'pending';
            $agenda->save();

            $agenda->logs()->create([
                'user_uuid' => $request->user()->uuid,
                'aksi' => 'diperbarui',
                'catatan' => 'Diubah via API mobile',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Perubahan dikirim dan menunggu persetujuan admin',
                'data' => new AgendaResource($agenda),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Agenda tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error('OPD agenda update error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui agenda'], 500);
        }
    }

    public function destroy(Request $request, string $uuid): JsonResponse
    {
        try {
            $agenda = Agenda::where('uuid', $uuid)
                ->where('user_uuid', $request->user()->uuid)
                ->firstOrFail();

            if ($agenda->status !== 'draft') {
                return response()->json(['status' => 'error', 'message' => 'Hanya agenda draft yang bisa dihapus'], 422);
            }

            $agenda->delete();

            return response()->json(['status' => 'success', 'message' => 'Agenda berhasil dihapus']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Agenda tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error('OPD agenda destroy error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus agenda'], 500);
        }
    }

    public function statistik(Request $request): JsonResponse
    {
        try {
            $userUuid = $request->user()->uuid;

            $stats = [
                'total' => Agenda::where('user_uuid', $userUuid)->count(),
                'published' => Agenda::where('user_uuid', $userUuid)->where('status', 'published')->count(),
                'pending' => Agenda::where('user_uuid', $userUuid)->where('status', 'pending')->count(),
                'draft' => Agenda::where('user_uuid', $userUuid)->where('status', 'draft')->count(),
                'scheduled' => Agenda::where('user_uuid', $userUuid)->where('status', 'scheduled')->count(),
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Statistik berhasil diambil',
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('OPD statistik error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal mengambil statistik'], 500);
        }
    }

    public function kategori(): JsonResponse
    {
        try {
            $categories = Category::orderBy('name')->get(['uuid', 'name', 'slug']);

            return response()->json([
                'status' => 'success',
                'message' => 'Daftar kategori berhasil diambil',
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            Log::error('OPD kategori error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal mengambil kategori'], 500);
        }
    }
}
