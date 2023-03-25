<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Report;
use App\Inspiring;
use Inertia\Response;

class HomeController extends Controller
{
    public function home(): Response {
        $quote = Inspiring::quote();
        $events = Event::select('id', 'name')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();
        return inertia('Home', compact('events', 'quote'));
    }

    public function about(): Response {
        return inertia('About');
    }

    /**
     * Redirects old permalinks to new route
     * Url fragments aren't sent in by backend
     * So using frontend redirect page
     */
    public function redirect(): Response {
        return inertia('Redirect');
    }

}
