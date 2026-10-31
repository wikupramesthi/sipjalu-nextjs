<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pembatasan akses /api
    |--------------------------------------------------------------------------
    |
    | Endpoint publik hanya boleh dipanggil lewat proxy frontend (nginx FE).
    | Bila `proxy_secret` diisi, proxy FE wajib mengirim header `X-API-Proxy`
    | dengan nilai yang sama. Bila kosong, akses hanya dibuka untuk IP peer
    | yang tercantum eksplisit di `trusted_proxies`; daftar kosong = tolak
    | semua request non-pengecualian (fail closed).
    |
    | Prefix di `exempt_prefixes` tetap dapat diakses langsung karena dipakai
    | klien lain (login Sanctum, web bekasikota.go.id, aplikasi OPD mobile).
    |
    */

    'proxy_secret' => (string) env('API_PROXY_SECRET', ''),

    'header' => 'X-API-Proxy',

    /*
    | Peer dari jaringan privat/loopback (RFC1918, ULA, link-local) dianggap
    | proxy internal secara default, bahkan bila proxy_secret diisi. Ini membuat
    | deployment Docker/Kubernetes jalan tanpa konfigurasi tambahan, sementara
    | akses langsung dari IP publik tetap ditolak. Set false untuk fail closed
    | mutlak (wajib isi proxy_secret atau API_ALLOWED_PROXY_IPS).
    */
    'trust_private_peers' => (bool) env('API_TRUST_PRIVATE_PEERS', true),

    'trusted_proxies' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('API_ALLOWED_PROXY_IPS', ''))
    ))),

    'exempt_prefixes' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env(
            'API_PROXY_EXEMPT_PREFIXES',
            'api/auth,api/user,api/v1,api/opd'
        ))
    ))),

];
