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
        $nonce = Vite::useCspNonce();

        // Attach debugbar nonce
        if (app()->isLocal() && class_exists(Debugbar::class) && app()->bound('debugbar')) {
            app('debugbar')->getJavascriptRenderer()->setCspNonce($nonce);
        }

        return $next($request)->withHeaders([
            'Content-Security-Policy' => "script-src 'self' 'nonce-$nonce' https://challenges.cloudflare.com;"
        ]);

    }
}
