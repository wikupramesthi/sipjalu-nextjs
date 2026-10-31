<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Public API Key
    |--------------------------------------------------------------------------
    |
    | Kunci untuk endpoint publik SPA, dikirim FE melalui header `X-Api-Key`.
    | Isi env PUBLIC_API_KEY (di produksi) dengan nilai acak, mis.:
    |     php -r "echo bin2hex(random_bytes(32));"
    |
    | Kosong = middleware `public.key` meloloskan semua (cocok untuk lokal).
    |
    */

    'public_key' => env('PUBLIC_API_KEY', ''),
];
