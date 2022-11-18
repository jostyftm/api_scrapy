<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ResourceTypeSaveRequest;
use App\Http\Requests\ResourceTypeUpdateRequest;

use App\Models\ResourceType;

class ResourceTypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resourceTypes = ResourceType::all();

        return $this->successResponse($resourceTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ResourceTypeSaveRequest $request)
    {
        return $this->successResponse(ResourceType::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResourceType  $resourceType
     * @return \Illuminate\Http\Response
     */
    public function show(ResourceType $resourceType)
    {
        return $this->successResponse($resourceType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResourceType  $resourceType
     * @return \Illuminate\Http\Response
     */
    public function update(ResourceTypeUpdateRequest $request, ResourceType $resourceType)
    {   
        return $this->successResponse($resourceType->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ResourceType  $resourceType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResourceType $resourceType)
    {
        $resourceType->delete();

        return $this->successResponse([], 204);
    }
}
