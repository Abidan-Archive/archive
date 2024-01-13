<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            // Status is used in certain forms like login
            // Unsure if we should use it for toast messages
            // Those make more sense in flash to me..
            // Have both for now
            'status' => fn() => $request->session()->get('status'),
            'flash' => fn() => $request->session()->get('flash'),
            'auth.user' => fn() => $request->user()
            ? [
                    ...$request->user()->only('id', 'username', 'email'),
                    'roles' => $request->user()->roles->pluck('name')
            ] :
            null
            ,
        ]);
    }
}
