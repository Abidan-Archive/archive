<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStubRequest;
use App\Jobs\CreateStubFileJob;
use App\Models\Event;
use App\Models\Source;
use App\Models\Stub;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class StubController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Stub::class, 'stub');
    }

    /**
     * Show the all the stubs regardless of parent
     */
    public function index(): Response
    {
        $stubs = Stub::with('source.event')->orderBy('created_at')->paginate(20);

        return inertia('Stub', compact('stubs'));
    }

    /**
     * Show the form for creating a new resource.
     * aka audio scrub
     */
    public function create(Event $event, Source $source): Response
    {
        $source->load('stubs');

        return inertia(
            'Event/Source/Scrub',
            [
                'event' => $event->only('id', 'name'),
                'source' => $source,
            ]);
    }

    /**
     * Store a newly created stub resource in storage.
     */
    public function store(Event $event, Source $source, StoreStubRequest $request): RedirectResponse
    {

        $stubs = collect($request->stubs)->map(fn (array $stub, int $idx) => $stub += ['id' => $idx, 'filename' => null]);
        $source->stubs()->delete(); // We're mass replacing all the stubs, this is expensive since they all run ffmpeg
        $source->stubs()->createMany($stubs);
        foreach ($source->stubs as $stub) {
            CreateStubFileJob::dispatch($stub);
        }

        return redirect()->back()->with('flash', [
            'message' => 'Created '.$stubs->count().' stubs successfully! Processing audio files in background.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stub $stub): RedirectResponse
    {
        $stub->delete();

        return redirect()->back()->with('flash', ['message' => 'Destroyed stub successfully!']);
    }
}
