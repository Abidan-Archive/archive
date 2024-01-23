<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;

class TagController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth')
             ->except('index', 'show');
        $this->authorizeResource(Tag::class, 'tag');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $tags = Tag::select('id','name', 'color')->withCount('reports')->get();
        return inertia('Tag/Index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Tag/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreTagRequest   $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTagRequest $request)
    {
        $validated = $request->validated();
        $validated['name'] = strtolower($validated['name']);
        $tag = Tag::create($validated);
        return to_route('tag.update', compact('tag'))
            ->with('flash', ['message'=>'Tag successfully created.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     */
    public function show(Tag $tag)
    {
        $tag->loadCount('reports');
        $tag->load('reports');
        $tag = $tag->only('id', 'name', 'color', 'reports_count', 'reports');
        return inertia('Tag/Show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return inertia('Tag/Edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateTagRequest   $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $validated = $request->validated();
        $validated['name'] = strtolower($validated['name']);
        $tag->update($validated);
        return to_route('tag.update', compact('tag'))
            ->with('flash', ['message' =>'Tag successfully updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return to_route('tag.index')->with('flash', ['message' => 'Tag successfully deleted.']);
    }
}
