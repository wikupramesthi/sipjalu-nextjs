<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SecurityRegressionTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        foreach (['super-admin', 'admin'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $user = User::factory()->create();
        $user->assignRole('super-admin');

        return $user;
    }

    private function userWithPassword(string $password): User
    {
        $user = User::factory()->create();
        $user->forceFill(['password' => Hash::make($password)])->save();

        return $user;
    }

    private function agendaFor(User $user, string $status): Agenda
    {
        $category = Category::firstOrCreate(['name' => 'Rapat'], ['slug' => 'rapat-' . uniqid()]);

        return Agenda::create([
            'user_uuid' => $user->uuid,
            'category_uuid' => $category->uuid,
            'title' => 'Agenda ' . $status . ' ' . uniqid(),
            'slug' => 'agenda-' . $status . '-' . uniqid(),
            'content' => 'Isi lengkap agenda',
            'excerpt' => 'Ringkasan',
            'status' => $status,
            'scheduled_at' => $status === 'scheduled' ? now()->addDay() : null,
            'jenis_agenda' => 'offline',
            'lokasi' => 'Aula',
            'waktu_mulai' => now()->subDay(),
            'waktu_akhir' => now()->subHour(),
            'search_engine' => 'index',
        ]);
    }

    public function test_seo_snapshot_only_exposes_published_agendas(): void
    {
        $admin = $this->admin();
        $draft = $this->agendaFor($admin, 'draft');
        $scheduled = $this->agendaFor($admin, 'scheduled');
        $published = $this->agendaFor($admin, 'published');

        $this->get(route('seo.agenda', $draft->slug))->assertNotFound();
        $this->get(route('seo.agenda', $scheduled->slug))->assertNotFound();
        $this->get(route('seo.agenda', $published->slug))
            ->assertOk()
            ->assertSee($published->title);
    }

    public function test_admin_created_user_requires_strong_password(): void
    {
        $admin = $this->admin();

        $payload = [
            'name' => 'Operator Baru',
            'email' => 'operator-baru@example.test',
            'password' => 'terlalu-pendek',
            'role' => 'admin',
            '_token' => csrf_token(),
        ];

        $this->actingAs($admin)
            ->post(route('user.store'), $payload)
            ->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => $payload['email']]);

        $payload['password'] = 'RahasiaKuat123';
        $this->actingAs($admin)->post(route('user.store'), $payload)->assertRedirect();

        $user = User::where('email', $payload['email'])->firstOrFail();
        $this->assertTrue(Hash::check('RahasiaKuat123', $user->password));
        $this->assertFalse(Hash::check('password', $user->password));
    }

    public function test_user_can_detect_the_seeded_default_password(): void
    {
        $user = $this->userWithPassword(User::DEFAULT_PASSWORD);

        $this->assertTrue($user->isUsingDefaultPassword());

        $user->forceFill(['password' => Hash::make('ChangedPassword123!')])->save();

        $this->assertFalse($user->fresh()->isUsingDefaultPassword());
    }

    public function test_default_password_modal_is_only_shown_once_after_successful_login(): void
    {
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user = $this->userWithPassword(User::DEFAULT_PASSWORD);
        $user->assignRole('user');
        Validator::extend('captcha', fn ($attribute, $value, $parameters, $validator) => true);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => User::DEFAULT_PASSWORD,
            'captcha' => 'valid-test-captcha',
        ])
            ->assertRedirect(route('dashboard.index'))
            ->assertSessionHas('show_default_password_modal', true);

        $this->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Akun Anda masih menggunakan password bawaan.', false);

        $this->get(route('dashboard.index'))
            ->assertOk()
            ->assertDontSee('Akun Anda masih menggunakan password bawaan.', false);
    }

    public function test_authorized_user_can_get_health_component_details(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->getJson(route('health.details'))
            ->assertOk()
            ->assertJsonStructure([
                'pemeriksaan' => ['database' => ['ok'], 'penyimpanan' => ['ok'], 'penjadwal' => ['ok']],
                'waktu',
            ]);
    }

    public function test_password_change_rejects_password_without_symbol(): void
    {
        $user = $this->userWithPassword('CurrentPassword123!');

        $this->actingAs($user)
            ->from('/')
            ->put(route('password.update'), [
                'current_password' => 'CurrentPassword123!',
                'password' => 'NewPassword123',
                'password_confirmation' => 'NewPassword123',
            ])
            ->assertSessionHasErrorsIn('updatePassword', ['password']);

        $this->assertTrue(Hash::check('CurrentPassword123!', $user->fresh()->password));
    }

    public function test_password_change_accepts_strong_password(): void
    {
        $user = $this->userWithPassword('CurrentPassword123!');

        $this->actingAs($user)
            ->from('/')
            ->put(route('password.update'), [
                'current_password' => 'CurrentPassword123!',
                'password' => 'Strong8!',
                'password_confirmation' => 'Strong8!',
            ])
            ->assertRedirect('/');

        $this->assertTrue(Hash::check('Strong8!', $user->fresh()->password));
    }
}
