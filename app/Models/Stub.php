<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * @mixin IdeHelperStub
 */
class Stub extends Model
{
    use HasFactory, \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    const DIRECTORY = 'stubs';

    /**
     * The calculated attributes that are appended to the model by default
     *
     * @var array<int, string>
     */
    protected $appends = ['audio_url'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id', 'prompt', 'from', 'to'];

    /**
     * Register methods to model hooks
     */
    protected static function booted(): void
    {
        // Delete associated files in storage when model deleted
        static::deleting(function (Stub $model) {
            Storage::delete(self::DIRECTORY.'/'.$model->filename);
        });
    }

    public function event(): HasOneDeep
    {
        // return $this->belongsToThrough(Event::class, Source::class);
        return $this->hasOneDeep(Event::class, [Source::class]);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function audioUrl(): Attribute
    {
        return Attribute::make(get: fn ($value, $attributes) => ($attributes['filename'] ?? null) !== null
            ? Storage::url(self::DIRECTORY.'/'.$attributes['filename'])
            : null);
    }

    private function createStubFile(): void
    {
        Log::info('Creating Stub', ['stub' => $this->id, 'from' => $this->from, 'to' => $this->to, 'source' => $this->source->toArray()]);
        // Generate the string params for ffmpeg
        $input = Storage::disk('public')->path(Source::DIRECTORY).'/'.$this->source->filename;
        // stub_<source_id>_<stub_id>_<random_hash>.<ext>
        $filename = implode('_', ['stub', $this->source->id, $this->id, Str::random(40)]).'.'.pathinfo($input, PATHINFO_EXTENSION);
        $output = Storage::disk('public')->path(self::DIRECTORY).'/'.$filename;

        // Run ffmpeg to generate our stub audio
        $process = new Process(['ffmpeg', '-ss', $this->from, '-t', $this->to - $this->from, '-i', $input, '-c', 'copy', $output]);
        $process->run();
        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Associate the stub audio to this model
        $this->filename = $filename;
        $this->save();
    }
}
