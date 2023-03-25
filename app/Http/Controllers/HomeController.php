<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Inspiring;
use Inertia\Response;

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

    public function redirect(): Response {
        return inertia('Redirect');
    }

}
