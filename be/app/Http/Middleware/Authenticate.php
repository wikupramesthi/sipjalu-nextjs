<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Route login adalah auth/login (bukan /login) dan app di-serve
        // dari subpath /be/, jadi pakai named route agar URL benar.
        return $request->expectsJson() ? null : route('login');
    }
}
