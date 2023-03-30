<?php

namespace App\Providers;

use Schema;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Event' => 'App\Policies\EventPolicy',
        'App\Models\Report' => 'App\Policies\ReportPolicy',
        'App\Models\Tag' => 'App\Policies\TagPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void {
        if (Schema::hasTable('permissions')) {
            // Get all the permissions
            $permissions = Permission::with('roles')->get();
            // Dynamically register permissions with Laravel's Gate
            foreach ($permissions as $permission) {
                Gate::define($permission->name, function(User $user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        }
    }

}
