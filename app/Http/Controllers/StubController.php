<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStubRequest;
use App\Models\Event;
use App\Models\Source;
use App\Models\Stub;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class StubController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->authorizeResource(Stub::class, 'stub');
    }

    /**
     * Show the all the stubs regardless of parent
     */
    public function all(): Response {
        $stubs = Stub::with(['event', 'source'])->orderBy('created_at')->paginate(20);
        return inertia('Stub', compact('stubs'));
    }

    /**
    * Show the form to create a report from a stub
    * audio playback
    */
    public function show(Event $event, Source $source, Stub $stub): Response {
        $tags = Tag::select('id', 'name')->get();
        return inertia('Event/Source/Stub/Show', [
            'event' => $event->only('id', 'date'),
            'source' => $source->only('id', 'url'),
            'stub' => $stub,
            'tags' => $tags,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * aka audio scrubber
     */
    public function create(Event $event, Source $source): Response
    {
        $source->load('stubs');
        return inertia(
            'Event/Source/Stub/Create',
            [
                'event' => $event->only('id', 'name'),
                'source' => $source
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Event $event, Source $source, StoreStubRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $stubs = collect($validated['stubs'])->map(fn(array $stub, int $idx) => $stub += ['id' => $idx]);
        $source->stubs()->delete(); // We're mass replacing all the stubs, this is expensive since they all run ffmpeg
        $source->stubs()->createMany($stubs);

        return redirect()->back()->with('flash', ['message' => 'Created '.$stubs->count().' stubs successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stub $stub): RedirectResponse
    {
        //
    }
}
