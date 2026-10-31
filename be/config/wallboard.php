<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Token Layar Wallboard TV
    |--------------------------------------------------------------------------
    |
    | Diakses via config(), bukan env() langsung, agar tetap terbaca saat
    | config:cache dipakai production. Token lewat query string tercatat di
    | log akses — untuk operasional harian prefer login user.
    |
    */

    'token' => env('WALLBOARD_TOKEN'),

];
