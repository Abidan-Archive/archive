<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Source;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;

class SourceController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->authorizeResource(Source::class, 'source');
    }

    public function update(Event $event, Source $source, Request $request): RedirectResponse {
        $validated = $request->validate([
            'name' => 'required'
        ]);
        $source->update($validated);

        return redirect()->back()->with('flash', ['type'=>'success', 'message'=> 'Successfully changed the name!']);
    }

    public function destroy(Event $event, Source $source): RedirectResponse {
        $source->delete();
        return redirect()->back()->with('flash', ['type'=>'success', 'message'=> 'Successfully deleted the name!']);
    }
}
