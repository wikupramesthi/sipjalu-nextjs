<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteIdentity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebsiteIdentityController extends Controller
{
    public function edit()
    {
        $identity = WebsiteIdentity::first();
        if (! $identity) {
            $identity = WebsiteIdentity::create([
                'site_name' => config('app.name', 'Pemerintah Kota Bekasi'),
                'site_title' => 'Pemerintah Kota Bekasi',
                'tagline' => 'Pemerintah Kota Bekasi',
            ]);
        }

        return view('pages.website-identity.edit', compact('identity'));
    }

    public function update(Request $request)
    {
        $identity = WebsiteIdentity::first();
        if (! $identity) {
            $identity = new WebsiteIdentity(['uuid' => (string) Str::uuid()]);
        }

        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_title' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:png,jpg,jpeg,ico,webp|max:1024',
            'og_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:3072',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
            'facebook_url' => 'nullable|url|max:500',
            'instagram_url' => 'nullable|url|max:500',
            'youtube_url' => 'nullable|url|max:500',
            'tiktok_url' => 'nullable|url|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'google_analytics_id' => 'nullable|string|max:100',
            'google_site_verification' => 'nullable|string|max:500',
            'hero_badge' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string|max:255',
            'hero_title_highlight' => 'nullable|string|max:255',
            'hero_lead' => 'nullable|string|max:2000',
            'hero_cta_label' => 'nullable|string|max:255',
            'hero_cta_url' => 'nullable|string|max:500',
            'hero_countdown_label' => 'nullable|string|max:255',
            'hero_countdown_target' => 'nullable|date',
            'hero_background' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:3072',
        ], [
            'site_name.required' => 'Nama situs wajib diisi.',
            'site_title.required' => 'Judul situs wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'facebook_url.url' => 'URL Facebook tidak valid.',
            'instagram_url.url' => 'URL Instagram tidak valid.',
            'youtube_url.url' => 'URL Youtube tidak valid.',
            'tiktok_url.url' => 'URL Tiktok tidak valid.',
        ]);

        $data = collect($validated)->except(['logo', 'favicon', 'og_image', 'hero_background'])->toArray();

        foreach (['logo', 'favicon', 'og_image', 'hero_background'] as $field) {
            if ($request->hasFile($field)) {
                if ($identity->getAttribute($field) && Storage::disk('public')->exists($identity->getAttribute($field))) {
                    Storage::disk('public')->delete($identity->getAttribute($field));
                }
                $data[$field] = $request->file($field)->store('website-identity', 'public');
            }
        }

        // handle remove flags
        foreach (['logo', 'favicon', 'og_image', 'hero_background'] as $field) {
            if ($request->input('remove_' . $field) == '1' && empty($data[$field])) {
                if ($identity->getAttribute($field) && Storage::disk('public')->exists($identity->getAttribute($field))) {
                    Storage::disk('public')->delete($identity->getAttribute($field));
                }
                $data[$field] = null;
            }
        }

        $identity->fill($data);

        if (! $identity->exists) {
            $identity->uuid = $identity->uuid ?? (string) Str::uuid();
            $identity->save();
        } else {
            $identity->save();
        }

        return redirect()->route('website-identity.edit')->with('success', 'Identitas website berhasil diperbarui.');
    }
}
