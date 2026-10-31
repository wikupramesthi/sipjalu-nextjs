<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Terima host apa pun (satu deployment melayani domain apa pun,
     * termasuk dev/staging/prod). Host/scheme aktual diatur ForceRootUrl.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        return ['*'];
    }
}
