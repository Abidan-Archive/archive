<?php

namespace App\Http\Middleware;

use Barryvdh\Debugbar\Facades\Debugbar;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class AddContentSecurityPolicyHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Laravel debugbar thows tons of errors because of bug in nonce application
        // Just skip if we're local
        if (app()->isLocal()) return $next($request);

        $nonce = Vite::useCspNonce();
        return $next($request)->withHeaders([
            'Content-Security-Policy' => "script-src 'self' 'nonce-$nonce' https://challenges.cloudflare.com;"
        ]);
    }
}
