<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperTag
 */
class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'color'];

    protected $aapends = ['color'];

    public function reports(): BelongsToMany
    {
        return $this->belongsToMany(Report::class);
    }

    protected function color(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => '#'.$value,
            set: fn (string $value) => trim($value, ' #'),
        );
    }
}
