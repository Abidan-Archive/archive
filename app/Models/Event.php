<?php

namespace App\Models;

use App\Models\Concerns\InertiaPaginate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperEvent
 */
class Event extends Model
{
    use HasFactory, InertiaPaginate, SoftDeletes;

    protected $casts = ['date' => 'date'];

    protected $fillable = ['name', 'date', 'location'];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function sources(): HasMany
    {
        return $this->hasMany(Source::class);
    }
}
