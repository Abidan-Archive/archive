<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Report;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(): Response {
        $events = Event::select('id', 'name')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Home', compact('events'));
    }

    public function search(Request $request): Response {
        $reports = Report::search(
            query: trim($request->input('q'))
            // query: trim($request->input('q')),
            // callback: function(Indexes $meilisearch, string $query, array $options) use ($request) {
                // if ($request->has(key: 'tags')) {
                    // $options['filter'] = "tags = {$request->get('tags')}";
                // }
                // return $meilisearch->search($query, $options);
            // }
        );
        return Inertia::render('search', compact('reports'));
    }

}
