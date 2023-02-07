<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;
// use MeiliSearch\MeiliSearch\Indexes;

class HomeController extends Controller
{
    public function home(): View {
        $events = Event::with('reports')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('home', compact('events'));
    }

    public function search(Request $request): View {
        $reports = Report::search(
            query: trim($request->input('q'))
            // query: trim($request->input('q')),
            // callback: function(Indexes $meilisearch, string $query, array $options) use ($request) {
            //     if ($request->has(key: 'tags')) {
            //         $options['filter'] = "tags = {$request->get('tags')}";
            //     }
            //     return $meilisearch->search($query, $options);
            // }
        )->get();
        return view('search', compact('reports'));
    }

}
