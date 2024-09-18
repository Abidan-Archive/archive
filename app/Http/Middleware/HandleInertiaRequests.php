<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'flash' => fn () => $request->session()->get('flash'),
            'auth.user' => fn () => $request->user()
            ? [
                ...$request->user()->only('id', 'username', 'email', 'is_sso'),
                'roles' => $request->user()->roles->pluck('name'),
                'permissions' => $request->user()->permissions->pluck('name'),
            ] :
            null,
        ]);
    }
}
