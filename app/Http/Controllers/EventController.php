<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Source;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class EventController extends Controller
{

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth')
             ->except('index', 'show');
        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response {
        [$events, $paginate] = Event::with('reports')->inertiaPaginate(20);

        return inertia('Event/Index', compact('events', 'paginate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response {
        return inertia('Event/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $event = Event::create($validated);
        foreach ($validated['sources'] as $file) {
            $source = $event->sources()->create(['name' => $file->getClientOriginalName()]);
            $source->update(['filename' => implode('_', [$event->id, $source->id, $file->hashName()])]);
            $file->storePubliclyAs(Source::DIRECTORY, $source->filename);
        }
        return to_route('event.update', compact('event'))->with('status', 'Event successfuly created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): Response
    {
        $event->load('reports');
        return inertia('Event/Show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
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

        return to_route('event.update', compact($event))->with('status', 'Event successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();
        session()->flash('status', 'Event successfully deleted!');
        return to_route('event.index');
    }

}
