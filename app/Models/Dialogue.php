<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use League\CommonMark\CommonMarkConverter;

/**
 * App\Models\Dialogue
 *
 * @property int $id
 * @property string $speaker
 * @property string $line
 * @property int $order
 * @property int $report_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Report|null $report
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Dialogue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dialogue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dialogue query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dialogue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dialogue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dialogue whereLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dialogue whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dialogue whereReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dialogue whereSpeaker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dialogue whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 * @mixin IdeHelperDialogue
 */
class Dialogue extends Model
{
    use HasFactory;

    protected $appends = ['line_html'];

    protected $fillable = ['speaker', 'line', 'order'];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the html of the line in markdown format
     */
    protected function lineHtml(): Attribute
    {
        $converter = new CommonMarkConverter(config('commonmark'));

        return Attribute::make(
            get: fn ($value, $attributes) => $converter->convert($attributes['line'])->getContent()
        );
    }
}
