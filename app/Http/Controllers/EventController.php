<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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
        $events = Event::with('reports')->get();
        return inertia('Event/Index', compact('events'));
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
        $request->session()->flash('status', 'Event successfully created!');
        return to_route('event.update', compact('event'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     */
    public function show(Event $event): Response
    {
        $event->load('reports');
        return inertia('Event/Show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $event->sources;
        return inertia('Event/Edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
    
}
