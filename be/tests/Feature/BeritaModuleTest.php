<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\BeritaImage;
use App\Models\Category;
use App\Models\User;
use App\Notifications\BeritaReviewNotification;
use App\Notifications\BeritaSubmittedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BeritaModuleTest extends TestCase
{
    use RefreshDatabase;

    private function userWithBeritaAccess(): User
    {
        $permissions = collect(['agendas.index', 'agendas.create', 'agendas.store', 'agendas.destroy', 'agendas.approve'])
            ->map(fn (string $name) => Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']));
        $user = User::factory()->create();
        $user->givePermissionTo($permissions);

        return $user;
    }

    private function adminWithBeritaApprovalAccess(): User
    {
        $permission = Permission::firstOrCreate(['name' => 'agendas.approve', 'guard_name' => 'web']);
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $role->givePermissionTo($permission);

        $admin = User::factory()->create();
        $admin->assignRole($role);

        return $admin;
    }

    public function test_create_form_has_no_agenda_implementation_section(): void
    {
        $user = $this->userWithBeritaAccess();

        $response = $this->actingAs($user)->get(route('beritas.create'));

        $response->assertOk();
        $response->assertSee('Konten Berita');
        $response->assertDontSee('Detail Pelaksanaan');
    }

    public function test_store_saves_berita_without_agenda_implementation_fields(): void
    {
        $user = $this->userWithBeritaAccess();
        $category = Category::create([
            'name' => 'Berita Umum',
            'slug' => 'berita-umum-' . uniqid(),
        ]);

        $response = $this->actingAs($user)->post(route('beritas.store'), [
            'title' => 'Berita Uji ' . uniqid(),
            'category_uuid' => $category->uuid,
            'excerpt' => 'Ringkasan berita',
            'content' => '<p>Isi berita</p>',
            'scheduled_at' => now()->toDateTimeString(),
            'status' => 'published',
            'search_engine' => 'index',
        ]);

        $response->assertRedirect(route('beritas.index'));
        $this->assertDatabaseHas('beritas', [
            'user_uuid' => $user->uuid,
            'category_uuid' => $category->uuid,
            'excerpt' => 'Ringkasan berita',
            'status' => 'pending',
        ]);
    }

    public function test_store_saves_featured_image_slider_images_and_attachments(): void
    {
        Storage::fake('public');
        $user = $this->userWithBeritaAccess();
        $category = Category::create([
            'name' => 'Berita Media',
            'slug' => 'berita-media-' . uniqid(),
        ]);

        $response = $this->actingAs($user)->post(route('beritas.store'), [
            'title' => 'Berita Media Uji ' . uniqid(),
            'category_uuid' => $category->uuid,
            'content' => '<p>Isi berita</p>',
            'status' => 'published',
            'search_engine' => 'index',
            'featured_image' => UploadedFile::fake()->image('utama.jpg'),
            'images' => [UploadedFile::fake()->image('slider.jpg')],
            'attachments' => [UploadedFile::fake()->create('lampiran.pdf', 10, 'application/pdf')],
        ]);

        $response->assertRedirect(route('beritas.index'));
        $berita = Berita::where('user_uuid', $user->uuid)->firstOrFail();

        $this->assertNotNull($berita->featured_image);
        $this->assertCount(1, $berita->images);
        $this->assertCount(1, $berita->attachments);
        Storage::disk('public')->assertExists($berita->featured_image);
        Storage::disk('public')->assertExists($berita->images->first()->image_path);
        Storage::disk('public')->assertExists($berita->attachments->first()->file_path);
    }

    public function test_archiving_berita_keeps_its_media_for_restore(): void
    {
        Storage::fake('public');
        $user = $this->userWithBeritaAccess();
        $category = Category::create([
            'name' => 'Berita Arsip',
            'slug' => 'berita-arsip-' . uniqid(),
        ]);
        Storage::disk('public')->put('berita/featured.jpg', 'featured image');
        Storage::disk('public')->put('berita/images/slider.jpg', 'slider image');
        $berita = Berita::create([
            'user_uuid' => $user->uuid,
            'category_uuid' => $category->uuid,
            'title' => 'Berita Arsip Uji ' . uniqid(),
            'slug' => 'berita-arsip-uji-' . uniqid(),
            'content' => '<p>Isi berita</p>',
            'featured_image' => 'berita/featured.jpg',
            'status' => 'draft',
            'search_engine' => 'index',
        ]);
        $image = BeritaImage::create([
            'berita_uuid' => $berita->uuid,
            'image_path' => 'berita/images/slider.jpg',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($user)->delete(route('beritas.destroy', $berita->uuid));

        $response->assertRedirect();
        $this->assertSoftDeleted('beritas', ['uuid' => $berita->uuid]);
        $this->assertDatabaseHas('berita_images', ['uuid' => $image->uuid]);
        Storage::disk('public')->assertExists('berita/featured.jpg');
        Storage::disk('public')->assertExists('berita/images/slider.jpg');
    }

    public function test_pending_berita_notifies_admin_and_super_admin(): void
    {
        Notification::fake();
        $author = $this->userWithBeritaAccess();
        $admin = $this->adminWithBeritaApprovalAccess();
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']));
        $category = Category::create([
            'name' => 'Berita Notifikasi',
            'slug' => 'berita-notifikasi-' . uniqid(),
        ]);

        $this->actingAs($author)->post(route('beritas.store'), [
            'title' => 'Pengajuan Berita ' . uniqid(),
            'category_uuid' => $category->uuid,
            'content' => '<p>Isi berita</p>',
            'status' => 'published',
            'search_engine' => 'index',
        ])->assertRedirect(route('beritas.index'));

        Notification::assertSentTo($admin, BeritaSubmittedNotification::class);
        Notification::assertSentTo($superAdmin, BeritaSubmittedNotification::class);
    }

    public function test_admin_can_approve_pending_berita_and_author_is_notified(): void
    {
        Notification::fake();
        $admin = $this->adminWithBeritaApprovalAccess();
        $author = User::factory()->create();
        $category = Category::create([
            'name' => 'Berita Approval',
            'slug' => 'berita-approval-' . uniqid(),
        ]);
        $berita = Berita::create([
            'user_uuid' => $author->uuid,
            'category_uuid' => $category->uuid,
            'title' => 'Berita Approval Uji ' . uniqid(),
            'slug' => 'berita-approval-uji-' . uniqid(),
            'content' => '<p>Isi berita</p>',
            'status' => 'pending',
            'search_engine' => 'index',
        ]);

        $this->actingAs($admin)
            ->post(route('beritas.approve', $berita->uuid))
            ->assertRedirect();

        $this->assertDatabaseHas('beritas', ['uuid' => $berita->uuid, 'status' => 'published']);
        Notification::assertSentTo($author, BeritaReviewNotification::class);
    }

    public function test_admin_can_return_pending_berita_to_draft_with_reason(): void
    {
        Notification::fake();
        $admin = $this->adminWithBeritaApprovalAccess();
        $author = User::factory()->create();
        $category = Category::create([
            'name' => 'Berita Return',
            'slug' => 'berita-return-' . uniqid(),
        ]);
        $berita = Berita::create([
            'user_uuid' => $author->uuid,
            'category_uuid' => $category->uuid,
            'title' => 'Berita Return Uji ' . uniqid(),
            'slug' => 'berita-return-uji-' . uniqid(),
            'content' => '<p>Isi berita</p>',
            'status' => 'pending',
            'search_engine' => 'index',
        ]);

        $this->actingAs($admin)
            ->post(route('beritas.reject', $berita->uuid), ['catatan' => 'Mohon lengkapi sumber berita.'])
            ->assertRedirect();

        $this->assertDatabaseHas('beritas', ['uuid' => $berita->uuid, 'status' => 'draft']);
        Notification::assertSentTo($author, BeritaReviewNotification::class);
    }

    public function test_admin_can_request_revision_without_changing_pending_status(): void
    {
        Notification::fake();
        $admin = $this->adminWithBeritaApprovalAccess();
        $author = User::factory()->create();
        $category = Category::create([
            'name' => 'Berita Revision',
            'slug' => 'berita-revision-' . uniqid(),
        ]);
        $berita = Berita::create([
            'user_uuid' => $author->uuid,
            'category_uuid' => $category->uuid,
            'title' => 'Berita Revision Uji ' . uniqid(),
            'slug' => 'berita-revision-uji-' . uniqid(),
            'content' => '<p>Isi berita</p>',
            'status' => 'pending',
            'search_engine' => 'index',
        ]);

        $this->actingAs($admin)
            ->post(route('beritas.mintaRevisi', $berita->uuid), ['catatan' => 'Mohon perbaiki judul.'])
            ->assertRedirect();

        $this->assertDatabaseHas('beritas', ['uuid' => $berita->uuid, 'status' => 'pending']);
        Notification::assertSentTo($author, BeritaReviewNotification::class);
    }
}
