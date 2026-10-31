<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Brute-force protection
    |--------------------------------------------------------------------------
    |
    | Setelah jumlah percobaan login gagal melewati batas di bawah ini dalam
    | rentang waktu tertentu, alamat IP dan/atau email akan diblokir sementara.
    |
    */
    'brute_force' => [
        'max_attempts' => (int) env('SECURITY_MAX_LOGIN_ATTEMPTS', 3),
        'decay_minutes' => (int) env('SECURITY_LOGIN_DECAY_MINUTES', 10),
        'lockout_minutes' => (int) env('SECURITY_LOGIN_LOCKOUT_MINUTES', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Anomaly detection
    |--------------------------------------------------------------------------
    |
    | Ambang batas untuk menandai aktivitas login yang mencurigakan pada
    | halaman Login Activity dan Failed Login.
    |
    */
    'anomaly' => [
        'window_hours' => (int) env('SECURITY_ANOMALY_WINDOW_HOURS', 24),
        'distinct_ip_threshold' => (int) env('SECURITY_ANOMALY_DISTINCT_IPS', 3),
        'failed_threshold' => (int) env('SECURITY_ANOMALY_FAILED_ATTEMPTS', 5),
        'off_hours' => [0, 5],
    ],

];
