<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\CommonMarkConverter;

class Dialogue extends Model
{
    use HasFactory;

    protected $fillable = ['speaker', 'line', 'order'];

    public function report() {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the html of the line in markdown format
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function lineHtml(): Attribute {
        $converter = new CommonMarkConverter(config('commonmark'));
        return Attribute::make(
            get: fn ($value, $attributes) => $converter->convert($attributes['line'])
        );
    }

}
