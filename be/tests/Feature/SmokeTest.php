<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        foreach (['super-admin', 'admin', 'opd'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $user = User::factory()->create();
        $user->assignRole('super-admin');

        return $user;
    }

    private function agendaFor(User $user, string $status = 'published'): Agenda
    {
        $category = Category::firstOrCreate(['name' => 'Rapat'], ['slug' => 'rapat-' . uniqid()]);

        return Agenda::create([
            'user_uuid' => $user->uuid,
            'category_uuid' => $category->uuid,
            'title' => 'Agenda Smoke ' . uniqid(),
            'slug' => 'agenda-smoke-' . uniqid(),
            'content' => 'Isi lengkap agenda',
            'excerpt' => 'Ringkasan',
            'status' => $status,
            'jenis_agenda' => 'offline',
            'lokasi' => 'Aula',
            'waktu_mulai' => now()->subDay(),
            'waktu_akhir' => now()->subHour(),
            'search_engine' => 'index',
        ]);
    }

    public function test_pages_render(): void
    {
        $admin = $this->admin();
        $agenda = $this->agendaFor($admin);

        $routes = [
            'dashboard.index',
            'agendas.index',
            'agendas.create',
            'agendas.logAudit',
            'agendas.import',
            'agendas.laporan',
            'agendas.laporanExcel',
            'agendas.laporanPptx',
            'agendas.calendarData',
            'agendas.pendingCount',
            'agendas.ical',
        ];

        foreach ($routes as $name) {
            $response = $this->actingAs($admin)->get(route($name));
            $response->assertStatus(200, "route {$name}");
        }

        $show = $this->actingAs($admin)->get(route('agendas.show', $agenda->uuid));
        $show->assertStatus(200);

        $edit = $this->actingAs($admin)->get(route('agendas.edit', $agenda->uuid));
        $edit->assertStatus(200);
    }

    public function test_pagination_and_post_endpoints(): void
    {
        $admin = $this->admin();

        for ($i = 0; $i < 25; $i++) {
            $this->agendaFor($admin, 'published');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->agendaFor($admin, 'pending');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->agendaFor($admin, 'draft');
        }

        $index = $this->actingAs($admin)->get(route('agendas.index'));
        $index->assertStatus(200);
        $index->assertSee('pagination');

        $audit = $this->actingAs($admin)->get(route('agendas.logAudit'));
        $audit->assertStatus(200);

        $filtered = $this->actingAs($admin)->get(route('agendas.logAudit', ['admin' => $admin->uuid]));
        $filtered->assertStatus(200);

        $pending = Agenda::where('status', 'pending')->pluck('uuid');

        if ($pending->isNotEmpty()) {
            $this->actingAs($admin)->post(route('agendas.bulkStatus'), [
                'ids'     => $pending->take(3)->all(),
                'status'  => 'scheduled',
                '_token'  => csrf_token(),
            ])->assertStatus(302);
        }

        $draftIds = Agenda::where('status', 'draft')->pluck('uuid')->take(3)->all();

        if ($draftIds) {
            $this->actingAs($admin)->post(route('agendas.bulkApprove'), [
                'ids'    => $draftIds,
                'aksi'   => 'publish',
                '_token' => csrf_token(),
            ])->assertStatus(302);
        }

        $this->actingAs($admin)->get(route('agendas.laporan', ['bulan' => now()->format('Y-m')]))->assertStatus(200);
        $this->actingAs($admin)->get(route('agendas.laporanExcel', ['bulan' => now()->format('Y-m')]))->assertStatus(200);
        $this->actingAs($admin)->get(route('agendas.laporanPptx', ['bulan' => now()->format('Y-m')]))->assertStatus(200);
    }

    public function test_single_item_actions(): void
    {
        $admin = $this->admin();
        $category = Category::firstOrCreate(['name' => 'Rapat'], ['slug' => 'rapat-' . uniqid()]);

        $payload = [
            'title'          => 'Agenda Store ' . uniqid(),
            'excerpt'        => 'Ringkasan',
            'content'        => 'Isi lengkap agenda',
            'category_uuid'  => $category->uuid,
            'jenis_agenda'   => 'offline',
            'lokasi'         => 'Aula Lt.4',
            'waktu_mulai'    => now()->addDay()->format('Y-m-d H:i:s'),
            'waktu_akhir'    => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
            'status'         => 'draft',
            'search_engine'  => 'index',
            '_token'         => csrf_token(),
        ];

        $create = $this->actingAs($admin)->post(route('agendas.store'), $payload);
        $create->assertStatus(302);

        $agenda = Agenda::where('title', $payload['title'])->first();
        $this->assertNotNull($agenda, 'agenda tidak tersimpan');

        $this->actingAs($admin)->get(route('agendas.show', $agenda->uuid))->assertStatus(200);

        $update = $this->actingAs($admin)->put(route('agendas.update', $agenda->uuid), [
            ...$payload,
            'title'   => $payload['title'] . ' (edit)',
            'content' => 'Isi hasil edit',
        ]);
        $update->assertStatus(302);
        $this->assertStringContainsString('edit', $agenda->fresh()->title);

        $pending = $this->agendaFor($admin, 'pending');
        $this->actingAs($admin)->post(route('agendas.approve', $pending->uuid), ['_token' => csrf_token()])
            ->assertStatus(302);
        $this->assertSame('published', $pending->fresh()->status);

        $toReject = $this->agendaFor($admin, 'pending');
        $this->actingAs($admin)->post(route('agendas.reject', $toReject->uuid), [
            'catatan' => 'Lokasi belum jelas',
            '_token'  => csrf_token(),
        ])->assertStatus(302);
        $this->assertSame('draft', $toReject->fresh()->status);

        $toRevise = $this->agendaFor($admin, 'pending');
        $this->actingAs($admin)->post(route('agendas.mintaRevisi', $toRevise->uuid), [
            'catatan' => 'Lengkapi URL',
            '_token'  => csrf_token(),
        ])->assertStatus(302);

        $this->actingAs($admin)->delete(route('agendas.destroy', $agenda->uuid), ['_token' => csrf_token()])
            ->assertStatus(302);
        $this->assertSoftDeleted($agenda);

        $this->actingAs($admin)->put(route('agendas.restore', $agenda->uuid), ['_token' => csrf_token()])
            ->assertStatus(302);
        $this->assertNotSoftDeleted($agenda);
    }

    public function test_import_flow(): void
    {
        $admin = $this->admin();
        Category::firstOrCreate(['name' => 'Rapat'], ['slug' => 'rapat-' . uniqid()]);

        $csv = "judul,ringkasan,isi,kategori,jenis,lokasi,waktu_mulai,waktu_akhir,url,tanggal_terbit,status,tags\n"
            . 'Import Agenda A,Ringkasan,Isi lengkap,rapat,offline,Aula,'
            . now()->addDay()->format('Y-m-d H:i') . ','
            . now()->addDay()->addHours(2)->format('Y-m-d H:i')
            . ",,,draft,\n"
            . "Import Agenda B,,Isi,.,offline,Aula,,,,,,\n";

        $file = UploadedFile::fake()->createWithContent('import.csv', $csv);

        $preview = $this->actingAs($admin)->post(route('agendas.importPreview'), [
            'file'   => $file,
            '_token' => csrf_token(),
        ]);
        $preview->assertStatus(200);

        $view = $preview->original;
        $token = $view->getData()['token'] ?? null;
        $this->assertNotEmpty($token, 'token import tidak dibuat');

        $this->actingAs($admin)->post(route('agendas.importConfirm'), [
            'token'  => $token,
            '_token' => csrf_token(),
        ])->assertStatus(302);

        $this->assertDatabaseHas('agendas', ['title' => 'Import Agenda A']);
    }

    public function test_guest_and_auth_pages_render(): void
    {
        if (! file_exists(public_path('build/manifest.json'))) {
            $this->markTestSkipped('Vite manifest belum ada - jalankan npm run build');
        }

        $this->get(route('password.request'))->assertStatus(200);
        $this->get(route('password.reset', ['token' => 'token-palsu']))->assertStatus(200);
        $this->get(route('login'))->assertStatus(200);

        $admin = $this->admin();
        $this->actingAs($admin)->get(route('password.confirm'))->assertStatus(200);
        $this->actingAs($admin)->get(route('profile.edit'))->assertStatus(200);
    }
}
