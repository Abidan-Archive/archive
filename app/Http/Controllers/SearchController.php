<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Response;

class SearchController extends Controller
{
    public function search(Request $request): Response {
        $paginate = Report::search($request->input('query') ?? '')
            ->paginate(20)
            ->toArray();
        $reports = $paginate['data'];
        unset($paginate['data']);

        return inertia('Search', compact('reports', 'paginate'));
    }
}
