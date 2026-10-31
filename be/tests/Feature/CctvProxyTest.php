<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CctvProxyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_list_uses_relative_stream_urls(): void
    {
        Http::fake([
            'https://eofficev2.bekasikota.go.id/backupcctv/m3/list.json' => Http::response([
                ['path' => 'Kamera_1.m3u8', 'name' => 'Kamera_1', 'pic' => 'https://example.test/1.jpg'],
                ['path' => '../evil.txt', 'name' => 'Evil'],
            ]),
        ]);

        $this->getJson('/api/cctv')
            ->assertOk()
            ->assertJsonPath('data.0.stream', '/api/cctv/stream/Kamera_1.m3u8')
            ->assertJsonCount(1, 'data');
    }

    public function test_playlist_is_rewritten_to_relative_segment_proxy(): void
    {
        Http::fake([
            'https://eofficev2.bekasikota.go.id/backupcctv/m3/Kamera_1.m3u8' => Http::response(
                "#EXTM3U\n#EXT-X-VERSION:3\n#EXTINF:3.0,\nseg_1.ts\n#EXTINF:3.0,\nhttps://cdn.example.test/seg_2.ts\n"
            ),
        ]);

        $response = $this->get('/api/cctv/stream/Kamera_1.m3u8');

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/vnd.apple.mpegurl')
            ->assertSee('/api/cctv/seg/seg_1.ts', false)
            ->assertSee('https://cdn.example.test/seg_2.ts', false);
    }

    public function test_segment_is_proxied_as_video(): void
    {
        Http::fake([
            'https://eofficev2.bekasikota.go.id/backupcctv/m3/seg_1.ts' => Http::response('video-bytes'),
        ]);

        $this->get('/api/cctv/seg/seg_1.ts')
            ->assertOk()
            ->assertHeader('Content-Type', 'video/MP2T')
            ->assertSee('video-bytes', false);
    }

    public function test_invalid_camera_name_is_rejected(): void
    {
        $this->get('/api/cctv/stream/..%2F..%2Fetc%2Fpasswd')->assertNotFound();
        $this->get('/api/cctv/seg/evil.php')->assertNotFound();
    }
}
