<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use App\Policies\LikePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Event::class => \App\Policies\EventPolicy::class,
        \App\Models\Report::class => \App\Policies\ReportPolicy::class,
        \App\Models\Tag::class => \App\Policies\TagPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void {
        $this->registerPolicies();

        if (!$this->app->environment('deployment') && Schema::hasTable('permissions')) {
            // Get all the permissions
            $permissions = Permission::with('roles')->get();
            // Dynamically register permissions with Laravel's Gate
            foreach ($permissions as $permission) {
                Gate::define($permission->name, function(User $user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        }

        Gate::define('like', [LikePolicy::class, 'like']);
        Gate::define('unlike', [LikePolicy::class, 'unlike']);

        Password::defaults(function() {
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

}
