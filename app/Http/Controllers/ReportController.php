<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')
            ->except('index', 'show');
        $this->authorizeResource(Report::class, 'report');
    }

    /**
     * Return all the reports
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $reports = Report::with('event')->paginate(20);

        return inertia('Report/Index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(Event $event)
    {
        // Create without a stub
        $event->load('sources');

        return inertia('Report/Create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Inertia\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report): Response
    {
        return inertia('Report/Show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Inertia\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Inertia\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Inertia\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
