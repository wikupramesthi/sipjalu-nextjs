<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ApiAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['api_access.trusted_proxies' => ['127.0.0.1']]);

        Route::middleware('api')->get('/api/__test/ping', fn () => response()->json([
            'status' => 'success',
            'message' => 'ok',
            'data' => ['pong' => true],
        ]));

        Route::middleware('api')->get('/api/v1/__test/ping', fn () => response()->json([
            'status' => 'success',
            'message' => 'ok',
            'data' => [],
        ]));

        Route::middleware('api')->get('/api/__test/failure', function () {
            throw new \RuntimeException('API request failed');
        });

        Route::middleware(['api', 'public.key'])->get('/api/__test/keyed', fn () => response()->json([
            'status' => 'success',
            'message' => 'ok',
            'data' => ['keyed' => true],
        ]));
    }

    public function test_api_is_reachable_from_an_explicitly_trusted_proxy(): void
    {
        $this->getJson('/api/__test/ping')
            ->assertOk()
            ->assertJsonPath('status', 'success');
    }

    public function test_api_exceptions_are_rendered_as_json_without_an_accept_header(): void
    {
        $this->get('/api/__test/failure')
            ->assertInternalServerError()
            ->assertJsonStructure(['message']);
    }

    public function test_loopback_is_denied_when_private_peers_are_disabled(): void
    {
        config([
            'api_access.proxy_secret' => '',
            'api_access.trusted_proxies' => [],
            'api_access.trust_private_peers' => false,
        ]);

        $this->getJson('/api/__test/ping')->assertNotFound();
    }

    public function test_private_peer_is_trusted_by_default(): void
    {
        config([
            'api_access.proxy_secret' => '',
            'api_access.trusted_proxies' => [],
            'api_access.trust_private_peers' => true,
        ]);

        $this->getJson('/api/__test/ping')
            ->assertOk()
            ->assertJsonPath('status', 'success');
    }

    public function test_public_peer_cannot_call_api_directly(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->getJson('/api/__test/ping')
            ->assertNotFound()
            ->assertJsonPath('status', 'error');
    }

    public function test_spoofed_forwarded_for_does_not_bypass_the_check(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->withHeaders(['X-Forwarded-For' => '127.0.0.1'])
            ->getJson('/api/__test/ping')
            ->assertNotFound();
    }

    public function test_private_peer_is_trusted_even_when_proxy_secret_is_set(): void
    {
        config(['api_access.proxy_secret' => 'rahasia-proxy']);

        $this->getJson('/api/__test/ping')
            ->assertOk()
            ->assertJsonPath('status', 'success');
    }

    public function test_proxy_secret_allows_a_public_proxy(): void
    {
        config(['api_access.proxy_secret' => 'rahasia-proxy']);

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->getJson('/api/__test/ping')
            ->assertNotFound();

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->withHeaders(['X-API-Proxy' => 'salah'])
            ->getJson('/api/__test/ping')
            ->assertNotFound();

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->withHeaders(['X-API-Proxy' => 'rahasia-proxy'])
            ->getJson('/api/__test/ping')
            ->assertOk();
    }

    public function test_direct_client_prefixes_remain_accessible(): void
    {
        config(['api_access.proxy_secret' => 'rahasia-proxy']);

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->getJson('/api/v1/__test/ping')
            ->assertOk();
    }

    public function test_public_key_is_ignored_when_not_configured(): void
    {
        config(['api.public_key' => '']);

        $this->getJson('/api/__test/keyed')
            ->assertOk()
            ->assertJsonPath('data.keyed', true);
    }

    public function test_public_key_is_required_when_configured(): void
    {
        config(['api.public_key' => 'kunci-publik']);

        $this->getJson('/api/__test/keyed')
            ->assertForbidden()
            ->assertJsonPath('status', 'error');

        $this->withHeaders(['X-Api-Key' => 'salah'])
            ->getJson('/api/__test/keyed')
            ->assertForbidden();
    }

    public function test_public_key_accepts_a_matching_header(): void
    {
        config(['api.public_key' => 'kunci-publik']);

        $this->withHeaders(['X-Api-Key' => 'kunci-publik'])
            ->getJson('/api/__test/keyed')
            ->assertOk()
            ->assertJsonPath('data.keyed', true);

        $this->withHeaders(['X-Api-Key' => '  kunci-publik  '])
            ->getJson('/api/__test/keyed')
            ->assertOk();
    }

    public function test_public_key_does_not_apply_to_direct_client_prefixes(): void
    {
        config(['api.public_key' => 'kunci-publik']);

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->getJson('/api/v1/__test/ping')
            ->assertOk();
    }
}
