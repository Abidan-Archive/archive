<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Source;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class EventController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')
            ->except('index', 'show');
        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $events = Event::with('reports')
            ->paginate(20)
            ->through(fn ($event) => [
                'id' => $event->id,
                'name' => $event->name,
                'date' => $event->date,
                'location' => $event->location,
                'reports' => $event->reports->count(),
            ]);

        return inertia('Event/Index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('Event/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $event = Event::create($validated);
        foreach (($validated['sources'] ?? []) as $i => $file) {
            Source::createFromFile($event, $file, $i);
        }

        return to_route('event.update', compact('event'))->with('flash', ['message' => 'Event successfully created!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): Response
    {
        $reports = $event->reports()->paginate(20);

        return inertia('Event/Show', compact('event', 'reports'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): Response
    {
        $event->sources;

        return inertia('Event/Edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $validated = $request->validated();
        $event->update($validated);
        $max = $event->sources->max('id'); // Get max, not count
        foreach (($validated['sources'] ?? []) as $i => $file) {
            Source::createFromFile($event, $file, $max + 1 + $i);
        }

        return to_route('event.update', compact('event'))->with('flash', ['message' => 'Event successfully updated!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return to_route('event.index')->with('flash', ['message' => 'Event successfully deleted!']);
    }
}
