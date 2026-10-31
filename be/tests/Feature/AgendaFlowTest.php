<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AgendaFlowTest extends TestCase
{
    use RefreshDatabase;

    private function agendaBase(): array
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Rapat', 'slug' => 'rapat-' . uniqid()]);

        return [$user, $category];
    }

    public function test_scheduled_agenda_is_published_by_command(): void
    {
        [$user, $category] = $this->agendaBase();

        $agenda = Agenda::create([
            'user_uuid' => $user->uuid,
            'category_uuid' => $category->uuid,
            'title' => 'Agenda Terjadwal ' . uniqid(),
            'slug' => 'agenda-terjadwal-' . uniqid(),
            'content' => 'Isi',
            'status' => 'scheduled',
            'scheduled_at' => now()->subMinute(),
            'search_engine' => 'index',
        ]);

        Artisan::call('agenda:publikasikan-terjadwal');

        $this->assertSame('published', $agenda->fresh()->status);
    }

    public function test_recurrence_requires_waktu_mulai(): void
    {
        $rules = (new \App\Http\Requests\Agenda\StoreAgendaRequest())->rules();

        $validator = \Illuminate\Support\Facades\Validator::make([
            'title' => 'X',
            'content' => 'Y',
            'category_uuid' => 'z',
            'jenis_agenda' => 'online',
            'url' => 'https://example.com',
            'status' => 'draft',
            'search_engine' => 'index',
            'recurrence_rule' => 'harian',
            'recurrence_end' => now()->addWeek()->toDateString(),
        ], $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('waktu_mulai', $validator->errors()->toArray());
    }

    public function test_recurrence_end_must_be_after_waktu_mulai(): void
    {
        $rules = (new \App\Http\Requests\Agenda\StoreAgendaRequest())->rules();

        $mulai = now()->addDays(3);
        $validator = \Illuminate\Support\Facades\Validator::make([
            'title' => 'X',
            'content' => 'Y',
            'category_uuid' => 'z',
            'jenis_agenda' => 'online',
            'url' => 'https://example.com',
            'status' => 'draft',
            'search_engine' => 'index',
            'waktu_mulai' => $mulai->toDateTimeString(),
            'recurrence_rule' => 'harian',
            'recurrence_end' => $mulai->copy()->subDay()->toDateString(),
        ], $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('recurrence_end', $validator->errors()->toArray());
    }

    public function test_attachments_max_ten_files(): void
    {
        $rules = (new \App\Http\Requests\Agenda\StoreAgendaRequest())->rules();

        $validator = \Illuminate\Support\Facades\Validator::make([
            'attachments' => array_fill(0, 11, ['file' => 'x']),
        ], ['attachments' => $rules['attachments']]);

        $this->assertTrue($validator->fails());
    }
}
