<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = ['date' => 'date'];

    protected $fillable = ['name', 'date', 'location'];

    public function reports() {
        return $this->hasMany(Report::class);
    }

    public function sources() {
        return $this->hasMany(Source::class);
    }

    public function isLocationLink() {
        return filter_var($this->location, FILTER_VALIDATE_URL);
    }
}
