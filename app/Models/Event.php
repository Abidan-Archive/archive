<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Check if deleted_at is auto cast to Carbon date
    protected $dates = ['date'];
    protected $fillable = ['name', 'date', 'location'];

    public function reports() {
        return $this->hasMany(Report::class);
    }

    public function sources() {
        return $this->hasMany(Sources::class);
    }
}
