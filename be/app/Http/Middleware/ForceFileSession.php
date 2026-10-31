<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceFileSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force file-based session driver before session starts
        config(['session.driver' => 'file']);
        
        // Debug: log the session driver
        \Log::info('ForceFileSession BEFORE: session.driver = ' . config('session.driver'));
        
        $response = $next($request);
        
        \Log::info('ForceFileSession AFTER: session.driver = ' . config('session.driver'));
        
        return $response;
    }
}