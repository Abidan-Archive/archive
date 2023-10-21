<?php

namespace App\Http\Controllers;

use App\Models\Source;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except('show');
    }

    public function show(Event $event, Source $source): Response {
        dd($source);
        // return response()->file('storage/sources/Bloodline_Release_Part_1.mp3');
        return response()->file('storage/sources/3_3_YZTShKdDHOrptCtEZmPplZASRMYYk4gRBqBnSAME.m4a'));
    }

    public function destroy(Event $event, Source $source): RedirectResponse {
        dd($source);
        return to_route('event.show', $event);
    }
}
