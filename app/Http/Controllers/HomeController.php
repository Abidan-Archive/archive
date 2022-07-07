<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Report;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home() {
        $events = Event::with('reports')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('home', compact('events'));
    }

    public function search(Request $request) {
        $reports = Report::search($request->input('q'))->get();
        dd($reports);
        return view('search', compact('reports'));
    }
}
