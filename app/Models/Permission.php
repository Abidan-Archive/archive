<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label'];

    /**
    * A permission can be applied to roles.
    */
    public function roles(): BelongsToMany {
        return $this->belongsToMany(Role::class);
    }
}
