<?php

namespace Tests\Feature;

use App\Models\WebsiteMenu;
use App\Models\WebsiteMenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_backend_redirect_follows_the_requested_domain(): void
    {
        $response = $this->get('http://domain-apa-saja.test/backend');

        $response->assertRedirect();
        $this->assertStringStartsWith(
            'http://domain-apa-saja.test/auth/login',
            (string) $response->headers->get('Location')
        );
    }

    public function test_https_forwarded_proto_is_honoured(): void
    {
        $response = $this->withServerVariables(['HTTP_X_FORWARDED_PROTO' => 'https'])
            ->get('http://domain-apa-saja.test/backend');

        $response->assertRedirect();
        $this->assertStringStartsWith(
            'https://domain-apa-saja.test/auth/login',
            (string) $response->headers->get('Location')
        );
    }

    public function test_menu_links_are_relative_for_the_active_domain(): void
    {
        $menu = WebsiteMenu::create([
            'name' => 'Header',
            'slug' => 'header-test',
            'location' => 'header',
            'status' => true,
            'position' => 1,
        ]);

        WebsiteMenuItem::create([
            'website_menu_id' => $menu->id,
            'name' => 'Beranda',
            'route' => 'home',
            'status' => true,
            'position' => 1,
        ]);

        WebsiteMenuItem::create([
            'website_menu_id' => $menu->id,
            'name' => 'Panel',
            'url' => 'https://domain-apa-saja.test/backend',
            'status' => true,
            'position' => 2,
        ]);

        WebsiteMenuItem::create([
            'website_menu_id' => $menu->id,
            'name' => 'Eksternal',
            'url' => 'https://contoh.go.id/halaman',
            'status' => true,
            'position' => 3,
        ]);

        $this->getJson('http://domain-apa-saja.test/api/menus')
            ->assertOk()
            ->assertJsonPath('data.0.items.0.link', '/')
            ->assertJsonPath('data.0.items.1.link', '/backend')
            ->assertJsonPath('data.0.items.2.link', 'https://contoh.go.id/halaman');
    }
}
