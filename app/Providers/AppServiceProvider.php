<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use App\Policies\LikePolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootAuth();
        $this->bootSocial();
        $this->bootRoute();
    }

    public function bootAuth(): void
    {

        if (
            ! $this->app->environment('deployment')
            && cache()->rememberForever('hasPermissions', fn () => Schema::hasTable('permissions'))
        ) {
            // Get all the permissions
            $permissions = cache()->remember('permissions', 3600, fn () => Permission::with('roles')->get());
            // Dynamically register permissions with Laravel's Gate
            foreach ($permissions as $permission) {
                Gate::define($permission->name, function (User $user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        }

        Gate::define('like', [LikePolicy::class, 'like']);
        Gate::define('unlike', [LikePolicy::class, 'unlike']);

        Password::defaults(function () {
            $rule = Password::min(9);

            return $this->app->isProduction()
                ? $rule->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                : $rule;
        });
    }

    public function bootSocial(): void
    {
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('discord', \SocialiteProviders\Discord\Provider::class);
        });
    }

    public function bootRoute(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('oauth:discovery', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

        RateLimiter::for('oauth:token', function (Request $request) {
            return [
                Limit::perMinute(10)->by($request->ip()),
                Limit::perMinute(20)->by($request->input('client_id', 'unknown')),
            ];
        });

        RateLimiter::for('oauth:authorize', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('oauth:userinfo', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
