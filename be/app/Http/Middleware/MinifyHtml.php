<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class MinifyHtml
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Hanya proses HTML response
        if ($response instanceof Response && str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $output = $response->getContent();

            // Hapus whitespace dan line break
            $output = preg_replace([
                '/<!--(?!\[if).*?-->/',     // hapus HTML comment kecuali IE conditional
                '/\>[^\S ]+/s',             // hapus whitespace setelah tag
                '/[^\S ]+\</s',             // hapus whitespace sebelum tag
                '/(\s)+/s'                  // multiple whitespace
            ], [
                '',
                '>',
                '<',
                '\\1'
            ], $output);

            $response->setContent($output);
        }

        return $response;
    }
}
