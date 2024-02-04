<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Response;

class SearchController extends Controller
{
    public function search(Request $request): Response {

        $search = Report::search($request->input('query') ?? '');

        $includeTags = $request->input('includeTags');
        if ($includeTags != null) {
            $search->whereIn('tags', explode(',', $includeTags));
        }

        $excludeTags = $request->input('excludeTags');
        if ($excludeTags != null) {
            $search->whereNotIn('tags', explode(',', $excludeTags));
        }

        $orderBy = $request->input('orderBy');
        if ($orderBy != null && preg_match('/^(id|likes|date),(ASC|DESC)$/', $orderBy)) {
            $orderBy = explode(',', $orderBy);
            $search->orderBy($orderBy[0], $orderBy[1]);
        }

        // Execute search
        $reports = $search->paginate(20);

        // We always pass all the tags
        $tags = Tag::select('name')->get()->pluck('name');
        return inertia('Search', compact('reports', 'tags'));
    }
}
