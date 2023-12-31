<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStubRequest;
use App\Models\Event;
use App\Models\Source;
use App\Models\Stub;
use Inertia\Response;

class StubController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->authorizeResource(Stub::class, 'stub');
    }

    public function index(Event $event, Source $source): Response {
        return null;
    }

    /**
     * Show the all the stubs regardless of parent
     */
    public function all(): Response {
        $stubs = Stub::all()->load(['event', 'source']);
        return inertia('Stub', compact('stubs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event, Source $source): Response
    {
        return inertia(
            'Event/Stub/Create',
            [
                'event' => $event->only('id', 'name'),
                'source' => $source
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStubRequest $request)
    {
        // $validated = $request->validated();
        // foreach(['stubs'] as $stub) {
        //
        // }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stub $stub)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stub $stub)
    {
        //
    }
}
