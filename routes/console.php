<?php

use App\Models\Dialogue;
use App\Models\Event;
use App\Models\Report;
use App\Models\Tag;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('consume {jsonPath}', function (string $jsonPath) {
    $data = json_decode(file_get_contents($jsonPath), true);

    foreach ($data as $i => $e) {
        $event = Event::create($e);

        if ($i > 0) {
            $this->newLine();
        }
        $this->line("Consuming $event->name");
        $this->withProgressBar($e['reports'], function ($r) use ($event) {
            try {
                $report = $event->reports()->create($r);
                $report->refresh();

                foreach ($r['dialogues'] as $i => $d) {
                    $d['order'] = $i;
                    $report->dialogues()->create($d);
                }

                foreach ($r['tags'] as $t) {
                    $tag = Tag::firstOrCreate(
                        ['name' => $t],
                        ['color' => str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)]
                    );
                    $report->tags()->attach($tag);
                }
            } catch (Exception $e) {
                // $this->newLine();
                // $this->error('Error on '.$r['id']);
                // $this->newLine();
                throw $e;
            }
        });
    }
})->purpose('Import all the scraped web data');

Artisan::command('killReports', function () {
    // Clear out everything since we're testing
    Event::truncate();
    Report::truncate();
    Dialogue::truncate();
    Tag::truncate();
    $this->info('All dead :)');
})->purpose('Truncate all tables for scraped data import.');
