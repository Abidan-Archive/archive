<?php

namespace App\Http\Controllers;

use App\Models\Stub;
use App\Http\Requests\StoreStubRequest;
use Inertia\Response;

class StubController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('Event/Stub');
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
