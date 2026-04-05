<?php

namespace App\Jobs;

use App\Models\Source;
use App\Models\Stub;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CreateStubFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $stub;

    /**
     * Create a new job instance.
     */
    public function __construct(Stub $stub)
    {
        $this->stub = $stub;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Log::info('Creating Stub', ['stub' => $this->stub->id, 'from' => $this->stub->from, 'to' => $this->stub->to, 'source' => $this->stub->source->toArray()]);

        $input = Storage::disk('public')->path(Source::DIRECTORY).'/'.$this->stub->source->filename;
        // stub_<source_id>_<stub_id>_<random_hash>.<ext>
        $filename = implode('_', ['stub', $this->stub->source->id, $this->stub->id, Str::random(40)]).'.'.pathinfo($input, PATHINFO_EXTENSION);
        $output = Storage::disk('public')->path(Stub::DIRECTORY).'/'.$filename;

        // Run ffmpeg to generate our stub audio
        $process = new Process(['ffmpeg', '-ss', $this->stub->from, '-t', $this->stub->to - $this->stub->from, '-i', $input, '-c', 'copy', $output]);
        $process->run();
        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->stub->filename = $filename;
        $this->stub->save();
    }
}
