<?php

namespace App\Models\Concerns;

trait ParsesUserAgent
{
    public function browser(): string
    {
        $ua = (string) $this->user_agent;

        $map = [
            'Edg/' => 'Microsoft Edge',
            'OPR/' => 'Opera',
            'Opera' => 'Opera',
            'SamsungBrowser' => 'Samsung Internet',
            'Firefox' => 'Mozilla Firefox',
            'CriOS' => 'Google Chrome',
            'Chrome' => 'Google Chrome',
            'Safari' => 'Safari',
            'MSIE' => 'Internet Explorer',
            'Trident' => 'Internet Explorer',
            'Postman' => 'Postman',
            'curl' => 'cURL',
        ];

        foreach ($map as $needle => $name) {
            if (stripos($ua, $needle) !== false) {
                return $name;
            }
        }

        return $ua === '' ? 'Tidak diketahui' : 'Lainnya';
    }

    public function platform(): string
    {
        $ua = (string) $this->user_agent;

        $map = [
            'Windows' => 'Windows',
            'Android' => 'Android',
            'iPhone' => 'iOS',
            'iPad' => 'iOS',
            'Macintosh' => 'macOS',
            'Mac OS X' => 'macOS',
            'Linux' => 'Linux',
        ];

        foreach ($map as $needle => $name) {
            if (stripos($ua, $needle) !== false) {
                return $name;
            }
        }

        return $ua === '' ? 'Tidak diketahui' : 'Lainnya';
    }

    public function deviceType(): string
    {
        $ua = (string) $this->user_agent;

        if (preg_match('/iPad|Tablet|PlayBook|Silk/i', $ua)) {
            return 'Tablet';
        }

        if (preg_match('/Mobile|Android|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i', $ua)) {
            return 'Mobile';
        }

        return $ua === '' ? 'Tidak diketahui' : 'Desktop';
    }
}
