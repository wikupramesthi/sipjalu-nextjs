<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BeritaLiveEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_all_returns_published_local_posts_in_bekasi_format(): void
    {
        config(['api.public_key' => '']);
        $category = Category::create([
            'name' => 'Pemerintahan',
            'slug' => 'pemerintahan',
        ]);

        $berita = Berita::create([
            'category_uuid' => $category->uuid,
            'title' => 'Berita Lokal',
            'slug' => 'berita-lokal',
            'content' => '<p>Isi dari database lokal</p>',
            'featured_image' => 'berita/berita-lokal.jpg',
            'scheduled_at' => now()->subDay(),
            'tagging' => 'Pemkot, Pemerintahan',
            'views' => 12,
            'is_popular' => true,
            'is_featured' => true,
            'status' => 'published',
        ]);

        Berita::create([
            'category_uuid' => $category->uuid,
            'title' => 'Berita Draft',
            'slug' => 'berita-draft',
            'content' => 'Tidak boleh tampil',
            'status' => 'draft',
        ]);

        Berita::create([
            'category_uuid' => $category->uuid,
            'title' => 'Berita Terjadwal',
            'slug' => 'berita-terjadwal',
            'content' => 'Belum boleh tampil',
            'scheduled_at' => now()->addDay(),
            'status' => 'published',
        ]);

        $this->getJson('/api/post/all?X-Api-Key=client-key')
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.post.0.id_post', $berita->uuid)
            ->assertJsonPath('data.post.0.seotitle', 'berita-lokal')
            ->assertJsonPath('data.post.0.title', 'Berita Lokal')
            ->assertJsonPath('data.post.0.title_category', 'Pemerintahan')
            ->assertJsonPath('data.post.0.id_category', $category->uuid)
            ->assertJsonPath('data.post.0.tag', 'Pemkot, Pemerintahan')
            ->assertJsonPath('data.post.0.hits', 12)
            ->assertJsonPath('data.post.0.is_popular', true)
            ->assertJsonPath('data.post.0.is_featured', true)
            ->assertJsonPath('data.post.0.status', 'published')
            ->assertJsonMissingPath('data.post.0.is_pupuler')
            ->assertJsonCount(1, 'data.post');
    }

    public function test_berita_live_returns_database_posts_for_the_frontend(): void
    {
        config(['api.public_key' => '']);
        $category = Category::create([
            'name' => 'Kegiatan',
            'slug' => 'kegiatan',
        ]);

        Berita::create([
            'category_uuid' => $category->uuid,
            'title' => 'Berita Frontend',
            'slug' => 'berita-frontend',
            'content' => 'Isi berita',
            'tagging' => 'Kota, Acara',
            'is_popular' => true,
            'is_featured' => true,
            'status' => 'published',
        ]);

        $this->getJson('/api/berita-live')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'berita-frontend')
            ->assertJsonPath('data.0.tags.0', 'Kota')
            ->assertJsonPath('data.0.tags.1', 'Acara')
            ->assertJsonPath('data.0.is_popular', true)
            ->assertJsonPath('data.0.is_featured', true)
            ->assertJsonPath('data.0.status', 'published')
            ->assertJsonMissingPath('data.0.content')
            ->assertJsonMissingPath('data.0.is_pupuler');
    }

    public function test_berita_live_can_filter_featured_posts_and_apply_a_limit(): void
    {
        config(['api.public_key' => '']);
        $category = Category::create(['name' => 'Kegiatan', 'slug' => 'kegiatan']);

        foreach (range(1, 6) as $number) {
            Berita::create([
                'category_uuid' => $category->uuid,
                'title' => "Berita Featured {$number}",
                'slug' => "berita-featured-{$number}",
                'content' => 'Isi berita',
                'excerpt' => 'Ringkasan berita',
                'featured_image' => "berita/featured-{$number}.jpg",
                'is_featured' => true,
                'status' => 'published',
            ]);
        }

        Berita::create([
            'category_uuid' => $category->uuid,
            'title' => 'Berita Biasa',
            'slug' => 'berita-biasa',
            'content' => 'Isi berita biasa',
            'is_featured' => false,
            'status' => 'published',
            'created_at' => now()->addMinute(),
            'updated_at' => now()->addMinute(),
        ]);

        $this->getJson('/api/berita-live?featured=1&limit=5')
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('data.0.is_featured', true)
            ->assertJsonPath('data.0.excerpt', 'Ringkasan berita')
            ->assertJsonMissing(['slug' => 'berita-biasa']);
    }

    public function test_post_detail_returns_a_published_local_post_by_slug(): void
    {
        config(['api.public_key' => '']);
        $category = Category::create(['name' => 'Kegiatan', 'slug' => 'kegiatan']);
        Berita::create([
            'category_uuid' => $category->uuid,
            'title' => 'Berita Detail',
            'slug' => 'berita-detail',
            'content' => 'Isi lengkap',
            'status' => 'published',
        ]);

        $this->getJson('/api/post/detail/berita-detail')
            ->assertOk()
            ->assertJsonPath('data.post.seotitle', 'berita-detail')
            ->assertJsonPath('data.post.content', 'Isi lengkap');

        $this->getJson('/api/post/tidak-ada')->assertNotFound();
    }

    public function test_post_category_returns_only_published_posts_in_that_category(): void
    {
        config(['api.public_key' => '']);
        $category = Category::create(['name' => 'Pendidikan', 'slug' => 'pendidikan']);
        $otherCategory = Category::create(['name' => 'Kesehatan', 'slug' => 'kesehatan']);

        Berita::create([
            'category_uuid' => $category->uuid,
            'title' => 'Berita Pendidikan',
            'slug' => 'berita-pendidikan',
            'content' => 'Isi pendidikan',
            'status' => 'published',
        ]);
        Berita::create([
            'category_uuid' => $otherCategory->uuid,
            'title' => 'Berita Kesehatan',
            'slug' => 'berita-kesehatan',
            'content' => 'Isi kesehatan',
            'status' => 'published',
        ]);

        $this->getJson('/api/post/category/pendidikan')
            ->assertOk()
            ->assertJsonPath('data.category.slug', 'pendidikan')
            ->assertJsonPath('data.post.0.seotitle', 'berita-pendidikan')
            ->assertJsonCount(1, 'data.post');
    }

    public function test_post_search_finds_published_posts_and_accepts_keyword_alias(): void
    {
        config(['api.public_key' => '']);
        $category = Category::create(['name' => 'Pemerintahan', 'slug' => 'pemerintahan']);
        Berita::create([
            'category_uuid' => $category->uuid,
            'title' => 'Kegiatan Wali Kota',
            'slug' => 'kegiatan-wali-kota',
            'content' => 'Isi kegiatan',
            'status' => 'published',
        ]);
        Berita::create([
            'category_uuid' => $category->uuid,
            'title' => 'Draft Rahasia',
            'slug' => 'draft-rahasia',
            'content' => 'Kegiatan Wali Kota',
            'status' => 'draft',
        ]);

        $this->getJson('/api/post/search?q=wali')
            ->assertOk()
            ->assertJsonPath('data.keyword', 'wali')
            ->assertJsonPath('data.pagination.current_page', 1)
            ->assertJsonPath('data.pagination.has_more', false)
            ->assertJsonPath('data.post.0.seotitle', 'kegiatan-wali-kota')
            ->assertJsonCount(1, 'data.post');

        $this->getJson('/api/post/search?keyword=kegiatan')
            ->assertOk()
            ->assertJsonCount(1, 'data.post');

        $this->getJson('/api/post/search')->assertUnprocessable();
    }

    public function test_post_search_paginates_without_returning_full_article_content(): void
    {
        config(['api.public_key' => '']);
        $category = Category::create(['name' => 'Pemerintahan', 'slug' => 'pemerintahan']);

        foreach (range(1, 13) as $number) {
            Berita::create([
                'category_uuid' => $category->uuid,
                'title' => "Tri berita {$number}",
                'slug' => "tri-berita-{$number}",
                'content' => str_repeat('Isi berita panjang. ', 1000),
                'status' => 'published',
            ]);
        }

        $firstPage = $this->getJson('/api/post/search?q=tri&limit=12')
            ->assertOk()
            ->assertJsonCount(12, 'data.post')
            ->assertJsonPath('data.pagination.current_page', 1)
            ->assertJsonPath('data.pagination.per_page', 12)
            ->assertJsonPath('data.pagination.from', 1)
            ->assertJsonPath('data.pagination.to', 12)
            ->assertJsonPath('data.pagination.has_more', true)
            ->assertJsonMissingPath('data.post.0.content');

        $this->assertLessThan(10_000, strlen($firstPage->getContent()));

        $this->getJson('/api/post/search?q=tri&limit=12&page=2')
            ->assertOk()
            ->assertJsonCount(1, 'data.post')
            ->assertJsonPath('data.pagination.current_page', 2)
            ->assertJsonPath('data.pagination.from', 13)
            ->assertJsonPath('data.pagination.to', 13)
            ->assertJsonPath('data.pagination.has_more', false);
    }
}
