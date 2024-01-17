<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Source;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SourceController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->authorizeResource(Source::class, 'source');
    }

    public function show(Event $event, Source $source): BinaryFileResponse {
        // The file pathing on this stuff makes no sense
        // For this response we need to tell it that it's also in the storage path under public
        // We have to serve this so we have the header accept-ranges=bytes set or we can't skip through file
        return response()->file('storage/'.Source::DIRECTORY.'/'.$source->filename);
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
