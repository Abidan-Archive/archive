<?php

namespace App\Http\Controllers;

use App\Inspiring;
use App\Models\Event;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class HomeController extends Controller
{
    public function home(): Response {
        $quote = Inspiring::quote();
        $events = Event::select('id', 'name')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();
        $mostLiked = Report::with('likes')
            ->orderBy('likes_count', 'desc')
            ->latest()
            ->take(5)
            ->get()
            ->filter(fn($m) => $m->likes_count > 0);
        return inertia('Home', compact('events', 'mostLiked', 'quote'));
    }

    public function about(): Response {
        return inertia('About');
    }

    /**
     * The frontend Redirect page returns type and context params
     * Use these parameters to figure out where to redirect them
     * and with what data
     */
    public function handleRedirect(Request $request): RedirectResponse {
        $type = $request->input('type');
        $context = $request->input('context');

        switch($type) {
            case 'report':
                $report = Report::where('legacy_permalink', $context)->first();
                if ($report != null)
                    return to_route('report.show', $report);
                break;
        }
        return to_route('home');
    }

    /**
     * Legacy application used URL fragments as part of its permalinks
     * These can only be handled by the frontend
     *
     * This route catches all redirects to be handled by the frontend.
     * It then determines the redirect type and context and redirects
     * the user to the handleRedirect route where they are finally
     * redirected to the appropriate location
     */
    public function redirect(): Response {
        return inertia('Redirect');
    }
}
