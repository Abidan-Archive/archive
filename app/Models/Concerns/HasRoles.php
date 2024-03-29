<?php

namespace App\Models\Concerns;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles {

    /**
     * A user may have multiple roles.
     */
    public function roles(): BelongsToMany {
        return $this->belongsToMany(Role::class);
    }


    /**
     * Assign the given role to the user
     */
    public function assignRole(string $roleName): mixed {
        return $this->roles()->attach(
            Role::whereName($roleName)->firstOrFail()
        );
    }

    /**
     * Assign the given role to the user
     */
    public function unassignRole(string $roleName): mixed {
        return $this->roles()->detach(
            Role::whereName($roleName)->firstOrFail()
        );
    }

    /**
     * Determine if the user has the given role.
     *
     * @param mixed $role
     * @return boolean
     */
    public function hasRole($role): bool {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return !! $role->intersect($this->roles)->count();
    }

    /**
      * Determine if the user may perform the given permission.
      */
    public function hasPermission(Permission $permission): bool {
        return $this->hasRole($permission->roles);
    }
}
