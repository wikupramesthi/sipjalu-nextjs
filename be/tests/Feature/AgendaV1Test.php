<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendaV1Test extends TestCase
{
    use RefreshDatabase;

    private function userWithToken(): array
    {
        $user = User::factory()->create();
        $token = $user->createToken('bekasikota-web', ['agenda:read'])->plainTextToken;

        return [$user, $token];
    }

    private function agenda(User $user, Category $category, array $overrides = []): Agenda
    {
        return Agenda::create(array_merge([
            'user_uuid' => $user->uuid,
            'category_uuid' => $category->uuid,
            'title' => 'Agenda V1 ' . uniqid(),
            'slug' => 'agenda-v1-' . uniqid(),
            'content' => 'Isi agenda',
            'status' => 'published',
            'jenis_agenda' => 'offline',
            'lokasi' => 'Aula',
            'search_engine' => 'index',
            'scheduled_at' => now()->subDay(),
            'waktu_mulai' => now()->addDay(),
        ], $overrides));
    }

    public function test_tanpa_token_ditolak(): void
    {
        $this->getJson('/api/v1/agenda')->assertStatus(401);
        $this->getJson('/api/v1/agenda/jadwal?month=' . now()->format('Y-m'))->assertStatus(401);
        $this->getJson('/api/v1/agenda/slug-apa-pun')->assertStatus(401);
    }

    public function test_list_pagination_dan_filter(): void
    {
        [$user, $token] = $this->userWithToken();
        $category = Category::create(['name' => 'Rapat', 'slug' => 'rapat-' . uniqid()]);

        $this->agenda($user, $category, ['title' => 'Musrenbang Kota Bekasi']);
        $this->agenda($user, $category, ['status' => 'draft', 'title' => 'Draft jangan tampil']);

        $res = $this->withToken($token)->getJson('/api/v1/agenda?per_page=10');
        $res->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure(['data', 'meta' => ['current_page', 'per_page', 'total']]);

        $this->assertSame(1, $res->json('meta.total'));

        $this->withToken($token)->getJson('/api/v1/agenda?search=Musrenbang')
            ->assertOk()->assertJsonPath('meta.total', 1);

        $this->withToken($token)->getJson('/api/v1/agenda?search=tidak-ada-xyz')
            ->assertOk()->assertJsonPath('meta.total', 0);
    }

    public function test_jadwal_bulanan_dan_upcoming(): void
    {
        [$user, $token] = $this->userWithToken();
        $category = Category::create(['name' => 'Upacara', 'slug' => 'upacara-' . uniqid()]);

        $mulai = now()->addDays(2)->setTime(9, 0);
        $agenda = $this->agenda($user, $category, [
            'waktu_mulai' => $mulai,
            'scheduled_at' => now()->subDay(),
        ]);

        $month = $mulai->format('Y-m');

        $res = $this->withToken($token)->getJson("/api/v1/agenda/jadwal?month={$month}");
        $res->assertOk()->assertJsonPath('status', 'success');

        $dates = collect($res->json('data.days'))->pluck('date');
        $this->assertContains($mulai->format('Y-m-d'), $dates->all());

        $up = $this->withToken($token)->getJson('/api/v1/agenda/jadwal?upcoming=1&limit=5');
        $up->assertOk();
        $this->assertGreaterThanOrEqual(1, $up->json('data.total'));

        // Alias lama tetap hidup.
        $this->withToken($token)->getJson("/api/v1/jadwal?month={$month}")->assertOk();

        $this->withToken($token)->getJson('/api/v1/agenda/jadwal?month=bulan-salah')
            ->assertStatus(422);
    }

    public function test_detail_by_slug_dan_uuid(): void
    {
        [$user, $token] = $this->userWithToken();
        $category = Category::create(['name' => 'Rapat', 'slug' => 'rapat-' . uniqid()]);
        $agenda = $this->agenda($user, $category);

        $this->withToken($token)->getJson('/api/v1/agenda/' . $agenda->slug)
            ->assertOk()
            ->assertJsonPath('data.agenda.slug', $agenda->slug)
            ->assertJsonStructure(['data' => ['agenda', 'terkait']]);

        $this->withToken($token)->getJson('/api/v1/agenda/' . $agenda->uuid)
            ->assertOk()
            ->assertJsonPath('data.agenda.uuid', $agenda->uuid);

        $this->withToken($token)->getJson('/api/v1/agenda/slug-tidak-ada')
            ->assertStatus(404);
    }
}
