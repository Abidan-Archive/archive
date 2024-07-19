<?php

namespace App\Http\Middleware;

use App\Models\Ip;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBansRejectAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = Ip::first(['ip', $request->ip()]);
        if ($ip != null && $ip->bans()->where('expires', '>', now())->exists()) {
            abort(403);
        }

        $user = $request->user();
        if ($user != null && $user->bans()->where('expires', '>', now())->exists()) {
            abort(403);
        }

        return $next($request);
    }
}
