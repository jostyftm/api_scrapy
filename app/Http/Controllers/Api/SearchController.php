<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SearchSaveRequest;
use App\Http\Requests\SearchUpdateRequest;
use App\Models\Search;

class SearchController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $searches = Search::with('website')->get();

        return $this->successResponse($searches);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SearchSaveRequest $request)
    {
        return $this->successResponse(Search::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function show(Search $search)
    {
        return $this->successResponse($search);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function update(SearchUpdateRequest $request, search $search)
    {
        return $this->successResponse($search->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function destroy(Search $search)
    {
        $search->delete();

        return $this->successResponse([], 204);
    }
}
