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
    public function __construct() {
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
        dd('I dont yet know what this page is doing');
        $event->load('sources');
        return inertia('Report/Create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Inertia\Response
     */
    public function show(Report $report): Response
    {
        $report->event;
        return inertia('Report/Show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Inertia\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Inertia\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Inertia\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
