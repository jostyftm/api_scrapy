<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\WebisteSaveRequest;
use App\Http\Requests\WebsiteUpdateRequest;
use App\Models\Search;
use App\Models\Spider;
use App\Models\Website;
use App\Models\WebsiteConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $websites = Website::all();

        return $this->successResponse($websites);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WebisteSaveRequest $request)
    {
        $website = Website::create($request->all());
        $website->webConfiguration()->save(new WebsiteConfiguration($request->all()));

        return $this->successResponse($website);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function show(Website $website)
    {
        $website->webConfiguration; 

        return $this->successResponse($website);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function update(WebsiteUpdateRequest $request, Website $website)
    {

        DB::transaction(function() use ($website, $request){

            $website->fill($request->all());
            $configuration = $website->webConfiguration;
            
            $configuration->fill($request->all());
            
            $website->update();
            $configuration->update();
            
        });
        
        return $this->successResponse($website);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function destroy(Website $website)
    {
        $website->delete();

        return $this->successResponse([], 204);
    }


    /**
     * 
     * 
     */
    public function getConfig(Website $website)
    {
        $website->webConfiguration;

        return $this->successResponse($website);
    }

    public function updateConfig(Request $request, WebsiteConfiguration $websiteConfiguration)
    {
        $websiteConfiguration->fill($request->all());
        $websiteConfiguration->state = ($request->state == "true") ? true : false;
        $websiteConfiguration->update();

        return $this->successResponse($websiteConfiguration);
    }

    public function testConfig(Request $request, WebsiteConfiguration $websiteConfiguration)
    {
    }
}
