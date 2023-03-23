<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Report;
use App\Inspiring;

use Inertia\Response;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(): Response {
        $events = Event::select('id', 'name')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();
        $quote = Inspiring::quote();
        return inertia('Home', compact('events', 'quote'));
    }

    public function search(Request $request): Response {
        $reports = Report::search(query: trim($request->input('q')) ?? '')->get();
        return inertia('Search', compact('reports'));
    }

}
