<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label'];

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Grant the given permission to a role.
     *
     * @return mixed
     */
    public function givePermissionTo(Permission $permission) {
        return $this->permissions()->save($permission);
    }
}
