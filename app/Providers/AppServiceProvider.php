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
            // Schema availability can change after migrations or between database connections.
            && Schema::hasTable('permissions')
        ) {
            // Get all the permissions
            // Cache scalar data, compatible with Laravel 13's restricted unserialization.
            $permissions = cache()->remember('permissions', 3600, fn () => Permission::with('roles')->get()
                ->mapWithKeys(fn (Permission $permission) => [$permission->name => $permission->roles->modelKeys()])
                ->all());
            // Dynamically register permissions with Laravel's Gate
            foreach ($permissions as $name => $roleIds) {
                Gate::define($name, function (User $user) use ($roleIds) {
                    return $user->roles->pluck('id')->intersect($roleIds)->isNotEmpty();
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
            // client_id is public and untrusted until its secret is verified.
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('oauth:authorize', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('oauth:userinfo', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
