<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $compression = env('RESPONSE_COMPRESSION');
        if ($compression) {
            $response->header('Content-Encoding', $compression);
            $response->header('Vary', 'Accept-Encoding');
        }

        return $response;
    }
}
