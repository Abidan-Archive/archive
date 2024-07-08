<?php

namespace App\Models\Concerns;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

trait HasRoles
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    /**
     * A user may have multiple roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * A user may have multiple permissions.
     */
    public function permissions(): HasManyDeep
    {
        return $this->hasManyDeep(Permission::class, ['role_user', Role::class, 'permission_role']);
    }

    /**
     * Assign the given role to the user
     */
    public function assignRole(string $roleName): mixed
    {
        return $this->roles()->attach(
            Role::whereName($roleName)->firstOrFail()
        );
    }

    /**
     * Assign the given role to the user
     */
    public function unassignRole(string $roleName): mixed
    {
        return $this->roles()->detach(
            Role::whereName($roleName)->firstOrFail()
        );
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  mixed  $role
     */
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return (bool) $role->intersect($this->roles)->count();
    }

    /**
     * Determine if the user may perform the given permission.
     */
    public function hasPermission(Permission $permission): bool
    {
        return $this->hasRole($permission->roles);
    }
}
