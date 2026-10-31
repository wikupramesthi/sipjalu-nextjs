<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aduan;
use App\Models\Agenda;
use App\Models\Document;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Pencarian global panel admin. Minimal 2 karakter.
     * Warga (role user) hanya mencari aduan milik sendiri.
     */
    public function index(Request $request): JsonResponse
    {
        $q = trim((string) $request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json(['data' => []]);
        }

        $user = $request->user();
        $warga = !$user->hasAnyRole(['super-admin', 'admin', 'uptd']);
        $like = '%' . $q . '%';
        $groups = [];

        // Aduan
        if ($user->can('aduans.index')) {
            $aq = Aduan::query()->whereNull('archived_at');

            if ($warga) {
                $aq->where('user_uuid', $user->uuid);
            }

            $items = $aq->where(function ($w) use ($like) {
                $w->where('nomor_aduan', 'like', $like)
                    ->orWhere('judul', 'like', $like)
                    ->orWhere('lokasi', 'like', $like);
            })->orderByDesc('tanggal_pengaduan')->limit(6)->get();

            if ($items->isNotEmpty()) {
                $groups[] = [
                    'label' => 'Pengaduan',
                    'icon' => 'bi-megaphone',
                    'items' => $items->map(fn (Aduan $a) => [
                        'title' => $a->nomor_aduan . ' — ' . $a->judul,
                        'subtitle' => $a->kategori . ' • ' . ucfirst($a->status),
                        'url' => route('aduans.show', $a->uuid),
                    ])->values(),
                ];
            }
        }

        if (!$warga) {
            // Agenda
            $agendas = Agenda::query()
                ->where('title', 'like', $like)
                ->orderByDesc('created_at')
                ->limit(6)
                ->get(['uuid', 'title', 'status']);

            if ($agendas->isNotEmpty()) {
                $groups[] = [
                    'label' => 'Agenda / Agenda',
                    'icon' => 'bi-newspaper',
                    'items' => $agendas->map(fn (Agenda $a) => [
                        'title' => $a->title,
                        'subtitle' => ucfirst($a->status ?? 'draft'),
                        'url' => route('agendas.edit', $a->uuid),
                    ])->values(),
                ];
            }

            // Dokumen
            $docs = Document::query()
                ->where('title', 'like', $like)
                ->orderByDesc('created_at')
                ->limit(6)
                ->get(['uuid', 'title', 'status']);

            if ($docs->isNotEmpty()) {
                $groups[] = [
                    'label' => 'Dokumen',
                    'icon' => 'bi-file-earmark-text',
                    'items' => $docs->map(fn (Document $d) => [
                        'title' => $d->title,
                        'subtitle' => ucfirst($d->status ?? '-'),
                        'url' => route('documents.edit', $d->uuid),
                    ])->values(),
                ];
            }

            // Halaman
            $pages = Page::query()
                ->where('title', 'like', $like)
                ->orderByDesc('created_at')
                ->limit(6)
                ->get(['uuid', 'title']);

            if ($pages->isNotEmpty()) {
                $groups[] = [
                    'label' => 'Halaman',
                    'icon' => 'bi-file-richtext',
                    'items' => $pages->map(fn (Page $p) => [
                        'title' => $p->title,
                        'subtitle' => 'Halaman statis',
                        'url' => route('pages.edit', $p->uuid),
                    ])->values(),
                ];
            }

            // Pengguna
            $users = User::query()
                ->where(function ($w) use ($like) {
                    $w->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like);
                })
                ->orderBy('name')
                ->limit(6)
                ->get(['uuid', 'name', 'email']);

            if ($users->isNotEmpty()) {
                $groups[] = [
                    'label' => 'Pengguna',
                    'icon' => 'bi-people',
                    'items' => $users->map(fn (User $u) => [
                        'title' => $u->name,
                        'subtitle' => $u->email,
                        'url' => route('pengguna.index'),
                    ])->values(),
                ];
            }
        }

        return response()->json(['data' => $groups]);
    }
}
