<?php

namespace App\Models;

use Cache;
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

    protected static function booted()
    {
        static::saved(fn ($model) => $model->flushCache());
        static::deleted(fn ($model) => $model->flushCache());
    }

    public function reports(): BelongsToMany
    {
        return $this->belongsToMany(Report::class);
    }

    protected function color(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => '#' . $value,
            set: fn (string $value) => trim($value, ' #'),
        );
    }

    /**
     * Frequently we need all the tags, this saves us some queries across multiple users
     *
     * @return string[]
     */
    public static function allNames()
    {
        return Cache::remember('tagNames', 3600, fn () => Tag::select('name')->get()->pluck('name'));
    }

    private function flushCache(): void
    {
        Cache::forget('tagNames');
    }
}
